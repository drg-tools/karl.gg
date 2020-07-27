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
    import apolloQueries from '../apolloQueries';
    import gql from 'graphql-tag';

    export default {
        name: 'ProfileLoadouts',
        components: {
            SmallLoadoutCard
        },
        props: {
            UserId: Number
        },
        computed: {
            dataReady() {
                return store.state.myLoadoutsDataReady;
            }
        },
        methods: {
            myLoadouts() {
                return store.state.myLoadouts;
            },
            async getMyLoadouts() {
                // store.commit('setPopularDataReady', {ready: false});
                if (store.state.myLoadouts.length > 0) {
                    // store.commit('setPopularDataReady', {ready: true});
                    return store.state.myLoadouts;
                }
                const response = await this.$apollo.query({
                    query: gql`${apolloQueries.myLoadouts}`,
                    variables: {
                        userId: this.UserId
                    }
                });
                console.log('response', response);
                store.commit('setMyLoadouts', {loadouts: response.data.myLoadouts});
                return store.state.myLoadouts;
            }
        },
        apollo: {},
        mounted: function () {
            console.log('mounted featured loadouts');
            this.getMyLoadouts().then((myLoadouts) => {
                store.commit('setMyLoadoutsDataReady', {ready: true});
                console.log('done with my loadouts', myLoadouts);
            });
        }
    };
</script>

<style>
    .loadoutCards.wide {
        width: 60%;
    }
</style>
