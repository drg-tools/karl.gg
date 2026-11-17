<?php

namespace Tests\Feature;

use App\Character;
use App\Filament\Resources\CharacterResource;
use App\Filament\Resources\CharacterResource\Pages\ManageCharacter;
use App\Filament\Resources\PostResource;
use App\Filament\Resources\UserResource;
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

    public function testAuthorsCanManagePostsButCannotReachStatsOrUsers()
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

    public function testResourcePermissionAloneDoesNotGrantPanelAccess()
    {
        $this->manager(['manage-stats']);
        $this->get('/admin')->assertForbidden();
        $this->assertFalse(CharacterResource::canCreate());
    }

    public function testAllAdminScreensRenderForAnAuthorizedManager()
    {
        $this->manager(['view-admin', 'manage-stats', 'manage-posts', 'manage-users']);
        $this->get('/admin')->assertOk();
        foreach (Filament::getPanel('admin')->getResources() as $resource) {
            $this->get($resource::getUrl())->assertOk();
        }
    }

    public function testStatsManagerCanCreateUpdateAndDeleteACharacter()
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
