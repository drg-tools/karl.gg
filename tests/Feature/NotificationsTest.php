<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\NotificationType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_sent_to_login(): void
    {
        $this->get(route('notifications.index'))->assertRedirect(route('login'));
    }

    public function test_the_inbox_lists_only_the_viewers_notifications_with_names_escaped(): void
    {
        $user = User::factory()->create();
        $this->notificationFor($user, ['author_name' => '<script>alert(1)</script>', 'loadout_name' => 'Bulk Bonanza']);
        $this->notificationFor(User::factory()->create(), ['loadout_name' => 'Someone Else Build']);

        $this->actingAs($user)
            ->get(route('notifications.index'))
            ->assertOk()
            ->assertSee('Bulk Bonanza')
            ->assertSee('&lt;script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertDontSee('Someone Else Build');
    }

    public function test_the_nav_shows_the_unread_count(): void
    {
        $user = User::factory()->create();
        $this->notificationFor($user);
        $this->notificationFor($user);
        $this->notificationFor($user, read: true);

        $this->actingAs($user)
            ->get(route('notifications.index'))
            ->assertSee('2 unread notifications');
    }

    public function test_opening_a_notification_marks_it_read_and_goes_to_the_comment(): void
    {
        $user = User::factory()->create();
        $notification = $this->notificationFor($user, ['url' => route('preview.show', 7).'#!comment=3']);

        $this->actingAs($user)
            ->get(route('notifications.show', $notification->id))
            ->assertRedirect(route('preview.show', 7).'#!comment=3');

        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_users_cannot_open_another_users_notification(): void
    {
        $notification = $this->notificationFor(User::factory()->create());

        $this->actingAs(User::factory()->create())
            ->get(route('notifications.show', $notification->id))
            ->assertNotFound();

        $this->assertNull($notification->fresh()->read_at);
    }

    public function test_mark_all_read_only_touches_the_viewers_notifications(): void
    {
        $user = User::factory()->create();
        $mine = $this->notificationFor($user);
        $theirs = $this->notificationFor(User::factory()->create());

        $this->actingAs($user)
            ->post(route('notifications.read-all'))
            ->assertRedirect(route('notifications.index'));

        $this->assertNotNull($mine->fresh()->read_at);
        $this->assertNull($theirs->fresh()->read_at);
    }

    public function test_settings_save_checked_types_and_turn_off_unchecked_ones(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put(route('account.notifications.update'), ['notifications' => [NotificationType::CommentReply->value => '1']])
            ->assertRedirect(route('account.settings'))
            ->assertSessionHas('status', 'notifications-updated');

        $user->refresh();
        $this->assertFalse($user->wantsNotification(NotificationType::BuildComment));
        $this->assertTrue($user->wantsNotification(NotificationType::CommentReply));
    }

    public function test_settings_ignore_unknown_notification_types(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put(route('account.notifications.update'), ['notifications' => ['not_a_type' => '1']])
            ->assertRedirect(route('account.settings'));

        $this->assertArrayNotHasKey('not_a_type', $user->refresh()->notification_preferences);
    }

    public function test_new_users_receive_every_notification_type(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('account.settings'))
            ->assertOk()
            ->assertSee('name="notifications['.NotificationType::BuildComment->value.']" value="1" checked', false)
            ->assertSee('name="notifications['.NotificationType::CommentReply->value.']" value="1" checked', false);
    }

    public function test_deleting_an_account_removes_its_notifications(): void
    {
        $user = User::factory()->create(['password' => Hash::make('secret-password')]);
        $notification = $this->notificationFor($user);

        $this->actingAs($user)->delete(route('account.destroy'), ['delete_password' => 'secret-password']);

        $this->assertModelMissing($notification);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function notificationFor(User $user, array $data = [], bool $read = false): DatabaseNotification
    {
        return $user->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => NotificationType::BuildComment->value,
            'data' => $data + [
                'comment_id' => 1,
                'loadout_id' => 1,
                'loadout_name' => 'Test build',
                'author_name' => 'Commenter',
                'excerpt' => 'Nice build',
                'url' => route('preview.show', 1).'#!comment=1',
            ],
            'read_at' => $read ? now() : null,
        ]);
    }
}
