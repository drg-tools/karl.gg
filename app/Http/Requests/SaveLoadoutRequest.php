<?php

namespace App\Http\Requests;

use App\Models\EquipmentMod;
use App\Models\Mod;
use App\Models\Overclock;
use Blaspsoft\Blasp\Enums\Severity;
use Blaspsoft\Blasp\Rules\Profanity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class SaveLoadoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        $loadout = $this->route('loadout');

        return $loadout === null || ($this->user()?->can('update', $loadout) ?? false);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $characterId = $this->integer('character_id');

        return [
            'name' => ['required', 'string', 'max:255', Profanity::severity(Severity::Extreme)],
            'description' => ['nullable', 'string', 'max:50000', Profanity::severity(Severity::Extreme)],
            'character_id' => ['required', 'integer', 'exists:characters,id'],
            'throwable_id' => ['nullable', 'integer', Rule::exists('throwables', 'id')->where('character_id', $characterId)],
            'mods' => ['present', 'array', 'max:10'],
            'mods.*' => ['required', 'integer', 'distinct', Rule::exists('mods', 'id')->where('character_id', $characterId)],
            'overclocks' => ['present', 'array', 'max:2'],
            'overclocks.*' => ['required', 'integer', 'distinct', Rule::exists('overclocks', 'id')->where('character_id', $characterId)],
            'equipment_mods' => ['present', 'array', 'max:30'],
            'equipment_mods.*' => ['required', 'integer', 'distinct', Rule::exists('equipment_mods', 'id')->where('character_id', $characterId)],
        ];
    }

    /** @return array<int, callable> */
    public function after(): array
    {
        return [function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }
            $mods = Mod::with('gun')->findMany($this->input('mods'));
            $overclocks = Overclock::with('gun')->findMany($this->input('overclocks'));
            $equipmentMods = EquipmentMod::findMany($this->input('equipment_mods'));
            if ($mods->groupBy(fn (Mod $mod): string => $mod->gun_id.':'.$mod->mod_tier)->contains(fn ($group): bool => $group->count() > 1)) {
                $validator->errors()->add('mods', 'Choose one mod per weapon tier.');
            }
            if ($overclocks->groupBy('gun_id')->contains(fn ($group): bool => $group->count() > 1)) {
                $validator->errors()->add('overclocks', 'Choose one overclock per weapon.');
            }
            $guns = $mods->pluck('gun')->merge($overclocks->pluck('gun'))->filter()->unique('id');
            if ($guns->groupBy('character_slot')->contains(fn ($group): bool => $group->count() > 1)) {
                $validator->errors()->add('mods', 'Choose one weapon per slot.');
            }
            if ($equipmentMods->groupBy(fn (EquipmentMod $mod): string => $mod->equipment_id.':'.$mod->mod_tier)->contains(fn ($group): bool => $group->count() > 1)) {
                $validator->errors()->add('equipment_mods', 'Choose one mod per equipment tier.');
            }
            if ($validator->errors()->isNotEmpty()) {
                return;
            }
            if ($guns->contains(fn ($gun): bool => $mods->where('gun_id', $gun->id)->isEmpty())) {
                $validator->errors()->add('mods', 'Choose at least one upgrade for each weapon.');
            }
        }];
    }
}
