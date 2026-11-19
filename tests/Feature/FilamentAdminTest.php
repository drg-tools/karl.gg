<?php

namespace Tests\Feature;

use App\Character;
use App\Filament\Resources\CharacterResource;
use App\Filament\Resources\CharacterResource\Pages\ManageCharacter;
use App\Filament\Resources\LoadoutResource;
use App\Filament\Resources\LoadoutResource\Pages\ManageLoadout;
use App\Filament\Resources\PostResource;
use App\Filament\Resources\UserResource;
use App\Loadout;
use App\Patch;
use App\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class FilamentAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        foreach (['view-admin', 'manage-stats', 'manage-posts', 'manage-users'] as $name) {
            Permission::findOrCreate($name, 'backpack');
        }
    }

    private function manager(array $permissions): User
    {
        $user = User::factory()->create();
        $user->givePermissionTo($permissions);
        $this->actingAs($user, 'backpack');

        return $user;
    }

    public function test_authors_can_manage_posts_but_cannot_reach_stats_or_users()
    {
        $this->manager(['view-admin', 'manage-posts']);
        $this->assertTrue(PostResource::canCreate());
        $this->assertFalse(CharacterResource::canCreate());
        $this->assertFalse(UserResource::canCreate());
        $this->get(PostResource::getUrl())->assertOk();
        $this->get(CharacterResource::getUrl())->assertForbidden();
        $this->get(UserResource::getUrl())->assertForbidden();
        Livewire::test(ManageCharacter::class)->assertForbidden();
    }

    public function test_resource_permission_alone_does_not_grant_panel_access()
    {
        $this->manager(['manage-stats']);
        $this->get('/admin')->assertForbidden();
        $this->assertFalse(CharacterResource::canCreate());
        $this->get(LoadoutResource::getUrl())->assertForbidden();
        Livewire::test(ManageLoadout::class)->assertForbidden();
    }

    public function test_panel_users_can_edit_and_delete_other_users_builds_in_admin(): void
    {
        $this->manager(['view-admin']);
        $loadout = $this->loadout();

        $this->get(LoadoutResource::getUrl())->assertOk();

        Livewire::test(ManageLoadout::class)
            ->callTableAction('edit', $loadout, data: ['name' => 'Updated build'])
            ->assertHasNoTableActionErrors();
        $this->assertDatabaseHas('loadouts', [
            'id' => $loadout->id,
            'name' => 'Updated build',
            'user_id' => $loadout->user_id,
        ]);

        Livewire::test(ManageLoadout::class)->callTableAction('delete', $loadout);
        $this->assertModelMissing($loadout);
    }

    public function test_admin_access_does_not_grant_public_build_update_or_delete(): void
    {
        $this->manager(['view-admin']);
        $loadout = $this->loadout();

        $this->putJson('/build/'.$loadout->id, [
            'name' => 'Updated build',
            'character_id' => $loadout->character_id,
            'mods' => [],
            'overclocks' => [],
            'equipment_mods' => [],
        ])->assertForbidden();
        $this->delete('/build/delete/'.$loadout->id)->assertForbidden();
        $this->assertDatabaseHas('loadouts', ['id' => $loadout->id, 'name' => $loadout->name]);
    }

    public function test_panel_users_can_hide_and_restore_another_users_build(): void
    {
        $this->manager(['view-admin']);
        $loadout = $this->loadout();
        Patch::factory()->create(['launched_at' => now()]);

        Livewire::test(ManageLoadout::class)
            ->callTableAction('hide', $loadout)
            ->assertHasNoTableActionErrors()
            ->assertCanSeeTableRecords([$loadout]);

        $this->assertDatabaseHas('loadouts', [
            'id' => $loadout->id,
            'is_hidden' => true,
            'patch_id' => $loadout->patch_id,
            'updated_at' => $loadout->getRawOriginal('updated_at'),
        ]);
        $this->get('/browse')->assertOk()->assertDontSee($loadout->name);

        Livewire::test(ManageLoadout::class)
            ->callTableAction('restoreListing', $loadout)
            ->assertHasNoTableActionErrors();

        $this->assertDatabaseHas('loadouts', ['id' => $loadout->id, 'is_hidden' => false]);
        $this->get('/browse')->assertOk()->assertSee($loadout->name);
    }

    private function loadout(): Loadout
    {
        $patch = Patch::factory()->create(['launched_at' => now()->subDay()]);

        return Loadout::factory()
            ->for(User::factory(), 'creator')
            ->for(Character::factory())
            ->for($patch)
            ->create();
    }

    public function test_comments_managers_see_the_comment_dashboard_link(): void
    {
        Permission::findOrCreate('manage-comments', 'backpack');
        $this->manager(['view-admin', 'manage-comments']);

        $this->get('/admin')->assertOk()
            ->assertSee('Comment Dashboard')
            ->assertSee(route('comments.dashboard'));
    }

    public function test_admins_without_comments_permission_do_not_see_the_comment_dashboard_link(): void
    {
        $this->manager(['view-admin']);

        $this->get('/admin')->assertOk()
            ->assertDontSee('Comment Dashboard');
    }

    public function test_all_admin_screens_render_for_an_authorized_manager()
    {
        $this->manager(['view-admin', 'manage-stats', 'manage-posts', 'manage-users']);
        $this->get('/admin')->assertOk();
        foreach (Filament::getPanel('admin')->getResources() as $resource) {
            $this->get($resource::getUrl())->assertOk();
        }
    }

    public function test_stats_manager_can_create_update_and_delete_a_character()
    {
        $this->manager(['view-admin', 'manage-stats']);
        Livewire::test(ManageCharacter::class)
            ->callAction('create', data: ['name' => 'Test character', 'image' => 'test'])
            ->assertHasNoActionErrors();
        $character = Character::where('name', 'Test character')->firstOrFail();

        Livewire::test(ManageCharacter::class)
            ->callTableAction('edit', $character, data: ['name' => 'Updated character', 'image' => 'test'])
            ->assertHasNoTableActionErrors();
        $this->assertDatabaseHas('characters', ['id' => $character->id, 'name' => 'Updated character']);
        Livewire::test(ManageCharacter::class)->callTableAction('delete', $character);
        $this->assertDatabaseMissing('characters', ['id' => $character->id]);
    }
}
