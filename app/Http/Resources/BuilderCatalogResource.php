<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BuilderCatalogResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id, 'name' => $this->name,
            'guns' => $this->guns->map(fn (Model $gun): array => array_merge($this->item($gun), [
                'mods' => $gun->mods->map(fn (Model $mod): array => $this->item($mod)),
                'overclocks' => $gun->overclocks->map(fn (Model $overclock): array => $this->item($overclock)),
            ])),
            'equipments' => $this->equipments->map(fn (Model $equipment): array => array_merge($this->item($equipment), [
                'equipment_mods' => $equipment->equipment_mods->map(fn (Model $mod): array => $this->item($mod)),
            ])),
            'throwables' => $this->throwables->map(fn (Model $throwable): array => $this->item($throwable)),
        ];
    }

    /** @return array<string, mixed> */
    private function item(Model $item): array
    {
        $data = $item->only(['id', 'name', 'gun_class', 'character_slot', 'image', 'icon', 'eq_type', 'description', 'text_description', 'mod_name', 'mod_tier', 'mod_index', 'overclock_name', 'overclock_index', 'overclock_type', 'credits_cost', 'magnite_cost', 'bismor_cost', 'umanite_cost', 'croppa_cost', 'enor_pearl_cost', 'jadiz_cost']);
        $data['json_stats'] = is_string($item->json_stats) ? (json_decode($item->json_stats, true) ?: []) : ($item->json_stats ?? []);

        return $data;
    }
}
