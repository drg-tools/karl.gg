<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_index_keeps_its_seo_metadata(): void
    {
        $this->get(route('blog.index'))
            ->assertOk()
            ->assertSee('<title>Karl: Born Ready - Blog</title>', false)
            ->assertSee('<meta property="og:title" content="Blog">', false)
            ->assertSee('<meta name="twitter:site" content="@DrgTools">', false);
    }

    public function test_blog_post_keeps_its_article_seo_metadata(): void
    {
        $post = Post::factory()->for(User::factory())->create([
            'title' => 'Season 5 Field Report',
            'content' => '<p>Rock and stone, miners.</p>',
            'published_at' => Carbon::parse('2026-10-01 12:00:00'),
        ]);

        $this->get(route('blog.show', $post))
            ->assertOk()
            ->assertSee('<title>Karl: Born Ready - Season 5 Field Report</title>', false)
            ->assertSee('<meta name="description" content="&lt;p&gt;Rock and stone, miners.&lt;/p&gt;">', false)
            ->assertSee('<meta property="article:published_time" content="'.$post->published_at->toW3CString().'">', false)
            ->assertSee('<meta property="og:type" content="article">', false)
            ->assertSee('<meta property="article:tag" content="Deep Rock Galactic News">', false)
            ->assertSee('<script type="application/ld+json">', false)
            ->assertSee('"name":"Season 5 Field Report"', false)
            ->assertSee('<h1 class="blog-post__title">Season 5 Field Report</h1>', false);
    }

    public function test_blog_index_features_the_newest_published_post(): void
    {
        $author = User::factory()->create(['name' => 'Karl']);
        Post::factory()->for($author)->create(['title' => 'Older dispatch', 'published_at' => Carbon::now()->subWeek()]);
        Post::factory()->for($author)->create(['title' => 'Newest dispatch', 'published_at' => Carbon::now()->subDay()]);
        Post::factory()->for($author)->create(['title' => 'Scheduled dispatch', 'published_at' => Carbon::now()->addDay()]);

        $this->get(route('blog.index'))
            ->assertOk()
            ->assertSeeInOrder(['Newest dispatch', 'More from the blog', 'Older dispatch'])
            ->assertDontSee('Scheduled dispatch');
    }

    public function test_blog_post_links_to_other_published_posts(): void
    {
        $author = User::factory()->create();
        $post = Post::factory()->for($author)->create(['title' => 'Current dispatch', 'published_at' => Carbon::now()->subDay()]);
        Post::factory()->for($author)->create(['title' => 'Another dispatch', 'published_at' => Carbon::now()->subWeek()]);
        Post::factory()->for($author)->create(['title' => 'Scheduled dispatch', 'published_at' => Carbon::now()->addDay()]);

        $this->get(route('blog.show', $post))
            ->assertOk()
            ->assertSeeInOrder(['More from the blog', 'Another dispatch'])
            ->assertDontSee('Scheduled dispatch');
    }

    public function test_reading_time_rounds_up_and_has_a_floor_of_one_minute(): void
    {
        $this->assertSame(1, (new Post(['content' => '<p>Short</p>']))->reading_minutes);
        $this->assertSame(2, (new Post(['content' => str_repeat('word ', 201)]))->reading_minutes);
    }
}
