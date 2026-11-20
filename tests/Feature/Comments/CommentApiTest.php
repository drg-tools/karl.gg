<?php

namespace Tests\Feature\Comments;

use App\Comment;
use App\Comments\Formatter;
use App\User;
use Carbon\Carbon;
use Spatie\Permission\Models\Permission;

class CommentApiTest extends CommentsTestCase
{
    public function test_it_can_fetch_comments_for_a_given_page(): void
    {
        Comment::factory(2)->create(['page_id' => 1]);
        Comment::factory(2)->create(['page_id' => 2]);

        $this->getJson('/comments?page_id=1')
            ->assertSuccessful()
            ->assertJsonCount(2, 'comments');
    }

    public function test_it_can_fetch_pending_comments_if_the_user_is_authenticated(): void
    {
        Comment::factory(2)->create(['page_id' => 1, 'status' => Comment::STATUS_PENDING, 'user_id' => $this->testUser->id]);
        Comment::factory(2)->create(['page_id' => 1, 'user_id' => $this->testUser->id]);

        $this->getJson('/comments?page_id=1')
            ->assertSuccessful()
            ->assertJsonCount(2, 'comments');

        $this->actingAs($this->testUser)
            ->getJson('/comments?page_id=1')
            ->assertJsonCount(4, 'comments');
    }

    public function test_guest_email_cookie_does_not_grant_access_to_pending_comments(): void
    {
        Comment::factory(2)->create(['page_id' => 1, 'status' => Comment::STATUS_PENDING, 'author_email' => 'foo@bar.app']);
        Comment::factory(2)->create(['page_id' => 1, 'author_email' => 'foo@bar.app']);

        $this->getJson('/comments?page_id=1')
            ->assertSuccessful()
            ->assertJsonCount(2, 'comments');

        $this->withCookie('comment_author_email', 'foo@bar.app')
            ->get('/comments?page_id=1', ['Accept' => 'application/json'])
            ->assertSuccessful()
            ->assertJsonCount(2, 'comments');
    }

    public function test_it_can_paginate_comments(): void
    {
        Comment::factory(5)->create(['page_id' => 1]);

        config(['comments.per_page' => 2]);

        $this->getJson('/comments?page_id=1')
            ->assertSuccessful()
            ->assertJsonCount(2, 'comments');

        $this->getJson('/comments?page_id=1&page=3')
            ->assertSuccessful()
            ->assertJsonCount(1, 'comments');
    }

    public function test_empty_threads_ignore_hidden_and_orphaned_replies(): void
    {
        $hidden = Comment::factory()->create(['page_id' => 'bar', 'status' => Comment::STATUS_PENDING]);
        Comment::factory()->create(['page_id' => 'bar', 'parent_id' => $hidden->id, 'root_id' => $hidden->id]);
        Comment::factory()->create(['page_id' => 'bar', 'parent_id' => $hidden->id]);

        $this->getJson('/comments?page_id=bar&page=2')
            ->assertOk()
            ->assertJsonPath('total', 0)
            ->assertJsonPath('pagination.current_page', 1)
            ->assertJsonCount(0, 'comments');
    }

    public function test_deep_link_uses_the_current_order_and_only_visible_threads_on_the_requested_page(): void
    {
        config(['comments.per_page' => 1]);
        $older = Comment::factory()->create(['page_id' => 'bar', 'upvotes' => 0, 'created_at' => now()->subDay()]);
        $newer = Comment::factory()->create(['page_id' => 'bar', 'upvotes' => 0, 'created_at' => now()]);
        $reply = Comment::factory()->create(['page_id' => 'bar', 'parent_id' => $older->id, 'root_id' => $older->id]);
        Comment::factory()->create(['page_id' => 'bar', 'upvotes' => 50, 'status' => Comment::STATUS_PENDING]);
        $otherPageTarget = Comment::factory()->create(['page_id' => 'elsewhere', 'upvotes' => 1]);
        Comment::factory()->create(['page_id' => 'elsewhere', 'upvotes' => 2]);

        $this->getJson('/comments?page_id=bar&order=best&page=1')
            ->assertOk()
            ->assertJsonPath('comments.0.id', $newer->id)
            ->assertJsonPath('total', 3);

        $this->getJson("/comments?page_id=bar&order=best&target={$reply->id}")
            ->assertOk()
            ->assertJsonPath('pagination.current_page', 2)
            ->assertJsonPath('comments.0.id', $older->id)
            ->assertJsonPath('comments.0.replies.0.id', $reply->id);

        $this->getJson("/comments?page_id=bar&order=oldest&target={$reply->id}")
            ->assertOk()
            ->assertJsonPath('pagination.current_page', 1)
            ->assertJsonPath('comments.0.id', $older->id);

        $this->getJson("/comments?page_id=bar&order=best&target={$otherPageTarget->id}")
            ->assertOk()
            ->assertJsonPath('pagination.current_page', 1)
            ->assertJsonPath('comments.0.id', $newer->id);

        $this->getJson('/comments?page_id=bar&order=best&page=99')
            ->assertOk()
            ->assertJsonPath('pagination.current_page', 2)
            ->assertJsonPath('comments.0.id', $older->id);
    }

    public function test_it_can_order_comments_by_date(): void
    {
        Comment::factory()->create(['created_at' => Carbon::now()->addHours(1), 'page_id' => 1, 'content' => 'first']);
        Comment::factory()->create(['created_at' => Carbon::now()->addHours(3), 'page_id' => 1, 'content' => 'third']);
        Comment::factory()->create(['created_at' => Carbon::now()->addHours(2), 'page_id' => 1, 'content' => 'second']);

        $this->getJson('/comments?page_id=1&order=latest')
            ->assertSuccessful()
            ->assertSeeInOrder(['third', 'second', 'first']);

        $this->json('GET', '/comments?page_id=1&order=oldest')
            ->assertSuccessful()
            ->assertSeeInOrder(['first', 'second', 'third']);
    }

    public function test_it_can_order_comments_by_upvotes(): void
    {
        $leastVoted = Comment::factory()->create(['page_id' => 1, 'upvotes' => 1]);
        $mostVoted = Comment::factory()->create(['page_id' => 1, 'upvotes' => 3]);
        $secondMostVoted = Comment::factory()->create(['page_id' => 1, 'upvotes' => 2]);

        $comments = $this->getJson('/comments?page_id=1&order=best')
            ->assertSuccessful()
            ->json('comments');

        $this->assertSame([$mostVoted->id, $secondMostVoted->id, $leastVoted->id], array_column($comments, 'id'));
    }

    public function test_it_can_validate_a_comment_request(): void
    {
        $this->postJson('/comments')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['content', 'author_name', 'author_email', 'page_id']);
    }

    public function test_it_can_validate_the_comment_content_length(): void
    {
        config(['comments.max_length' => 4]);

        $this->postJson('/comments', ['content' => 'a'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['content' => 'The content must be at least 2 characters.']);

        $this->postJson('/comments', ['content' => 'abcde'])
            ->assertStatus(422)
            ->assertSeeText('not be greater than 4 characters.');
    }

    public function test_posting_a_non_text_comment_returns_422(): void
    {
        $this->actingAs($this->testUser)
            ->postJson('/comments', ['content' => ['invalid'], 'page_id' => 'loadout-1'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('content');

        $this->assertDatabaseMissing('comments', ['page_id' => 'loadout-1']);
    }

    public function test_it_can_check_if_the_parent_comment_exists(): void
    {
        $this->actingAs($this->testUser)
            ->postJson('/comments', [
                'content' => 'foo',
                'page_id' => 'bar',
                'root_id' => 1,
                'parent_id' => 1,
            ])
            ->assertJsonValidationErrors('parent_id');

        $parent = Comment::factory()->create(['page_id' => 'bar']);

        $this->actingAs($this->testUser)
            ->postJson('/comments', [
                'content' => 'foo',
                'page_id' => 'bar',
                'root_id' => $parent->id,
                'parent_id' => $parent->id,
            ])
            ->assertSuccessful();
    }

    public function test_replies_inherit_the_root_of_their_parent(): void
    {
        $root = Comment::factory()->create(['page_id' => 'bar']);
        $parent = Comment::factory()->create(['page_id' => 'bar', 'parent_id' => $root->id, 'root_id' => $root->id]);

        $this->actingAs($this->testUser)
            ->postJson('/comments', ['page_id' => 'bar', 'content' => 'reply', 'parent_id' => $parent->id])
            ->assertCreated()
            ->assertJsonPath('root_id', $root->id)
            ->assertJsonPath('parent_id', $parent->id);
    }

    public function test_replies_reject_a_parent_on_another_page_or_a_root_in_another_thread(): void
    {
        $parent = Comment::factory()->create(['page_id' => 'bar']);
        $otherRoot = Comment::factory()->create(['page_id' => 'bar']);
        $reply = ['content' => 'reply', 'parent_id' => $parent->id];

        $this->actingAs($this->testUser)
            ->postJson('/comments', $reply + ['page_id' => 'elsewhere'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('parent_id');

        $this->postJson('/comments', $reply + ['page_id' => 'bar', 'root_id' => $otherRoot->id])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('root_id');

        $this->assertDatabaseCount('comments', 2);
    }

    public function test_it_can_check_if_the_parent_comment_is_approved(): void
    {
        $parent = Comment::factory()->create([
            'page_id' => 'bar',
            'status' => Comment::STATUS_PENDING,
        ]);

        $this->actingAs($this->testUser)
            ->postJson('/comments', [
                'content' => 'foo',
                'page_id' => 'bar',
                'root_id' => $parent->id,
                'parent_id' => $parent->id,
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['parent_id']);
    }

    public function test_it_can_throttle_comment_posts(): void
    {
        config(['comments.posts_per_minute' => 2]);

        $data = ['content' => 'foo', 'page_id' => 'bar'];

        $this->actingAs($this->testUser);
        $this->postJson('/comments', $data)->assertSuccessful();
        $this->postJson('/comments', $data)->assertSuccessful();
        $this->postJson('/comments', $data)
            ->assertTooManyRequests()
            ->assertJsonPath('content', 'Too many comment posts. Please try again in 60 seconds.');
        $this->assertDatabaseCount('comments', 2);
    }

    public function test_guests_can_post_when_the_contact_email_has_pending_comments(): void
    {
        $author = [
            'author_name' => 'Test User',
            'author_email' => 'test@example.org',
            'author_url' => 'http://example.org',
        ];

        config(['comments.max_unapproved' => 1]);
        Comment::factory()->create(['author_email' => $author['author_email'], 'status' => Comment::STATUS_PENDING]);

        $this->postJson('/comments', array_merge($author, ['content' => 'foo', 'page_id' => 'bar']))
            ->assertCreated()
            ->assertCookieMissing('comment_author_email');
    }

    public function test_it_can_post_a_new_comment(): void
    {
        $this->actingAs($this->testUser)
            ->postJson('/comments', [
                'content' => 'foo',
                'page_id' => 'bar',
                'author_name' => 'Ignored for members',
            ])
            ->assertCreated()
            ->assertJsonPath('author_name', $this->testUser->name)
            ->assertJsonPath('status', Comment::STATUS_APPROVED);

        $this->assertDatabaseHas('comments', [
            'page_id' => 'bar',
            'user_id' => $this->testUser->id,
            'author_name' => null,
            'status' => Comment::STATUS_APPROVED,
        ]);
    }

    public function test_comments_with_too_many_links_are_held_for_moderation(): void
    {
        $this->app->forgetInstance(Formatter::class);
        config(['comments.max_links' => 2]);

        $this->actingAs($this->testUser)
            ->postJson('/comments', ['content' => 'See http://a.example and http://b.example', 'page_id' => 'bar'])
            ->assertCreated()
            ->assertJsonPath('status', Comment::STATUS_PENDING);
    }

    public function test_comments_from_moderators_are_approved_even_with_many_links(): void
    {
        $this->app->forgetInstance(Formatter::class);
        config(['comments.max_links' => 2]);
        $this->testUser->givePermissionTo(Permission::findOrCreate('manage-comments'));

        $this->actingAs($this->testUser)
            ->postJson('/comments', ['content' => 'See http://a.example and http://b.example', 'page_id' => 'bar'])
            ->assertCreated()
            ->assertJsonPath('status', Comment::STATUS_APPROVED);
    }

    public function test_it_rejects_new_comments_from_authors_with_too_many_unapproved_comments(): void
    {
        config(['comments.max_unapproved' => 2]);
        Comment::factory(2)->create(['user_id' => $this->testUser->id, 'status' => Comment::STATUS_PENDING]);

        $this->actingAs($this->testUser)
            ->postJson('/comments', ['content' => 'foo', 'page_id' => 'bar'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['content' => 'You have too many unapproved comments. Wait until some are approved.']);
    }

    public function test_listing_comments_requires_a_page_id_and_hides_author_emails(): void
    {
        $this->getJson('/comments')->assertUnprocessable()->assertJsonValidationErrors('page_id');

        Comment::factory()->create(['page_id' => 'bar', 'author_email' => 'guest@example.org']);

        $this->getJson('/comments?page_id=bar')
            ->assertOk()
            ->assertJsonMissingPath('comments.0.author_email');
    }

    public function test_it_can_edit_the_comment_content(): void
    {
        $comment = Comment::factory()->create(['user_id' => $this->testUser->id]);

        $this->actingAs($this->testUser)
            ->patchJson("/comments/{$comment->id}", [
                'content' => 'updated',
                'status' => Comment::STATUS_PENDING,
                'author_email' => 'foo@bar.com',
            ])
            ->assertSuccessful()
            ->assertJson([
                'content' => 'updated',
                'status' => Comment::STATUS_APPROVED,
            ]);

        $this->assertDatabaseHas('comments', ['id' => $comment->id, 'author_email' => $comment->getRawOriginal('author_email')]);
    }

    public function test_authors_can_not_edit_comments_after_the_edit_window(): void
    {
        config(['comments.edit_window' => 60]);
        $comment = Comment::factory()->create(['user_id' => $this->testUser->id, 'created_at' => now()->subMinutes(2)]);

        $this->actingAs($this->testUser)
            ->patchJson("/comments/{$comment->id}", ['content' => 'updated'])
            ->assertForbidden();
    }

    public function test_users_can_not_edit_other_users_comments(): void
    {
        $comment = Comment::factory()->create(['user_id' => $this->testUser->id]);

        $this->actingAs(User::factory()->create())
            ->patchJson("/comments/{$comment->id}", ['content' => 'updated'])
            ->assertForbidden();
    }

    public function test_it_can_not_edit_a_comment_if_is_a_guest(): void
    {
        $comment = $this->postJson('/comments', [
            'content' => 'foo',
            'page_id' => 'bar',
            'author_name' => 'Test User',
            'author_email' => 'test@example.org',
            'author_url' => 'http://example.org',
        ])->assertCreated()->json();

        $this->patchJson("/comments/{$comment['id']}", ['content' => 'updated'])
            ->assertStatus(401);
    }

    public function test_it_can_delete_a_comment(): void
    {
        $comment = Comment::factory()->create(['user_id' => $this->testUser->id]);

        $this->actingAs($this->testUser)
            ->deleteJson("/comments/{$comment->id}")
            ->assertSuccessful();

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }

    public function test_it_can_report_a_comment(): void
    {
        $comment = Comment::factory()->create(['user_id' => $this->testUser->id]);

        $this->actingAs($this->testUser)
            ->putJson("/comments/{$comment->id}/report")
            ->assertSuccessful();

        $this->assertDatabaseHas('comment_reports', [
            'comment_id' => $comment->id,
            'user_id' => $this->testUser->id,
        ]);
    }

    public function test_it_can_mark_the_comment_as_pending_if_it_has_to_many_reports(): void
    {
        config(['comments.max_reports' => 1]);

        $comment = Comment::factory()->create(['user_id' => $this->testUser->id]);

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

    public function test_it_can_render_comment_preview(): void
    {
        $this->app->forgetInstance(Formatter::class);

        $this->postJson('/comments/preview', [
            'content' => 'foo [bar](http://example.org)',
        ])
            ->assertSuccessful()
            ->assertContent('<p>foo <a href="http://example.org">bar</a></p>');
    }
}
