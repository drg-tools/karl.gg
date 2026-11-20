<?php

namespace Tests\Feature;

use App\Character;
use App\Comment;
use App\Loadout;
use App\Patch;
use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_sent_to_login(): void
    {
        $this->get(route('account.settings'))->assertRedirect(route('login'));
    }

    public function test_settings_page_is_not_indexed(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('account.settings'))
            ->assertOk()
            ->assertSee('<title>Karl: Born Ready - Account settings</title>', false)
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    }

    public function test_profile_can_be_updated_without_a_password(): void
    {
        $user = User::factory()->create(['name' => 'Old Name', 'email' => 'old@example.com']);

        $this->actingAs($user)
            ->put(route('account.profile.update'), ['name' => 'New Name', 'email' => 'new@example.com'])
            ->assertRedirect(route('account.settings'))
            ->assertSessionHas('status', 'profile-updated');

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'New Name', 'email' => 'new@example.com']);
    }

    public function test_email_must_not_belong_to_another_user(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);
        $user = User::factory()->create(['email' => 'mine@example.com']);

        $this->actingAs($user)
            ->from(route('account.settings'))
            ->put(route('account.profile.update'), ['name' => $user->name, 'email' => 'taken@example.com'])
            ->assertRedirect(route('account.settings'))
            ->assertSessionHasErrorsIn('updateProfile', ['email' => 'The email has already been taken.']);

        $this->assertSame('mine@example.com', $user->fresh()->email);
    }

    public function test_password_change_requires_the_current_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('old-password')]);

        $this->actingAs($user)
            ->from(route('account.settings'))
            ->put(route('account.password.update'), [
                'current_password' => 'wrong-password',
                'password' => 'brand-new-password',
                'password_confirmation' => 'brand-new-password',
            ])
            ->assertRedirect(route('account.settings'))
            ->assertSessionHasErrorsIn('updatePassword', ['current_password' => 'The password is incorrect.']);

        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
    }

    public function test_password_can_be_changed(): void
    {
        $user = User::factory()->create(['password' => Hash::make('old-password')]);

        $this->actingAs($user)
            ->put(route('account.password.update'), [
                'current_password' => 'old-password',
                'password' => 'brand-new-password',
                'password_confirmation' => 'brand-new-password',
            ])
            ->assertRedirect(route('account.settings'))
            ->assertSessionHas('status', 'password-updated');

        $this->assertTrue(Hash::check('brand-new-password', $user->fresh()->password));
    }

    public function test_account_deletion_requires_the_current_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('secret-password')]);

        $this->actingAs($user)
            ->from(route('account.settings'))
            ->delete(route('account.destroy'), ['delete_password' => 'wrong-password'])
            ->assertRedirect(route('account.settings'))
            ->assertSessionHasErrorsIn('deleteAccount', ['delete_password' => 'The password is incorrect.']);

        $this->assertModelExists($user);
        $this->assertAuthenticatedAs($user);
    }

    public function test_blog_authors_cannot_delete_their_own_account(): void
    {
        $author = User::factory()->create(['password' => Hash::make('secret-password')]);
        Post::factory()->create(['user_id' => $author->id]);

        $this->actingAs($author)
            ->from(route('account.settings'))
            ->delete(route('account.destroy'), ['delete_password' => 'secret-password'])
            ->assertRedirect(route('account.settings'))
            ->assertSessionHasErrorsIn('deleteAccount', 'delete_password');

        $this->assertModelExists($author);
    }

    public function test_deleting_an_account_removes_personal_data_and_keeps_content_anonymously(): void
    {
        $user = User::factory()->create(['password' => Hash::make('secret-password')]);
        $otherUser = User::factory()->create();
        Patch::factory()->create(['launched_at' => now()->subDay()]);
        $loadout = Loadout::factory()->create(['user_id' => $user->id, 'character_id' => Character::factory()->create()->id]);
        $otherLoadout = Loadout::factory()->create(['user_id' => $otherUser->id, 'character_id' => $loadout->character_id]);
        $user->favorite($otherLoadout);
        $user->upVote($otherLoadout);
        $otherUser->upVote($otherLoadout);
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'author_email' => $user->email,
            'author_ip' => '203.0.113.7',
            'user_agent' => 'Test Browser',
        ]);

        $this->actingAs($user)
            ->delete(route('account.destroy'), ['delete_password' => 'secret-password'])
            ->assertRedirect('/')
            ->assertSessionHas('status', 'account-deleted');

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseHas('loadouts', ['id' => $loadout->id, 'user_id' => null]);
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'user_id' => null,
            'author_name' => 'Anonymous',
            'author_email' => null,
            'author_ip' => null,
            'user_agent' => null,
        ]);
        $this->assertSame(0, $otherLoadout->favorites()->count());
        $this->assertSame(1, $otherLoadout->votes()->count());
        $this->assertModelExists($otherUser);
    }
}
