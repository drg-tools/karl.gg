<?php

namespace Database\Factories;

use App\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraphs(6, true),
            'user_id' => 1,
            'published_at' => $this->faker->dateTimeThisYear,
        ];
    }
}
