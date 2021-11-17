<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BuilderTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testLoadingBuilder()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/build')
                ->assertSee('Save Loadout');
        });
    }

    public function testSelectingClassAndPrimary()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/build')
                ->waitForText("Engineer")
                ->click('@engineer')
                ->waitForText("Select a Primary")
                ->clickLink('Select')
                ->waitFor("@primary-selectors")
                ->assertSee("Warthog")
                ->click('@mod-53')
                ->waitFor('@credits-cost')
                ->assertVisible('@credits-cost')
                ->assertVisible('@bismor-cost');

        });
    }
}
