@extends('layouts.app')

@php
    $slotLabels = [1 => 'Primary weapon', 2 => 'Secondary weapon'];
    $hasOverclock = $modMatrix['selected_index'][6]['selected'];

    /**
     * Metric tables: label => [column, value shown when the column is -1].
     *
     * @var array<string, array<string, array{0: string, 1?: string}>> $metricGroups
     */
    $metricGroups = [
        'Burst DPS' => [
            'Ideal' => ['ideal_burst_dps'],
            'Weakpoint' => ['burst_dps_wp'],
            'Accuracy' => ['burst_dps_acc'],
            'Armor wasting' => ['burst_dps_aw'],
            'Weakpoint + accuracy' => ['burst_dps_wp_acc'],
            'Weakpoint + armor wasting' => ['burst_dps_wp_aw'],
            'Accuracy + armor wasting' => ['burst_dps_acc_aw'],
            'All three' => ['burst_dps_wp_acc_aw'],
        ],
        'Sustained DPS' => [
            'Ideal' => ['ideal_sustained_dps'],
            'Weakpoint' => ['sustained_dps_wp'],
            'Accuracy' => ['sustained_dps_acc'],
            'Armor wasting' => ['sustained_dps_aw'],
            'Weakpoint + accuracy' => ['sustained_dps_wp_acc'],
            'Weakpoint + armor wasting' => ['sustained_dps_wp_aw'],
            'Accuracy + armor wasting' => ['sustained_dps_acc_aw'],
            'All three' => ['sustained_dps_wp_acc_aw'],
        ],
        'Other metrics' => [
            'Additional target DPS' => ['ideal_additional_target_dps'],
            'Max targets hit per shot' => ['max_num_targets_per_shot'],
            'Max multi-target damage' => ['max_multi_target_damage'],
            'Ammo efficiency' => ['ammo_efficiency'],
            'Damage lost to armor (%)' => ['damage_wasted_by_armor'],
            'General accuracy (%)' => ['general_accuracy', 'Manually aimed'],
            'Weakpoint accuracy (%)' => ['weakpoint_accuracy', 'Manually aimed'],
            'Time to expend all ammo' => ['firing_duration'],
            'Average time to kill' => ['average_time_to_kill'],
            'Average overkill (%)' => ['average_overkill'],
            'Average time to ignite or freeze' => ['average_time_to_ignite_or_freeze', 'N/A'],
            'Damage per magazine' => ['damage_per_magazine'],
            'Time to fire magazine' => ['time_to_fire_magazine'],
        ],
    ];
@endphp

@section('content')
    <div class="loadout-page asv">
        <header class="browse-header">
            <div>
                <p class="loadout-eyebrow">{{ $slotLabels[$gun['character_slot']] ?? 'Equipment' }} · Advanced statistics</p>
                <h1 class="browse-header__title">{{ $gun['name'] }}</h1>
                <p class="browse-header__lede">
                    Build <code class="asv-combo">{{ $combo }}</code>
                    @if ($hasOverclock)
                        with {{ $overclock['overclock_name'] }}
                    @endif
                </p>
            </div>
            <a class="loadout-button is-secondary" href="{{ url()->previous() }}"><span aria-hidden="true">←</span> Back</a>
        </header>

        @include('components.asv-alert')

        <div class="asv-layout">
            <section class="loadout-panel asv-build" aria-label="Selected upgrades">
                <x-equipment-component :gun="$gun" :modMatrix="$modMatrix" :overclock="$overclock" />
            </section>

            <div class="asv-metrics">
                @foreach ($metricGroups as $groupTitle => $metrics)
                    <section class="loadout-panel asv-metric-group" aria-labelledby="asv-group-{{ $loop->index }}">
                        <h2 id="asv-group-{{ $loop->index }}">{{ $groupTitle }}</h2>
                        <dl>
                            @foreach ($metrics as $label => $metric)
                                @php
                                    $value = $buildMetrics->{$metric[0]};
                                    $fallback = $metric[1] ?? null;
                                @endphp
                                <div class="asv-metric">
                                    <dt>{{ $label }}</dt>
                                    @if ($fallback !== null && $value == -1)
                                        <dd class="is-muted">{{ $fallback }}</dd>
                                    @else
                                        <dd>{{ round($value, 4) }}</dd>
                                    @endif
                                </div>
                            @endforeach
                        </dl>
                    </section>
                @endforeach
            </div>
        </div>
    </div>
@endsection
