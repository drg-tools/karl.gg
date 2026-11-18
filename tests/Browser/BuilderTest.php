<?php

namespace Tests\Browser;

use App\Gun;
use App\Loadout;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BuilderTest extends DuskTestCase
{
    public function test_builder_saves_a_guest_loadout_and_renders_its_preview(): void
    {
        $gun = Gun::where('character_slot', 1)->whereHas('character', fn ($query) => $query->where('name', 'Engineer'))->firstOrFail();
        $mod = $gun->mods()->orderBy('mod_tier')->firstOrFail();
        $name = 'Browser prototype '.uniqid();

        $this->browse(function (Browser $browser) use ($gun, $mod, $name): void {
            $browser->visit('/build')
                ->waitForText('Engineer')
                ->click('@engineer')
                ->waitFor('#weapon-1')
                ->select('#weapon-1', (string) $gun->id)
                ->waitFor('@mod-'.$mod->id)
                ->click('@mod-'.$mod->id)
                ->waitFor('@credits-cost')
                ->assertVisible('@credits-cost')
                ->screenshot('builder-prototype-desktop')
                ->resize(390, 844)
                ->screenshot('builder-prototype-mobile')
                ->type('#loadout-name', $name)
                ->type('#loadout-description', '# Browser guide')
                ->press('Save Loadout')
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

    public function test_switching_classes_clears_weapon_selection(): void
    {
        $gun = Gun::where('character_slot', 1)->whereHas('character', fn ($query) => $query->where('name', 'Engineer'))->firstOrFail();
        $this->browse(function (Browser $browser) use ($gun): void {
            $browser->visit('/build')->waitForText('Engineer')->click('@engineer')
                ->waitFor('#weapon-1')->select('#weapon-1', (string) $gun->id)
                ->click('@scout')->waitForText('Deepcore GK2')
                ->assertSeeIn('#weapon-1 option:checked', 'Select a weapon');
        });
    }
}
