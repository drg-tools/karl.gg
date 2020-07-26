<template>
    <div class="featuredLoadoutsContainer">
        <div class="cardGroups">
            <div class="loadoutCards">
                <SmallLoadoutCard
                    v-for="(loadouts, id) in popularLoadouts('D')"
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
                    v-for="(loadouts, id) in popularLoadouts('E')"
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
                    v-for="(loadouts, id) in popularLoadouts('G')"
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
                    v-for="(loadouts, id) in popularLoadouts('S')"
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
        name: 'ProfileLoadouts',
        components: {
            LoadoutCard,
            SmallLoadoutCard
        },
        props: {
            LoadoutsList: null
        },
        data () {
            return {
                loadouts: this.LoadoutsList,
          }
        },
        methods: {
            popularLoadouts(classId) {
                let profileLoadouts = this.loadouts.filter(loadout => loadout.classId === classId);
                profileLoadouts.sort(sortByVotes);
                return profileLoadouts.slice(0, numberOfLoadoutsToShowPerClass);
            },
        },
        apollo: {},
        mounted: function () {
            console.log('mounted profile loadouts');
            console.log('here come the loadouts --------------------');
            console.log(this.loadouts);
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
