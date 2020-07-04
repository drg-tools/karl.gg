<template>
    <div class="featuredLoadoutsContainer">
        <h1>MOST POPULAR LOADOUTS</h1>
        <!--<div class="loadoutCards">
            <LoadoutCard
                v-for="(loadout, loadoutId) in popularLoadouts"
                :key="loadoutId"
                :iconPath="loadout.iconPath"
                :name="loadout.name"
                :author="loadout.author"
                :classId="loadout.classId"
                :salutes="loadout.salutes"
                :primary="loadout.primary"
                :secondary="loadout.secondary"/>-->
        <div class="cardGroups">
            <div class="loadoutCards">
                <SmallLoadoutCard
                    v-for="(loadout, id) in popularDrillerLoadouts"
                    :key="id"
                    :loadoutId="loadout.loadoutId"
                    :iconPath="loadout.iconPath"
                    :name="loadout.name"
                    :author="loadout.author"
                    :classId="loadout.classId"
                    :salutes="loadout.salutes"
                    :primary="loadout.primary"
                    :secondary="loadout.secondary"/>
            </div>
            <div class="loadoutCards">
                <SmallLoadoutCard
                    v-for="(loadout, id) in popularEngineerLoadouts"
                    :key="id"
                    :loadoutId="loadout.loadoutId"
                    :iconPath="loadout.iconPath"
                    :name="loadout.name"
                    :author="loadout.author"
                    :classId="loadout.classId"
                    :salutes="loadout.salutes"
                    :primary="loadout.primary"
                    :secondary="loadout.secondary"/>
            </div>
            <div class="loadoutCards">
                <SmallLoadoutCard
                    v-for="(loadout, id) in popularGunnerLoadouts"
                    :key="id"
                    :loadoutId="loadout.loadoutId"
                    :iconPath="loadout.iconPath"
                    :name="loadout.name"
                    :author="loadout.author"
                    :classId="loadout.classId"
                    :salutes="loadout.salutes"
                    :primary="loadout.primary"
                    :secondary="loadout.secondary"/>
            </div>
            <div class="loadoutCards">
                <SmallLoadoutCard
                    v-for="(loadout, id) in popularScoutLoadouts"
                    :key="id"
                    :loadoutId="loadout.loadoutId"
                    :iconPath="loadout.iconPath"
                    :name="loadout.name"
                    :author="loadout.author"
                    :classId="loadout.classId"
                    :salutes="loadout.salutes"
                    :primary="loadout.primary"
                    :secondary="loadout.secondary"/>
            </div>
        </div>
    </div>
</template>

<script>
    import LoadoutCard from './LoadoutCard.vue';
    import SmallLoadoutCard from './SmallLoadoutCard.vue';
    import gql from 'graphql-tag';

    export default {
        name: 'FeaturedLoadouts',
        components: {
            LoadoutCard,
            SmallLoadoutCard
        },
        computed: {
            popularLoadouts() {
                return [
                    {
                        loadoutId: "111111",
                        iconPath: '50px-Driller_icon.png',
                        name: 'Karl\'s Freezer Build',
                        author: 'Karl_21347',
                        classId: 'D',
                        salutes: 47,
                        primary: 'P2_Cryo',
                        secondary: 'S1_Subata',
                        lastUpdate: new Date('2020-02-15')
                    },
                    {
                        loadoutId: "222222",
                        iconPath: '50px-Driller_icon.png',
                        name: 'Karl\'s Flamer Build',
                        author: 'Karl_21347',
                        classId: 'D',
                        salutes: 43,
                        primary: 'P1_CRSPR',
                        secondary: 'S2_Plasma',
                        lastUpdate: new Date('2020-02-14')
                    },
                    {
                        loadoutId: "333333",
                        iconPath: '50px-Gunner_icon.png',
                        name: 'pew pew pew',
                        author: 'redguy',
                        classId: 'G',
                        salutes: 22,
                        primary: 'P1_Lead',
                        secondary: 'S1_Bulldog',
                        lastUpdate: new Date('2020-06-01')
                    },
                    {
                        loadoutId: "444444",
                        iconPath: '50px-Engineer_icon.png',
                        name: 'cheese party',
                        author: 'turret-master_666',
                        classId: 'E',
                        salutes: 21,
                        primary: 'P1_Warthog',
                        secondary: 'S1_PGL',
                        lastUpdate: new Date('2020-04-02')
                    }
                ];
            },
            popularDrillerLoadouts() {
                /* todo: return top 5, sorted by salutes */
                return this.popularLoadouts.filter(loadout => loadout.classId === "D");
            },
            popularEngineerLoadouts() {
                return this.popularLoadouts.filter(loadout => loadout.classId === "E");
            },
            popularGunnerLoadouts() {
                return this.popularLoadouts.filter(loadout => loadout.classId === "G");
            },
            popularScoutLoadouts() {
                return this.popularLoadouts.filter(loadout => loadout.classId === "S");
            }
        },
        methods: {},
        apollo: {},
        mounted: function () {
            console.log('mounted featured loadouts');
        }
    };
</script>

<style scoped>
    .featuredLoadoutsContainer {
        width: 100%;
        border-top: 5px solid #fc9e00;
        background-color: #352E1E;
        margin-bottom: 0.5rem;
    }

    .cardGroups {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .loadoutCards {
        width: 48%;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        padding-bottom: 2rem;
    }

    h1 {
        color: #fc9e00;
        text-align: center
    }
</style>
