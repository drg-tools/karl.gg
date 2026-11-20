<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_navigation_marks_the_current_page_and_offers_login(): void
    {
        $response = $this->get('/blog');

        $this->assertMatchesRegularExpression(
            '#<a href="/blog" class="site-nav__link is-active"\s+aria-current="page"#',
            $response->getContent()
        );
        $response->assertOk()
            ->assertSee('<a class="site-nav__login" href="/login">Login</a>', false)
            ->assertDontSee('id="logout-form"', false);
    }

    public function test_signed_in_navigation_signs_out_through_one_logout_form(): void
    {
        $user = User::factory()->create(['name' => 'Karl']);

        $response = $this->actingAs($user)->get('/blog')->assertOk();

        $this->assertSame(1, substr_count($response->getContent(), 'id="logout-form"'));
        $this->assertSame(2, substr_count($response->getContent(), 'form="logout-form"'));
        $response->assertSee('action="'.route('logout').'"', false)
            ->assertDontSee('<a class="site-nav__login" href="/login">', false);
    }

    public function test_site_notice_appears_on_the_dashboard_only(): void
    {
        config(['app.banner.enabled' => true, 'app.banner.message' => 'Rock and stone notice']);

        $dashboard = $this->get('/');
        $blog = $this->get('/blog');

        $dashboard->assertOk()->assertSeeText('Rock and stone notice')->assertSee('aria-label="Dismiss notice"', false);
        $blog->assertOk()->assertDontSeeText('Rock and stone notice');
    }
}
