<?php

namespace Tests\Feature;

use App\Models\Character;
use App\Models\Loadout;
use App\Models\Patch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_has_seo_metadata_for_the_miner(): void
    {
        $user = User::factory()->create(['name' => 'Molly']);
        $loadout = Loadout::factory()->create(['user_id' => $user->id, 'character_id' => Character::factory()->create()->id]);
        User::factory()->create()->upVote($loadout);

        $this->get(route('user.profile', $user->id))
            ->assertOk()
            ->assertSee("<title>Karl: Born Ready - Molly's Deep Rock Galactic Builds</title>", false)
            ->assertSee('<meta name="description" content="Molly has shared 1 Deep Rock Galactic loadout on Karl.gg, earning 1 salute.">', false)
            ->assertSee('<link rel="canonical" href="'.route('user.profile', $user->id).'">', false)
            ->assertSee('<meta property="og:type" content="profile">', false)
            ->assertSee('<meta name="twitter:site" content="@DrgTools">', false)
            ->assertDontSee('noindex', false);
    }

    public function test_profile_without_loadouts_is_not_indexed(): void
    {
        $user = User::factory()->create(['name' => 'Molly']);

        $this->get(route('user.profile', $user->id))
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex, follow">', false)
            ->assertSee("Molly hasn't shared any loadouts.", false);
    }

    public function test_missing_user_returns_not_found(): void
    {
        $this->get('/profile/999')->assertNotFound();
    }

    public function test_profile_summarises_the_miners_loadouts_and_salutes(): void
    {
        $user = User::factory()->create();
        $character = Character::factory()->create();
        Patch::factory()->create(['launched_at' => Carbon::parse('2026-01-01')]);
        $currentPatch = Patch::factory()->create(['launched_at' => Carbon::parse('2026-06-01')]);
        $popular = Loadout::factory()->create(['user_id' => $user->id, 'character_id' => $character->id, 'name' => 'Popular Build', 'patch_id' => $currentPatch->id]);
        $quiet = Loadout::factory()->create(['user_id' => $user->id, 'character_id' => $character->id, 'name' => 'Quiet Build']);
        Loadout::factory()->count(2)->create(['user_id' => $user->id, 'character_id' => $character->id]);
        Loadout::factory()->create(['user_id' => User::factory()->create()->id, 'character_id' => $character->id, 'name' => 'Someone Else Build']);
        [$voterA, $voterB] = User::factory()->count(2)->create();
        $voterA->upVote($popular);
        $voterB->upVote($popular);
        $voterA->upVote($quiet);

        $response = $this->get(route('user.profile', $user->id));

        $response->assertOk()
            ->assertSeeInOrder(['Loadouts', '4', 'Salutes', '3', 'Current patch', '1'])
            ->assertSeeInOrder(['Top builds', 'Popular Build', 'Quiet Build', 'Builds by'])
            ->assertDontSee('Someone Else Build');
    }

    public function test_profile_query_count_does_not_grow_with_loadouts(): void
    {
        $user = User::factory()->create();
        $character = Character::factory()->create();
        $voter = User::factory()->create();
        $createVotedLoadouts = fn (int $count) => Loadout::factory()->count($count)
            ->create(['user_id' => $user->id, 'character_id' => $character->id])
            ->each(fn (Loadout $loadout) => $voter->upVote($loadout));
        $createVotedLoadouts(4);
        $queriesFor = function () use ($user): int {
            DB::flushQueryLog();
            DB::enableQueryLog();
            $this->get(route('user.profile', $user->id))->assertOk();

            return count(DB::getQueryLog());
        };
        $baseline = $queriesFor();

        $createVotedLoadouts(12);

        $this->assertSame($baseline, $queriesFor());
    }

    public function test_profile_shows_the_class_the_miner_builds_most(): void
    {
        $user = User::factory()->create(['name' => 'Molly']);
        $scout = Character::factory()->create(['name' => 'Scout']);
        $driller = Character::factory()->create(['name' => 'Driller']);
        Loadout::factory()->create(['user_id' => $user->id, 'character_id' => $scout->id]);
        Loadout::factory()->count(2)->create(['user_id' => $user->id, 'character_id' => $driller->id]);

        $response = $this->get(route('user.profile', $user->id));

        $response->assertOk()
            ->assertSeeInOrder(['Main class', 'Driller', 'Builds by Molly']);
    }

    public function test_profile_without_loadouts_has_no_main_class(): void
    {
        $user = User::factory()->create();

        $response = $this->get(route('user.profile', $user->id));

        $response->assertOk()
            ->assertSeeInOrder(['Main class', '—']);
    }

    public function test_loadouts_can_be_filtered_by_class_and_sorted(): void
    {
        $user = User::factory()->create();
        $scout = Character::factory()->create(['name' => 'Scout']);
        $driller = Character::factory()->create(['name' => 'Driller']);
        Loadout::factory()->create(['user_id' => $user->id, 'character_id' => $scout->id, 'name' => 'Older Scout Build', 'created_at' => Carbon::parse('2026-01-01')]);
        Loadout::factory()->create(['user_id' => $user->id, 'character_id' => $scout->id, 'name' => 'Newer Scout Build', 'created_at' => Carbon::parse('2026-02-01')]);
        Loadout::factory()->create(['user_id' => $user->id, 'character_id' => $driller->id, 'name' => 'Driller Build']);

        $this->get(route('user.profile', ['id' => $user->id, 'class' => $scout->id, 'sort' => 'newest']))
            ->assertOk()
            ->assertSeeInOrder(['Newer Scout Build', 'Older Scout Build'])
            ->assertDontSee('Driller Build')
            ->assertSee('2 loadouts');
    }

    public function test_unknown_class_and_sort_fall_back_to_all_loadouts(): void
    {
        $user = User::factory()->create();
        $scout = Character::factory()->create(['name' => 'Scout']);
        $driller = Character::factory()->create(['name' => 'Driller']);
        Loadout::factory()->create(['user_id' => $user->id, 'character_id' => $scout->id, 'name' => 'Scout Build']);
        Loadout::factory()->create(['user_id' => $user->id, 'character_id' => $driller->id, 'name' => 'Driller Build']);

        $this->get(route('user.profile', ['id' => $user->id, 'class' => 'abc', 'sort' => 'bogus']))
            ->assertOk()
            ->assertSee('Scout Build')
            ->assertSee('Driller Build');
    }

    public function test_only_the_owner_sees_loadout_actions(): void
    {
        $owner = User::factory()->create();
        $loadout = Loadout::factory()->create(['user_id' => $owner->id, 'character_id' => Character::factory()->create()->id, 'name' => 'Owned Build']);

        $this->actingAs($owner)->get(route('user.profile', $owner->id))
            ->assertOk()
            ->assertSee('aria-label="Edit Owned Build"', false)
            ->assertSee('action="/build/delete/'.$loadout->id.'"', false)
            ->assertSee(route('account.settings'));

        $this->actingAs(User::factory()->create())->get(route('user.profile', $owner->id))
            ->assertOk()
            ->assertDontSee('aria-label="Edit Owned Build"', false)
            ->assertDontSee('/build/delete/', false);
    }

    public function test_deleting_a_loadout_returns_to_the_profile(): void
    {
        $owner = User::factory()->create();
        $loadout = Loadout::factory()->create(['user_id' => $owner->id, 'character_id' => Character::factory()->create()->id, 'name' => 'Doomed Build']);

        $this->actingAs($owner)->delete('/build/delete/'.$loadout->id)
            ->assertRedirect(route('user.profile', $owner->id))
            ->assertSessionHas('status', 'Deleted "Doomed Build".');

        $this->assertModelMissing($loadout);
    }

    public function test_old_edit_profile_url_redirects_to_settings(): void
    {
        $this->get('/profile/12/edit')
            ->assertMovedPermanently()
            ->assertRedirect('/settings');
    }
}
