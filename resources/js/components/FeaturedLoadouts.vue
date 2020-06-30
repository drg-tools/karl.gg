<template>
    <div class="featuredLoadoutsContainer">
        <LoadoutCard 
        v-for="(loadout, loadoutId) in drillerLoadouts"
                :key="loadoutId"
                :iconPath="loadout.iconPath"
                :name="loadout.name"
                :author="loadout.author"
                :classId="'D'"
                :salutes="loadout.salutes"
                :primaryId="loadout.primaryId"
                :secondaryId="loadout.secondaryId" />
    </div>
</template>

<script>
    import LoadoutCard from './LoadoutCard.vue';
    import gql from 'graphql-tag';

    export default {
        name: 'FeaturedLoadouts',
        components: {
            LoadoutCard,
        },
        computed: {
            drillerLoadouts() {
                return [
                    {
                        iconPath: "50px-Driller_icon.png",
                        name: "Karl's Freezer Build",
                        author: "Karl_21347",
                        salutes: 47,
                        primaryId: "P2",
                        secondaryId: "S1"
                    }
                ];
            },
        },
        methods: {},
        apollo: {},
        mounted: function () {
            console.log("mounted featured loadouts")
        }
    };
</script>

<style>
    .featuredLoadoutsContainer {
        width: 100%;
        display: flex;
        flex-wrap: wrap;
        border-top: 5px solid #fc9e00;
        background-color: #352e1e;
        margin-bottom: 0.5rem;
    }
</style>
