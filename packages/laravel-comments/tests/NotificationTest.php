<?php

namespace Hazzard\Comments\Tests;

use Hazzard\Comments\Comment;
use Hazzard\Comments\Notifications\CommentWasPosted;
use Hazzard\Comments\Notifications\Notifiable;
use Hazzard\Comments\Notifications\ReplyWasPosted;
use Illuminate\Support\Facades\Notification;

class NotificationTest extends TestCase
{
    /** @test */
    public function it_can_notify_the_admin()
    {
        Notification::fake();

        config(['comments.admin_notification' => 'admin@example.app']);

        $this->actingAs($this->testUser)
            ->postJson('/comments', [
                'content' => 'foo',
                'page_id' => 'bar',
            ])
            ->assertSuccessful();

        Notification::assertSentTo(
            new Notifiable('admin@example.app'),
            CommentWasPosted::class
        );
    }

    /** @test */
    public function it_does_not_notify_the_admin_if_the_admin_posts()
    {
        Notification::fake();

        config(['comments.admin_notification' => $this->testUser->email]);

        $this->actingAs($this->testUser)
            ->postJson('/comments', [
                'content' => 'foo',
                'page_id' => 'bar',
            ])
            ->assertSuccessful();

        Notification::assertNotSentTo(
            new Notifiable($this->testUser->email),
            CommentWasPosted::class
        );
    }

    /** @test */
    public function it_can_notify_the_comment_parent_author()
    {
        Notification::fake();

        config(['comments.reply_notification' => true]);

        $comment = factory(Comment::class)->create(['user_id' => $this->testUser->id]);

        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->postJson('/comments', [
                'content' => 'foo',
                'page_id' => 'bar',
                'parent_id' => $comment->id,
            ])
            ->assertSuccessful();

        Notification::assertSentTo(
            new Notifiable($this->testUser->email),
            ReplyWasPosted::class
        );
    }

    /** @test */
    public function it_does_not_notift_the_parent_comment_author_if_is_same_author()
    {
        Notification::fake();

        config(['comments.reply_notification' => true]);

        $comment = factory(Comment::class)->create(['user_id' => $this->testUser->id]);

        $this->actingAs($this->testUser)
            ->postJson('/comments', [
                'content' => 'foo',
                'page_id' => 'bar',
                'parent_id' => $comment->id,
            ])
            ->assertSuccessful();

        Notification::assertNotSentTo(
            new Notifiable($this->testUser->email),
            ReplyWasPosted::class
        );
    }

    /** @test */
    public function it_can_only_notify_the_admin_if_the_parent_comment_author_is_the_admin()
    {
        Notification::fake();

        config(['comments.reply_notification' => true]);
        config(['comments.admin_notification' => $this->testUser->email]);

        $comment = factory(Comment::class)->create(['user_id' => $this->testUser->id]);

        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->postJson('/comments', [
                'content' => 'foo',
                'page_id' => 'bar',
                'parent_id' => $comment->id,
            ])
            ->assertSuccessful();

        Notification::assertNotSentTo(
            new Notifiable($this->testUser->email),
            ReplyWasPosted::class
        );

        Notification::assertSentTo(
            new Notifiable($this->testUser->email),
            CommentWasPosted::class
        );
    }
}
