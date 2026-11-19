<template>
    <div class="statsDisplay" v-if="equipment">
        <span class="equipmentTitle allCaps">{{ equipment.name }}</span>
        <span class="equipmentSubTitle allCaps">{{ equipmentClass }}</span>
        <div class="statsBaseContainer">
            <div
                v-for="(stat, statId) in calcStats.stats"
                :key="statId"
                class="statsContainer"
            >
                <span
                    class="statsText"
                    :class="[stat.inactive ? 'inactiveStat' : '']"
                    >{{ stat.name }}:</span
                >
                <div class="statsValueContainer">
                    <span
                        class="statsValue fixedWidth"
                        :class="[stat.inactive ? 'inactiveStat' : '']"
                    >
                        {{ stat.baseValue }}<span v-if="stat.percent">%</span>
                    </span>
                    <span class="statsModifier fixedWidth">{{
                        stat.modifier
                    }}</span>
                    <span
                        class="statsValue fixedWidth"
                        :class="[
                            stat.modified
                                ? 'modifiedStat'
                                : stat.inactive
                                ? 'inactiveStat'
                                : '',
                        ]"
                    >
                        {{ stat.value }}<span v-if="stat.percent">%</span>
                    </span>
                </div>
            </div>
            <!-- todo: different color for dps numbers -->
            <span
                v-if="calcStats.dps && calcStats.dps !== 'NaN'"
                class="text-white text-lg block my-2"
                >DPS: {{ calcStats.dps }}</span
            >
            <span
                v-if="!!calcStats.dps && calcStats.dps !== 'NaN'"
                class="inactiveStat"
            >
                <i>Theoretical</i> damage per second, ignoring armor break and
                weakspot bonuses.
            </span>

            <span v-if="calcStats.dpb" class="text-white text-lg block my-2"
                >Damage per shot: {{ calcStats.dpb }}</span
            >
            <!-- damage per bullet -->

            <span v-if="calcStats.wpd" class="text-white text-lg block my-2"
                >Weakpoint Damage:
                {{
                    `(1x: ${calcStats.wpd} / 2x: ${(calcStats.wpd * 2).toFixed(
                        2
                    )}
                / 3x: ${(calcStats.wpd * 3).toFixed(2)})`
                }}</span
            >
            <span v-if="calcStats.wpd" class="inactiveStat">
                <i>Important,</i> 1x damage is applied to praetorians and
                oppressors. 2x damage is applied to all grunts and most enemies.
                3x is applied to bulks, breeders, mactera, etc.
            </span>

            <span v-if="calcStats.dpm" class="text-white text-lg block my-2"
                >Magazine damage: {{ calcStats.dpm
                }}<span v-if="calcStats.tte">
                    / Time to empty mag: {{ calcStats.tte }}S</span
                >
            </span>
            <span v-if="calcStats.dpm" class="inactiveStat">
                <i>Theoretical</i> damage per magazine and how long it takes to
                empty it.
            </span>

            <span v-if="calcStats.dpa" class="text-white text-lg block my-2"
                >Total damage: {{ calcStats.dpa }}</span
            >
            <span v-if="calcStats.dpa" class="inactiveStat">
                <i>Theoretical</i> total damage available with initial
                ammunition.
            </span>

            <span v-if="calcStats.ex1" class="text-white text-lg block my-2"
                >Total lighting time: {{ calcStats.ex1 }} minutes</span
            >
            <span v-if="calcStats.ex1" class="inactiveStat">
                <i>Theoretical</i> total lighting time available with initial
                ammunition.
            </span>

            <span
                v-if="calcStats.dpsplasma && calcStats.dpsplasma !== 'NaN'"
                class="text-white text-lg block my-2"
                >Normal shot DPS: {{ calcStats.dpsplasma }} <br />Charged shot
                DPS: {{ calcStats.dpscharged }}</span
            >
            <span
                v-if="!!calcStats.dpsplasma && calcStats.dpsplasma !== 'NaN'"
                class="inactiveStat"
            >
                <i>Theoretical</i> damage per second without taking overheat
                into account, ignoring armor break and weakspot bonuses.
            </span>

            <span
                v-if="calcStats.dpbplasma"
                class="text-white text-lg block my-2"
                >Damage per normal shot: {{ calcStats.dpbplasma }} <br />Damage
                per charged shot: {{ calcStats.dpbcharged }}</span
            >

            <span
                v-if="calcStats.dpaplasma"
                class="text-white text-lg block my-2"
                >Total damage for normal shots: {{ calcStats.dpaplasma }}
                <br />Total damage for charged shots:
                {{ calcStats.dpacharged }}</span
            >
            <span v-if="calcStats.dpaplasma" class="inactiveStat">
                <i>Theoretical</i> total damage available with initial
                ammunition.
            </span>

        </div>
    </div>
</template>
<!--todo: show most cost effective upgrade in tier (most change %)-->
<script>

import clone from 'lodash-es/clone';
import { calculateDamage as _calculateDamage, getModifiedStats } from '../statCalculator';

export default {
    name: "MainStatsDisplay",
    props: ["equipment", "overclock", "mods"],
    computed: {
        baseStats: function () {
            return this.equipment?.json_stats;
        },
        equipmentClass() {
            return this.equipment?.gun_class
        },
        calcStats: function () {
            let visible = false;

            let allSelectedUpgrades = clone(this.mods);

            if (this.overclock) {
                allSelectedUpgrades.push(this.overclock)
            }
            let results = getModifiedStats(this.baseStats, allSelectedUpgrades);
            let stats = results.stats;

            const damage = _calculateDamage(stats, this.equipmentClass);
            const derivedMetrics = Object.fromEntries(
                Object.entries(damage).filter(([, value]) => value && Number.isFinite(Number(value)))
            );

            return {
                stats: stats,
                visible: visible,
                ...derivedMetrics,
            };
        },
    },
};
</script>
<style scoped>
.statsDisplay {
    font-family: "Avenir", Helvetica, Arial, sans-serif;
}
.statsDisplay {
    flex: 1;
    width: 100%;
    padding-right: 1rem;
    display: flex;
    flex-flow: column;
    align-items: center;
}
@media (max-width: 770px) {
    .statsDisplay {
        flex: 0 0 100%;
        order: 2;
        padding: 0;
    }
}
.equipmentTitle {
    font-size: 1.5rem;
    text-align: center;
    color: #fc9e00;
    margin-bottom: 0;
}
.equipmentSubTitle {
    text-align: center;
    color: #fffbff;
    font-size: 1rem;
    margin-top: 0;
}
.statsBaseContainer {
    width: 100%;
}
.statsContainer {
    display: flex;
    width: 100%;
}
.fixedWidth {
    width: 33%;
}
.statsValueContainer {
    width: 45%;
    display: flex;
    justify-content: flex-end;
}
.statsText {
    width: 55%;
    color: rgba(255, 251, 255, 1);
    line-height: 1.2rem;
}
.statsValue {
    color: #fc9e00;
    text-align: right;
}
.statsModifier {
    text-align: right;
    color: #fccc00;
}
.modifiedStat {
    color: #fccc00;
}
.inactiveStat {
    color: rgba(107, 114, 128, var(--tw-text-opacity));
}
</style>
