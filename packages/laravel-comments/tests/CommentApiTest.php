<?php

namespace Hazzard\Comments\Tests;

use Carbon\Carbon;
use Hazzard\Comments\Comment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Event;
use Hazzard\Comments\Events\CommentWasPosted;

class CommentApiTest extends TestCase
{
    /** @test */
    public function it_can_fetch_comments_for_a_given_page()
    {
        factory(Comment::class, 2)->create(['page_id' => 1]);
        factory(Comment::class, 2)->create(['page_id' => 2]);

        $this->getJson('/comments?page_id=1')
            ->assertSuccessful()
            ->assertJsonCount(2, 'comments');
    }

    /** @test */
    public function it_can_fetch_comments_for_a_commentable_entity()
    {
        factory(Comment::class, 2)->create(['page_id' => 1]);

        factory(Comment::class, 2)->create([
            'commentable_id' => 1,
            'commentable_type' => Page::class,
        ]);

        $this->getJson('/comments?commentable_id=1&commentable_type='.Page::class)
            ->assertSuccessful()
            ->assertJsonCount(2, 'comments');
    }

    /** @test */
    public function it_can_fetch_pending_comments_if_the_user_is_authenticated()
    {
        factory(Comment::class, 2)->create(['page_id' => 1, 'status' => Comment::STATUS_PENDING, 'user_id' => $this->testUser->id]);
        factory(Comment::class, 2)->create(['page_id' => 1, 'user_id' => $this->testUser->id]);

        $this->getJson('/comments?page_id=1')
            ->assertSuccessful()
            ->assertJsonCount(2, 'comments');

        $this->actingAs($this->testUser)
            ->getJson('/comments?page_id=1')
            ->assertJsonCount(4, 'comments');
    }

    /** @test */
    public function it_can_fetch_pending_comments_if_author_email_cookie_is_set()
    {
        factory(Comment::class, 2)->create(['page_id' => 1, 'status' => Comment::STATUS_PENDING, 'author_email' => 'foo@bar.app']);
        factory(Comment::class, 2)->create(['page_id' => 1, 'author_email' => 'foo@bar.app']);

        $this->getJson('/comments?page_id=1')
            ->assertSuccessful()
            ->assertJsonCount(2, 'comments');

        $this->withCookie('comment_author_email', 'foo@bar.app')
            ->get('/comments?page_id=1', ['Accept' => 'application/json'])
            ->assertSuccessful()
            ->assertJsonCount(4, 'comments');
    }

    /** @test */
    public function it_can_paginate_comments()
    {
        factory(Comment::class, 5)->create(['page_id' => 1]);

        config(['comments.per_page' => 2]);

        $this->getJson('/comments?page_id=1')
            ->assertSuccessful()
            ->assertJsonCount(2, 'comments');

        $this->getJson('/comments?page_id=1&page=3')
            ->assertSuccessful()
            ->assertJsonCount(1, 'comments');
    }

    /** @test */
    public function it_can_order_comments_by_date()
    {
        factory(Comment::class)->create(['created_at' => Carbon::now()->addHours(1), 'page_id' => 1, 'content' => 'first']);
        factory(Comment::class)->create(['created_at' => Carbon::now()->addHours(3), 'page_id' => 1, 'content' => 'third']);
        factory(Comment::class)->create(['created_at' => Carbon::now()->addHours(2), 'page_id' => 1, 'content' => 'second']);

        $this->getJson('/comments?page_id=1&order=latest')
            ->assertSuccessful()
            ->assertSeeInOrder(['third', 'second', 'first']);

        $this->json('GET', '/comments?page_id=1&order=oldest')
            ->assertSuccessful()
            ->assertSeeInOrder(['first', 'second', 'third']);
    }

    /** @test */
    public function it_can_order_comments_by_upvotes()
    {
        factory(Comment::class)->create(['page_id' => 1, 'upvotes' => 1]);
        factory(Comment::class)->create(['page_id' => 1, 'upvotes' => 3]);
        factory(Comment::class)->create(['page_id' => 1, 'upvotes' => 2]);

        $comments = $this->getJson('/comments?page_id=1&order=best')
            ->assertSuccessful()
            ->json('comments');

        $this->assertEquals(2, $comments[0]['id']);
        $this->assertEquals(3, $comments[1]['id']);
        $this->assertEquals(1, $comments[2]['id']);
    }

    /** @test */
    public function it_can_validate_a_comment_request()
    {
        $this->postJson('/comments')
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'content', 'author_name', 'author_email',
                'commentable_id', 'commentable_type',
            ]);
    }

    /** @test */
    public function it_can_validate_the_comment_content_length()
    {
        config(['comments.max_length' => 4]);

        $this->postJson('/comments', ['content' => 'a'])
           ->assertStatus(422)
            ->assertJsonValidationErrors(['content' => 'The content must be at least 2 characters.']);

        $this->postJson('/comments', ['content' => 'abcde'])
            ->assertStatus(422)
            ->assertSeeText('not be greater than 4 characters.');
    }

    /** @test */
    public function it_requires_captcha_for_guest_users()
    {
        config(['comments.captcha_guest' => true]);

        $this->postJson('/comments')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['captcha']);
    }

    /** @test */
    public function it_requires_captcha_auth_users()
    {
        config(['comments.captcha_auth' => true]);

        $this->actingAs($this->testUser)
            ->postJson('/comments')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['captcha']);
    }

    /** @test */
    public function it_does_not_allow_guests_to_post_comments()
    {
        config(['comments.allow_guests' => false]);

        $this->postJson('/comments')
            ->assertStatus(403);
    }

    /** @test */
    public function it_can_check_if_the_parent_comment_exists()
    {
        $this->actingAs($this->testUser)
            ->postJson('/comments', [
                'content' => 'foo',
                'page_id' => 'bar',
                'root_id' => 1,
                'parent_id' => 1,
            ])
            ->assertJsonValidationErrors(['root_id', 'parent_id']);

        factory(Comment::class)->create();

        $this->actingAs($this->testUser)
            ->postJson('/comments', [
                'content' => 'foo',
                'page_id' => 'bar',
                'root_id' => 1,
                'parent_id' => 1,
            ])
            ->assertSuccessful();
    }

    /** @test */
    public function it_can_check_if_the_parent_comment_is_approved()
    {
        factory(Comment::class)->create([
            'status' => Comment::STATUS_PENDING,
        ]);

        $this->actingAs($this->testUser)
            ->postJson('/comments', [
                'content' => 'foo',
                'page_id' => 'bar',
                'root_id' => 1,
                'parent_id' => 1,
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['parent_id']);
    }

    /** @test */
    public function it_can_check_if_the_commentable_entity_exists()
    {
        $this->actingAs($this->testUser)
            ->postJson('/comments', [
                'content' => 'foo',
                'commentable_id' => 1,
                'commentable_type' => 'Post',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['commentable_id']);

        $this->actingAs($this->testUser)
            ->postJson('/comments', [
                'content' => 'foo',
                'commentable_id' => 1,
                'commentable_type' => Page::class,
            ])
            ->assertSuccessful();
    }

    /** @test */
    public function it_can_throttle_comment_posts()
    {
        config([
            'comments.throttle' => true,
            'comments.throttle_max_attempts' => 2,
            'comments.throttle_decay_minutes' => 1,
            'comments.detect_duplicates' => false,
            'comments.max_unapproved' => false,
        ]);

        $data = ['content' => 'foo', 'page_id' => 'bar'];

        $this->actingAs($this->testUser);
        $this->postJson('/comments', $data)->assertSuccessful();
        $this->postJson('/comments', $data)->assertSuccessful();
        $this->postJson('/comments', $data)->assertStatus(429);
    }

    /** @test */
    public function it_remembers_the_author_email()
    {
        $author = [
            'author_name' => 'Test User',
            'author_email' => 'test@example.org',
            'author_url' => 'http://example.org',
        ];

        $this->postJson('/comments', array_merge($author, ['content' => 'foo','page_id' => 'bar']))
            ->assertSuccessful()
            ->assertCookie('comment_author_email', $author['author_email']);
    }

    /** @test */
    public function it_can_post_a_new_comment()
    {
        Event::fake();

        $this->actingAs($this->testUser)
            ->postJson('/comments', [
                'content' => 'foo',
                'page_id' => 'bar',
            ])
            ->assertSuccessful();

        Event::assertDispatched(CommentWasPosted::class);
    }

    /** @test */
    public function it_can_edit_the_comment_content()
    {
        $comment = factory(Comment::class)->create(['user_id' => $this->testUser->id]);

        $this->actingAs($this->testUser)
            ->patchJson('/comments/1', [
                'content' => 'updated',
                'status' => Comment::STATUS_PENDING,
                'author_email' => 'foo@bar.com',
            ])
            ->assertSuccessful()
            ->assertJson([
                'content' => 'updated',
                'status' => Comment::STATUS_APPROVED,
            ]);
    }

    /** @test */
    public function it_can_edit_multiple_attributes_if_is_moderator()
    {
        $comment = factory(Comment::class)->create(['user_id' => $this->testUser->id]);

        Gate::define('moderate-comments', function ($user) {
            return true;
        });

        $this->actingAs($this->testUser)
            ->patchJson('/comments/1', [
                'content' => 'updated',
                'status' => Comment::STATUS_PENDING,
                'author_email' => 'foo@bar.com',
            ])
            ->assertSuccessful()
            ->assertJson([
                'content' => 'updated',
                'status' => Comment::STATUS_PENDING,
            ]);
    }

    /** @test */
    public function it_can_not_edit_a_comment_if_is_a_guest()
    {
        $this->postJson('/comments', [
            'content' => 'foo',
            'page_id' => 'bar',
            'author_name' => 'Test User',
            'author_email' => 'test@example.org',
            'author_url' => 'http://example.org',
        ]);

        $this->patchJson('/comments/1', ['content' => 'updated'])
            ->assertStatus(401);
    }

    /** @test */
    public function it_can_bulk_update_comments()
    {
        Gate::define('moderate-comments', function ($user) {
            return true;
        });

        factory(Comment::class, 5)->create(['user_id' => $this->testUser->id]);

        $this->actingAs($this->testUser)
            ->postJson('/comments/bulk-update', [
                'ids' => [1, 3],
                'status' => Comment::STATUS_PENDING,
            ])
            ->assertSuccessful()
            ->assertJson(['updated' => 2]);

        $this->actingAs($this->testUser)
            ->postJson('/comments/bulk-update', [
                'ids' => [2, 4],
                'status' => 'delete',
            ])
            ->assertSuccessful()
            ->assertJson(['deleted' => 2]);

        $this->assertDatabaseHas('comments', ['id' => 1, 'status' => Comment::STATUS_PENDING]);
        $this->assertDatabaseHas('comments', ['id' => 3, 'status' => Comment::STATUS_PENDING]);

        $this->assertDatabaseMissing('comments', ['id' => 2]);
        $this->assertDatabaseMissing('comments', ['id' => 4]);
    }

    /** @test */
    public function it_can_delete_a_comment()
    {
        $comment = factory(Comment::class)->create(['user_id' => $this->testUser->id]);

        $this->actingAs($this->testUser)
            ->deleteJson("/comments/{$comment->id}")
            ->assertSuccessful();

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }

    /** @test */
    public function it_can_report_a_comment()
    {
        $comment = factory(Comment::class)->create(['user_id' => $this->testUser->id]);

        $this->actingAs($this->testUser)
            ->putJson("/comments/{$comment->id}/report")
            ->assertSuccessful();

        $this->assertDatabaseHas('comment_reports', [
            'comment_id' => $comment->id,
            'user_id' => $this->testUser->id,
        ]);
    }

    /** @test */
    public function it_can_mark_the_comment_as_pending_if_it_has_to_many_reports()
    {
        config(['comments.max_reports' => 1]);

        $comment = factory(Comment::class)->create(['user_id' => $this->testUser->id]);

        $this->actingAs($this->testUser)
            ->putJson("/comments/{$comment->id}/report")
            ->assertSuccessful();

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('comment_reports', [
            'comment_id' => $comment->id,
            'user_id' => $this->testUser->id,
        ]);
    }

    /** @test */
    public function it_can_render_comment_preview()
    {
        config(['comments.markdown' => true]);

        $this->postJson('/comments/preview', [
                'content' => 'foo [bar](http://example.org)',
            ])
            ->assertSuccessful();
    }
}

class Page
{
    use \Hazzard\Comments\Commentable;

    public function find($id)
    {
        return $id === 1 ? new static : null;
    }

    public function getKeyName()
    {
        return 'id';
    }
}
