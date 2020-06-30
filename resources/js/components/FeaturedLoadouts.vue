<template>
    <div class="featuredLoadoutsContainer">
        <h1>MOST POPULAR LOADOUTS</h1>
        <div class="loadoutCards">
            <LoadoutCard
                v-for="(loadout, loadoutId) in popularLoadouts"
                :key="loadoutId"
                :iconPath="loadout.iconPath"
                :name="loadout.name"
                :author="loadout.author"
                :classId="loadout.classId"
                :salutes="loadout.salutes"
                :primary="loadout.primary"
                :secondary="loadout.secondary"/>
        </div>
    </div>
</template>

<script>
    import LoadoutCard from './LoadoutCard.vue';
    import gql from 'graphql-tag';

    export default {
        name: 'FeaturedLoadouts',
        components: {
            LoadoutCard
        },
        computed: {
            popularLoadouts() {
                return [
                    {
                        iconPath: '50px-Driller_icon.png',
                        name: 'Karl\'s Freezer Build',
                        author: 'Karl_21347',
                        classId: "D",
                        salutes: 47,
                        primary: 'P2_Cryo',
                        secondary: 'S1_Subata'
                    },
                    {
                        iconPath: '50px-Gunner_icon.png',
                        name: 'pew pew pew',
                        author: 'redguy',
                        classId: "G",
                        salutes: 22,
                        primary: 'P1_Lead',
                        secondary: 'S1_Bulldog'
                    },
                    {
                        iconPath: '50px-Engineer_icon.png',
                        name: 'cheese party',
                        author: 'turret-master_666',
                        classId: "E",
                        salutes: 21,
                        primary: 'P1_Warthog',
                        secondary: 'S1_PGL'
                    }
                ];
            }
        },
        methods: {},
        apollo: {},
        mounted: function () {
            console.log('mounted featured loadouts');
        }
    };
</script>

<style>
    .featuredLoadoutsContainer {
        width: 100%;
        border-top: 5px solid #fc9e00;
        background-color: #352E1E;
        margin-bottom: 0.5rem;
    }

    .loadoutCards {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    h1 {
        font-size: 1.4rem;
        color: #fc9e00
    }
</style>
