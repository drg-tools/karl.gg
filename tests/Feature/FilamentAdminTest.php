<?php

namespace Tests\Feature;

use App\Character;
use App\Comment;
use App\Filament\Resources\CharacterResource;
use App\Filament\Resources\CharacterResource\Pages\ManageCharacter;
use App\Filament\Resources\CommentResource;
use App\Filament\Resources\CommentResource\Pages\ManageComment;
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
        foreach (['view-admin', 'manage-stats', 'manage-posts', 'manage-users', 'manage-comments'] as $name) {
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

    public function test_loadouts_admin_links_to_the_public_preview(): void
    {
        $this->manager(['view-admin']);
        $loadout = $this->loadout();

        $this->get(LoadoutResource::getUrl())->assertOk()
            ->assertSee('View loadout')
            ->assertSee('href="'.url('/preview/'.$loadout->id).'"', false);
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

    public function test_loadouts_admin_can_show_every_optional_column(): void
    {
        $this->manager(['view-admin']);
        $loadout = $this->loadout();

        Livewire::test(ManageLoadout::class)
            ->toggleAllTableColumns()
            ->assertTableColumnVisible('created_at')
            ->assertTableColumnVisible('patch.label')
            ->assertCanSeeTableRecords([$loadout]);
    }

    public function test_loadouts_admin_filters_by_patch_and_local_created_day(): void
    {
        $this->manager(['view-admin']);
        $older = $this->loadout();
        $newer = Loadout::factory()
            ->for(User::factory(), 'creator')
            ->for($older->character)
            ->for(Patch::factory()->create(['launched_at' => now()]))
            ->create();
        $older->forceFill(['created_at' => '2026-11-02 03:00:00'])->saveQuietly();
        $newer->forceFill(['created_at' => '2026-11-03 03:00:00'])->saveQuietly();

        Livewire::test(ManageLoadout::class)
            ->filterTable('patch', $newer->patch_id)
            ->assertCanSeeTableRecords([$newer])
            ->assertCanNotSeeTableRecords([$older]);

        Livewire::test(ManageLoadout::class)
            ->filterTable('created_at', ['from' => '2026-11-01', 'until' => '2026-11-01'])
            ->assertCanSeeTableRecords([$older])
            ->assertCanNotSeeTableRecords([$newer]);
    }

    public function test_admin_dates_display_in_eastern_time(): void
    {
        $this->manager(['view-admin']);
        $loadout = $this->loadout();
        $loadout->forceFill(['updated_at' => '2026-11-02 03:00:00'])->saveQuietly();

        $this->get(LoadoutResource::getUrl())
            ->assertOk()
            ->assertSee('Nov 1, 2026 10:00 PM EST');
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

    public function test_comment_moderators_can_approve_and_filter_comments_by_status(): void
    {
        $this->manager(['view-admin', 'manage-comments']);
        $reporter = User::factory()->create();
        $pending = Comment::factory()->create(['page_id' => 'loadout-7', 'status' => Comment::STATUS_PENDING]);
        $approved = Comment::factory()->create(['page_id' => 'loadout-7']);

        $pending->reports()->create(['user_id' => $reporter->id]);

        $this->get(CommentResource::getUrl())->assertOk()
            ->assertSee('href="'.url('/preview/7').'#!comment='.$pending->id.'"', false);

        Livewire::test(ManageComment::class)
            ->set('activeTab', Comment::STATUS_PENDING)
            ->assertCanSeeTableRecords([$pending])
            ->assertCanNotSeeTableRecords([$approved])
            ->callTableAction('approve', $pending);

        $this->assertSame(Comment::STATUS_APPROVED, $pending->fresh()->status);
        $this->assertDatabaseMissing('comment_reports', ['comment_id' => $pending->id]);
    }

    public function test_comment_moderators_can_clear_reports_with_bulk_approval(): void
    {
        $this->manager(['view-admin', 'manage-comments']);
        $reporter = User::factory()->create();
        $reported = Comment::factory(2)->create(['page_id' => 'loadout-7', 'status' => Comment::STATUS_PENDING]);
        $reported->each(fn (Comment $comment) => $comment->reports()->create(['user_id' => $reporter->id]));

        Livewire::test(ManageComment::class)->callTableBulkAction(Comment::STATUS_APPROVED, $reported);

        $this->assertSame([Comment::STATUS_APPROVED, Comment::STATUS_APPROVED], $reported->map->fresh()->pluck('status')->all());
        $this->assertDatabaseCount('comment_reports', 0);
    }

    public function test_comment_moderators_can_bulk_mark_comments_as_spam(): void
    {
        $this->manager(['view-admin', 'manage-comments']);
        $spam = Comment::factory(2)->create(['page_id' => 'loadout-7']);
        $kept = Comment::factory()->create(['page_id' => 'loadout-7']);

        Livewire::test(ManageComment::class)->callTableBulkAction(Comment::STATUS_SPAM, $spam);

        $this->assertSame([Comment::STATUS_SPAM, Comment::STATUS_SPAM], $spam->map->fresh()->pluck('status')->all());
        $this->assertSame(Comment::STATUS_APPROVED, $kept->fresh()->status);
    }

    public function test_comment_moderators_can_edit_comment_content(): void
    {
        $this->manager(['view-admin', 'manage-comments']);
        $comment = Comment::factory()->create(['page_id' => 'loadout-7', 'content' => 'Original']);

        Livewire::test(ManageComment::class)
            ->callTableAction('edit', $comment, data: ['content' => 'Edited **text**', 'status' => Comment::STATUS_PENDING])
            ->assertHasNoTableActionErrors();

        $comment->refresh();
        $this->assertSame('Edited **text**', $comment->content);
        $this->assertSame(Comment::STATUS_PENDING, $comment->status);
    }

    public function test_approving_through_the_comment_editor_clears_reports(): void
    {
        $this->manager(['view-admin', 'manage-comments']);
        $reporter = User::factory()->create();
        $comment = Comment::factory()->create(['page_id' => 'loadout-7', 'status' => Comment::STATUS_PENDING]);
        $comment->reports()->create(['user_id' => $reporter->id]);

        Livewire::test(ManageComment::class)
            ->callTableAction('edit', $comment, data: ['status' => Comment::STATUS_APPROVED])
            ->assertHasNoTableActionErrors();

        $this->assertSame(Comment::STATUS_APPROVED, $comment->fresh()->status);
        $this->assertDatabaseMissing('comment_reports', ['comment_id' => $comment->id]);
    }

    public function test_admins_without_comments_permission_can_not_manage_comments(): void
    {
        $this->manager(['view-admin']);

        $this->get(CommentResource::getUrl())->assertForbidden();
    }

    public function test_all_admin_screens_render_for_an_authorized_manager()
    {
        $this->manager(['view-admin', 'manage-stats', 'manage-posts', 'manage-users', 'manage-comments']);
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
