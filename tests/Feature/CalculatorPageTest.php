<?php

namespace Tests\Feature;

use App\Character;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalculatorPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_open_the_calculator_with_every_class(): void
    {
        $character = Character::factory()->create(['name' => 'Gunner']);

        $response = $this->get('/calculator')->assertOk();

        $response->assertViewHas('calculator', fn (array $calculator): bool => $calculator['characters']->first() === [
            'id' => $character->id, 'name' => 'Gunner', 'url' => route('builder.catalog', $character),
        ]);
        $response->assertDontSee('name="robots"', false);
    }

    public function test_loadouts_menu_links_to_the_calculator_and_marks_it_current(): void
    {
        $response = $this->get('/calculator')->assertOk();

        $this->assertMatchesRegularExpression(
            '#<a href="'.preg_quote(route('calculator'), '#').'" class="site-nav__menu-link is-active"\s+aria-current="page"#',
            $response->getContent()
        );
        $this->assertMatchesRegularExpression(
            '#<a[^>]+href="'.preg_quote(route('calculator'), '#').'"[^>]*>\s*Calculator <span class="site-nav__badge">Beta</span>\s*</a>#',
            $this->get('/blog')->assertOk()->getContent()
        );
    }
}
