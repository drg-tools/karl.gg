<?php

namespace Tests\Browser;

use App\Equipment;
use App\Gun;
use App\Loadout;
use App\Throwable;
use Facebook\WebDriver\Chrome\ChromeDevToolsDriver;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BuilderTest extends DuskTestCase
{
    public function test_builder_saves_a_guest_loadout_and_renders_its_preview(): void
    {
        $gun = Gun::where('character_slot', 1)->whereHas('character', fn ($query) => $query->where('name', 'Engineer'))->firstOrFail();
        $mod = $gun->mods()->orderBy('mod_tier')->firstOrFail();
        $name = 'Browser workbench '.uniqid();

        $this->browse(function (Browser $browser) use ($gun, $mod, $name): void {
            $this->freshBuilder($browser)
                ->resize(1440, 1000)
                ->screenshot('builder-class-selection')
                ->click('@engineer')
                ->waitFor('@weapon-'.$gun->id)
                ->click('@weapon-'.$gun->id)
                ->waitFor('@mod-'.$mod->id)
                ->assertAttribute('@mod-'.$mod->id, 'aria-pressed', 'true')
                ->waitFor('@credits-cost')
                ->assertVisible('@credits-cost')
                ->screenshot('builder-workbench-desktop')
                ->resize(500, 900)
                ->screenshot('builder-workbench-mobile')
                ->click('@section-review')
                ->waitFor('#loadout-name')
                ->press('Publish loadout')
                ->assertSee('Give your loadout a name before publishing.')
                ->type('#loadout-name', $name)
                ->type('#loadout-description', '# Browser guide')
                ->screenshot('builder-review-mobile')
                ->press('Publish loadout')
                ->waitForText('Loadout guide')
                ->assertSee($name)
                ->assertSee($gun->name)
                ->assertSee($mod->mod_name)
                ->assertSee('Browser guide');
        });

        $loadout = Loadout::where('name', $name)->firstOrFail();
        $this->assertNull($loadout->user_id);
        $this->assertTrue($loadout->mods()->whereKey($mod->id)->exists());
    }

    public function test_switching_classes_requires_confirmation_and_clears_weapon_selection(): void
    {
        $gun = Gun::where('character_slot', 1)->whereHas('character', fn ($query) => $query->where('name', 'Engineer'))->firstOrFail();
        $this->browse(function (Browser $browser) use ($gun): void {
            $this->freshBuilder($browser)->click('@engineer')
                ->waitFor('@weapon-'.$gun->id)->click('@weapon-'.$gun->id)
                ->click('@change-class')->click('@scout')
                ->assertSee('Switch to Scout?')
                ->press('Keep current class')
                ->assertSee('Keep Engineer')
                ->click('@scout')->click('@confirm-class')
                ->waitForText('Deepcore GK2')
                ->assertSee('Pick your primary.')
                ->assertDontSee('Supercharged Feed Mechanism');
        });
    }

    public function test_draft_survives_refresh_and_can_be_restored_at_the_review_step(): void
    {
        $gun = Gun::where('character_slot', 1)->whereHas('character', fn ($query) => $query->where('name', 'Engineer'))->firstOrFail();
        $this->browse(function (Browser $browser) use ($gun): void {
            $this->freshBuilder($browser)->click('@engineer')
                ->waitFor('@weapon-'.$gun->id)->click('@weapon-'.$gun->id)
                ->click('@section-review')->waitFor('#loadout-name')->type('#loadout-name', 'Recovered expedition')
                ->waitForText('Draft saved in this browser')
                ->refresh()->waitFor('@restore-draft')->click('@restore-draft')
                ->waitFor('#loadout-name')->assertInputValue('#loadout-name', 'Recovered expedition')
                ->assertSee($gun->name)
                ->click('@section-primary')->waitFor('@weapon-'.$gun->id)
                ->assertAttribute('@weapon-'.$gun->id, 'aria-pressed', 'true');
        });
    }

    public function test_changing_primary_preserves_secondary_upgrades_and_the_guide(): void
    {
        $guns = Gun::where('character_slot', 1)->whereHas('character', fn ($query) => $query->where('name', 'Engineer'))->take(2)->get();
        $secondary = Gun::where('character_slot', 2)->whereHas('character', fn ($query) => $query->where('name', 'Engineer'))->firstOrFail();
        $this->browse(function (Browser $browser) use ($guns, $secondary): void {
            $this->freshBuilder($browser)->click('@engineer')->waitFor('@weapon-'.$guns[0]->id)->click('@weapon-'.$guns[0]->id)
                ->click('@next-section')->waitFor('@weapon-'.$secondary->id)->click('@weapon-'.$secondary->id)
                ->click('@section-primary')->waitFor('@weapon-'.$guns[1]->id)->click('@weapon-'.$guns[1]->id)
                ->click('@section-secondary')->waitFor('@weapon-'.$secondary->id)
                ->assertAttribute('@weapon-'.$secondary->id, 'aria-pressed', 'true')
                ->assertSee('5 / 5 tiers');
        });
    }

    public function test_equipment_throwables_and_overclocks_are_included_when_publishing(): void
    {
        $gun = Gun::where('character_slot', 1)->whereHas('character', fn ($query) => $query->where('name', 'Engineer'))->firstOrFail();
        $overclock = $gun->overclocks()->firstOrFail();
        $equipment = Equipment::where('character_id', $gun->character_id)->whereHas('equipment_mods')->firstOrFail();
        $equipmentMod = $equipment->equipment_mods()->firstOrFail();
        $throwable = Throwable::where('character_id', $gun->character_id)->firstOrFail();
        $name = 'Complete expedition '.uniqid();

        $this->browse(function (Browser $browser) use ($gun, $overclock, $equipment, $equipmentMod, $throwable, $name): void {
            $this->freshBuilder($browser)->click('@engineer')->waitFor('@weapon-'.$gun->id)->click('@weapon-'.$gun->id)
                ->scrollIntoView('@overclock-'.$overclock->id)->click('@overclock-'.$overclock->id)
                ->click('@section-equipment')->waitFor('@equipment-'.$equipment->id)->click('@equipment-'.$equipment->id)
                ->waitFor('@mod-'.$equipmentMod->id)->click('@mod-'.$equipmentMod->id)
                ->assertDontSee('NaN')->screenshot('builder-equipment')
                ->click('@section-throwable')->waitFor('@throwable-'.$throwable->id)->click('@throwable-'.$throwable->id)
                ->assertDontSee('NaN')->screenshot('builder-throwables')
                ->click('@next-section')->waitFor('#loadout-name')->type('#loadout-name', $name)
                ->press('Publish loadout')->waitForText('Loadout guide')
                ->assertSee($overclock->overclock_name)->assertSee($equipmentMod->mod_name)->assertSee($throwable->name);
        });

        $loadout = Loadout::where('name', $name)->firstOrFail();
        $this->assertSame($throwable->id, $loadout->throwable_id);
        $this->assertTrue($loadout->overclocks()->whereKey($overclock->id)->exists());
        $this->assertTrue($loadout->equipment_mods()->whereKey($equipmentMod->id)->exists());
    }

    public function test_mobile_workbench_and_review_fit_a_phone_viewport(): void
    {
        $gun = Gun::where('character_slot', 1)->whereHas('character', fn ($query) => $query->where('name', 'Engineer'))->firstOrFail();
        $this->browse(function (Browser $browser) use ($gun): void {
            $this->freshBuilder($browser);
            $devTools = new ChromeDevToolsDriver($browser->driver);
            $devTools->execute('Emulation.setDeviceMetricsOverride', ['width' => 390, 'height' => 844, 'deviceScaleFactor' => 1, 'mobile' => true]);
            $browser->refresh()->waitForText('Who’s going down?')->assertScript('window.innerWidth', 390)
                ->assertScript('document.documentElement.scrollWidth <= window.innerWidth', true)
                ->screenshot('builder-classes-phone')
                ->click('@engineer')->waitFor('@weapon-'.$gun->id)->click('@weapon-'.$gun->id)
                ->assertScript('document.documentElement.scrollWidth <= window.innerWidth', true)
                ->screenshot('builder-workbench-phone')
                ->click('@section-review')->waitFor('#loadout-name')
                ->assertScript('document.documentElement.scrollWidth <= window.innerWidth', true)
                ->screenshot('builder-review-phone');
            $devTools->execute('Emulation.clearDeviceMetricsOverride');
        });
    }

    private function freshBuilder(Browser $browser): Browser
    {
        (new ChromeDevToolsDriver($browser->driver))->execute('Emulation.clearDeviceMetricsOverride');
        $browser->visit('/build');
        $browser->script('sessionStorage.clear();');

        return $browser->refresh()->waitForText('Who’s going down?');
    }
}
