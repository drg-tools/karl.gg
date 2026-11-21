<?php

namespace Tests\Feature;

use App\Character;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalculatorPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_open_the_unlisted_calculator_with_every_class(): void
    {
        $character = Character::factory()->create(['name' => 'Gunner']);

        $response = $this->get('/calculator')->assertOk();

        $response->assertViewHas('calculator', fn (array $calculator): bool => $calculator['characters']->first() === [
            'id' => $character->id, 'name' => 'Gunner', 'url' => route('builder.catalog', $character),
        ]);
        $response->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    }
}
