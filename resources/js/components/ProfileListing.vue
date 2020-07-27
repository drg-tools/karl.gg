<template>
    <div v-if="!dataReady" class="loadingIndicator">
        <img src="../assets/img/karl-spinner-free.gif" alt="loading...">
    </div>
    <div v-else-if="dataReady" class="featuredLoadoutsContainer">
        <h1>MY LOADOUTS</h1>
        <div class="cardGroups">
            <div class="loadoutCards wide">
                <!-- todo: could also use table -->
                <SmallLoadoutCard
                    v-for="(loadout, id) in myLoadouts()"
                    :key="id"
                    :editEnabled="true"
                    :deleteEnabled="true"
                    :loadoutId="loadout.loadoutId"
                    :name="loadout.name"
                    :author="loadout.author"
                    :classId="loadout.classId"
                    :votes="loadout.votes"
                    :primary="loadout.primary"
                    :secondary="loadout.secondary"/>
            </div>
        </div>
    </div>
</template>

<script>
    import SmallLoadoutCard from './SmallLoadoutCard.vue';
    import store from '../store';

    export default {
        name: 'ProfileLoadouts',
        components: {
            SmallLoadoutCard
        },
        props: {
            LoadoutsList: Array
        },
        computed: {
            dataReady() {
                return store.state.myLoadoutsDataReady;
            }
        },
        methods: {
            myLoadouts() {
                return store.state.myLoadouts;
            }
        },
        apollo: {},
        mounted: function () {
            console.log('mounted profile loadouts');
            console.log('here come the loadouts --------------------');
            console.log(this.LoadoutsList);

            store.commit('setMyLoadouts', {loadouts: this.LoadoutsList});
            store.commit('setMyLoadoutsDataReady', {ready: true});
        }
    };
</script>

<style>
    .loadoutCards.wide {
        width: 60%;
    }
</style>
