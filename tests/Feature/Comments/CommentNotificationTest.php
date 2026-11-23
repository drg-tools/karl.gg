<?php

namespace Tests\Feature\Comments;

use App\Comments\Formatter;
use App\Models\Character;
use App\Models\Comment;
use App\Models\Loadout;
use App\Models\Patch;
use App\Models\User;
use App\Notifications\NotificationType;

class CommentNotificationTest extends CommentsTestCase
{
    public function test_commenting_on_a_build_notifies_its_owner(): void
    {
        $owner = User::factory()->create();
        $loadout = $this->loadoutOwnedBy($owner, 'Bulk Bonanza');

        $this->actingAs($this->testUser)
            ->postJson('/comments', ['content' => 'Great build', 'page_id' => 'loadout-'.$loadout->id])
            ->assertCreated();

        $notification = $owner->notifications()->sole();
        $comment = Comment::query()->sole();
        $this->assertSame(NotificationType::BuildComment->value, $notification->type);
        $this->assertSame([
            'comment_id' => $comment->id,
            'loadout_id' => $loadout->id,
            'loadout_name' => 'Bulk Bonanza',
            'author_name' => $this->testUser->name,
            'excerpt' => 'Great build',
            'url' => route('preview.show', $loadout->id).'#!comment='.$comment->id,
        ], $notification->data);
    }

    public function test_guest_comments_notify_the_owner_with_the_guest_name(): void
    {
        $owner = User::factory()->create();
        $loadout = $this->loadoutOwnedBy($owner);

        $this->postJson('/comments', [
            'content' => 'Nice',
            'page_id' => 'loadout-'.$loadout->id,
            'author_name' => 'Guest Dwarf',
            'author_email' => 'guest@example.com',
        ])->assertCreated();

        $this->assertSame('Guest Dwarf', $owner->notifications()->sole()->data['author_name']);
    }

    public function test_owners_are_not_notified_about_their_own_comments(): void
    {
        $loadout = $this->loadoutOwnedBy($this->testUser);

        $this->actingAs($this->testUser)
            ->postJson('/comments', ['content' => 'My notes', 'page_id' => 'loadout-'.$loadout->id])
            ->assertCreated();

        $this->assertDatabaseCount('notifications', 0);
    }

    public function test_replying_notifies_the_parent_author_and_the_build_owner(): void
    {
        $owner = User::factory()->create();
        $parentAuthor = User::factory()->create();
        $loadout = $this->loadoutOwnedBy($owner);
        $parent = Comment::factory()->create(['page_id' => 'loadout-'.$loadout->id, 'user_id' => $parentAuthor->id]);

        $this->actingAs($this->testUser)
            ->postJson('/comments', ['content' => 'Agreed', 'page_id' => 'loadout-'.$loadout->id, 'parent_id' => $parent->id])
            ->assertCreated();

        $this->assertSame(NotificationType::CommentReply->value, $parentAuthor->notifications()->sole()->type);
        $this->assertSame(NotificationType::BuildComment->value, $owner->notifications()->sole()->type);
    }

    public function test_a_reply_to_the_owner_sends_them_one_reply_notification(): void
    {
        $owner = User::factory()->create();
        $loadout = $this->loadoutOwnedBy($owner);
        $parent = Comment::factory()->create(['page_id' => 'loadout-'.$loadout->id, 'user_id' => $owner->id]);

        $this->actingAs($this->testUser)
            ->postJson('/comments', ['content' => 'Agreed', 'page_id' => 'loadout-'.$loadout->id, 'parent_id' => $parent->id])
            ->assertCreated();

        $this->assertSame(NotificationType::CommentReply->value, $owner->notifications()->sole()->type);
    }

    public function test_an_owner_who_turned_off_replies_still_hears_about_comments_on_their_build(): void
    {
        $owner = User::factory()->create(['notification_preferences' => [NotificationType::CommentReply->value => false]]);
        $loadout = $this->loadoutOwnedBy($owner);
        $parent = Comment::factory()->create(['page_id' => 'loadout-'.$loadout->id, 'user_id' => $owner->id]);

        $this->actingAs($this->testUser)
            ->postJson('/comments', ['content' => 'Agreed', 'page_id' => 'loadout-'.$loadout->id, 'parent_id' => $parent->id])
            ->assertCreated();

        $this->assertSame(NotificationType::BuildComment->value, $owner->notifications()->sole()->type);
    }

    public function test_owners_who_turned_off_build_comments_are_not_notified(): void
    {
        $owner = User::factory()->create(['notification_preferences' => [NotificationType::BuildComment->value => false]]);
        $loadout = $this->loadoutOwnedBy($owner);

        $this->actingAs($this->testUser)
            ->postJson('/comments', ['content' => 'Great build', 'page_id' => 'loadout-'.$loadout->id])
            ->assertCreated();

        $this->assertDatabaseCount('notifications', 0);
    }

    public function test_comments_held_for_moderation_do_not_notify(): void
    {
        $this->app->forgetInstance(Formatter::class);
        config(['comments.max_links' => 1]);
        $owner = User::factory()->create();
        $loadout = $this->loadoutOwnedBy($owner);

        $this->actingAs($this->testUser)
            ->postJson('/comments', ['content' => 'See http://a.example', 'page_id' => 'loadout-'.$loadout->id])
            ->assertCreated()
            ->assertJsonPath('status', Comment::STATUS_PENDING);

        $this->assertDatabaseCount('notifications', 0);
    }

    private function loadoutOwnedBy(User $owner, string $name = 'Test build'): Loadout
    {
        Patch::factory()->create(['launched_at' => now()->subDay()]);

        return Loadout::factory()->create([
            'name' => $name,
            'user_id' => $owner->id,
            'character_id' => Character::factory()->create()->id,
        ]);
    }
}
