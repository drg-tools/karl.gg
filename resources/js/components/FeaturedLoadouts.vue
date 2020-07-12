<template>
    <div v-if="!dataReady" class="loadingIndicator">
        <img src="../assets/img/karl-spinner-free.gif" alt="loading...">
    </div>
    <div v-else-if="dataReady" class="featuredLoadoutsContainer">
        <h1>MOST POPULAR LOADOUTS</h1>
        <div class="cardGroups">
            <div class="loadoutCards">
                <SmallLoadoutCard
                    v-for="(loadout, id) in popularLoadouts('D')"
                    :key="id"
                    :loadoutId="loadout.loadoutId"
                    :name="loadout.name"
                    :author="loadout.author"
                    :classId="loadout.classId"
                    :votes="loadout.votes"
                    :primary="loadout.primary"
                    :secondary="loadout.secondary"/>
            </div>
            <div class="loadoutCards">
                <SmallLoadoutCard
                    v-for="(loadout, id) in popularLoadouts('E')"
                    :key="id"
                    :loadoutId="loadout.loadoutId"
                    :name="loadout.name"
                    :author="loadout.author"
                    :classId="loadout.classId"
                    :votes="loadout.votes"
                    :primary="loadout.primary"
                    :secondary="loadout.secondary"/>
            </div>
            <div class="loadoutCards">
                <SmallLoadoutCard
                    v-for="(loadout, id) in popularLoadouts('G')"
                    :key="id"
                    :loadoutId="loadout.loadoutId"
                    :name="loadout.name"
                    :author="loadout.author"
                    :classId="loadout.classId"
                    :votes="loadout.votes"
                    :primary="loadout.primary"
                    :secondary="loadout.secondary"/>
            </div>
            <div class="loadoutCards">
                <SmallLoadoutCard
                    v-for="(loadout, id) in popularLoadouts('S')"
                    :key="id"
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
    import LoadoutCard from './LoadoutCard.vue';
    import SmallLoadoutCard from './SmallLoadoutCard.vue';
    import store from '../store';
    import apolloQueries from '../apolloQueries';
    import gql from 'graphql-tag';

    const charToId = {
        D: 3,
        E: 1,
        G: 4,
        S: 2
    };
    const sortByVotes = (a, b) => {
        if (a.votes < b.votes) {
            return 1;
        }
        if (a.votes > b.votes) {
            return -1;
        }
        return 0;
    };
    const numberOfLoadoutsToShowPerClass = 3;
    export default {
        name: 'FeaturedLoadouts',
        components: {
            LoadoutCard,
            SmallLoadoutCard
        },
        computed: {
            dataReady() {
                return store.state.popularDataReady;
            }
            /*popularLoadouts() {
                return [
                    {
                        loadoutId: '111111',
                        name: 'Karl\'s Freezer Build',
                        author: 'Karl_21347',
                        classId: 'D',
                        salutes: 47,
                        primary: 'P2_Cryo',
                        secondary: 'S1_Subata',
                        lastUpdate: new Date('2020-02-15')
                    },
                    {
                        loadoutId: '222222',
                        name: 'Karl\'s Flamer Build',
                        author: 'Karl_21347',
                        classId: 'D',
                        salutes: 43,
                        primary: 'P1_CRSPR',
                        secondary: 'S2_Plasma',
                        lastUpdate: new Date('2020-02-14')
                    },
                    {
                        loadoutId: '333333',
                        name: 'pew pew pew',
                        author: 'redguy',
                        classId: 'G',
                        salutes: 22,
                        primary: 'P1_Lead',
                        secondary: 'S1_Bulldog',
                        lastUpdate: new Date('2020-06-01')
                    },
                    {
                        loadoutId: '444444',
                        name: 'cheese party',
                        author: 'turret-master_666',
                        classId: 'E',
                        salutes: 21,
                        primary: 'P1_Warthog',
                        secondary: 'S1_PGL',
                        lastUpdate: new Date('2020-04-02')
                    }
                ];
            },*/
        },
        methods: {
            popularLoadouts(classId) {
                let loadouts = store.state.popularLoadouts.filter(loadout => loadout.classId === classId);
                loadouts.sort(sortByVotes);
                return loadouts.slice(0, numberOfLoadoutsToShowPerClass);
            },
            async getPopularLoadouts() {
                // store.commit('setPopularDataReady', {ready: false});
                if (store.state.popularLoadouts.length > 0) {
                    // store.commit('setPopularDataReady', {ready: true});
                    return store.state.popularLoadouts;
                }
                const response = await this.$apollo.query({
                    query: gql`${apolloQueries.popularLoadouts}`
                });
                console.log('response', response);
                store.commit('setPopularLoadouts', {loadouts: response.data.loadouts.data});
                return store.state.popularLoadouts;
            }
        },
        apollo: {},
        mounted: function () {
            console.log('mounted featured loadouts');
            this.getPopularLoadouts().then((popularLoadouts) => {
                store.commit('setPopularDataReady', {ready: true});
                console.log('done with popular loadouts', popularLoadouts);
            });
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
    @media (max-width: 770px) {
        .loadoutCards {
            width: 100%;
        }
    }

    h1 {
        color: #fc9e00;
        text-align: center
    }
</style>
