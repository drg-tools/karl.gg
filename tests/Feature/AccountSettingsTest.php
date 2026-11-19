<?php

namespace Tests\Feature;

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
}
