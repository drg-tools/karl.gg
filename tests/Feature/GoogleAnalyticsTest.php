<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoogleAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_get_the_tag_marked_as_signed_out(): void
    {
        config(['app.google_analytics_id' => 'G-TEST123']);

        $this->get('/blog')
            ->assertOk()
            ->assertSee('googletagmanager.com/gtag/js?id=G-TEST123', false)
            ->assertSee("{ signed_in: 'no' }", false);
    }

    public function test_signed_in_players_get_the_tag_marked_as_signed_in(): void
    {
        config(['app.google_analytics_id' => 'G-TEST123']);

        $this->actingAs(User::factory()->create())
            ->get('/blog')
            ->assertOk()
            ->assertSee("{ signed_in: 'yes' }", false);
    }

    public function test_tag_is_left_out_without_a_measurement_id(): void
    {
        config(['app.google_analytics_id' => null]);

        $this->get('/blog')
            ->assertOk()
            ->assertDontSee('gtag', false);
    }
}
