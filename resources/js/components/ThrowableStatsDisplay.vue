<template>
    <div class="statsDisplay" v-if="throwable">
        <span class="equipmentTitle allCaps">{{ throwable.name }}</span>
        <span class="equipmentSubTitle allCaps">{{ throwableClass }}</span>
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
            
        </div>
    </div>
</template>
<script>

export default {
    name: "ThrowableStatsDisplay",
    props: ["throwable"],
    computed: {
        baseStats: function () {
            return this.throwable?.json_stats;
        },
        throwableClass() {
            return this.throwable?.character
        },
        calcStats: function () {
            let visible = true;
            let stats = this.baseStats;

            return {
                stats: stats,
                visible: visible,
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
