<?php

namespace Tests\Feature;

use App\Notifications\DatabaseNotification;
use App\Notifications\NotificationType;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class PruneNotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_pruning_removes_long_read_and_very_old_notifications(): void
    {
        $this->freezeTime();
        $user = User::factory()->create();
        $readLongAgo = $this->notificationFor($user, now()->subDays(40), readAt: now()->subDays(31));
        $readRecently = $this->notificationFor($user, now()->subDays(40), readAt: now()->subDays(29));
        $unreadButAncient = $this->notificationFor($user, now()->subDays(91));
        $unreadAndRecent = $this->notificationFor($user, now()->subDays(89));

        $this->artisan('model:prune', ['--model' => [DatabaseNotification::class]])->assertSuccessful();

        $this->assertModelMissing($readLongAgo);
        $this->assertModelMissing($unreadButAncient);
        $this->assertModelExists($readRecently);
        $this->assertModelExists($unreadAndRecent);
    }

    public function test_user_notifications_use_the_prunable_model(): void
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(DatabaseNotification::class, $this->notificationFor($user, now()));
        $this->assertInstanceOf(DatabaseNotification::class, $user->unreadNotifications()->sole());
    }

    private function notificationFor(User $user, Carbon $createdAt, ?Carbon $readAt = null): DatabaseNotification
    {
        $notification = $user->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => NotificationType::BuildComment->value,
            'data' => ['url' => '/'],
            'read_at' => $readAt,
        ]);
        $notification->forceFill(['created_at' => $createdAt, 'updated_at' => $createdAt])->saveQuietly();

        return $notification;
    }
}
