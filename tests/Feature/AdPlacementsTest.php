<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdPlacementsTest extends TestCase
{
    use RefreshDatabase;

    public function test_browse_shows_its_sidebar_and_footer_units(): void
    {
        config(['app.enable_ads' => true]);

        $this->get('/browse')
            ->assertOk()
            ->assertSee('adsbygoogle.js?client=ca-pub-3237881937260629', false)
            ->assertSee('data-ad-slot="6546353660"', false)
            ->assertSee('data-ad-slot="3816695658"', false);
    }

    public function test_ads_are_left_out_when_disabled(): void
    {
        config(['app.enable_ads' => false]);

        $this->get('/browse')
            ->assertOk()
            ->assertDontSee('adsbygoogle', false);
    }
}
