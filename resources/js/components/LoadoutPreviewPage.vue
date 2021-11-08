<template>
    <div class="equipmentCards text-gray-300">

        <div class="flex flex-row justify-around my-5">
        <div>
            
                <PreviewPageCard
                    v-if="this.primary"
                    :gun="true"
                    :equipment="this.primary"
                    :equipment-mods="this.primaryMods"
                    :selected-mods="this.selectedPrimaryMods"
                    :selected-overclock="this.primaryOverclock"
                />

        </div>

        <div>

            <PreviewPageCard
                    v-if="this.secondary"
                    :gun="true"
                    :equipment="this.secondary"
                    :equipment-mods="this.secondaryMods"
                    :selected-mods="this.selectedSecondaryMods"
                    :selected-overclock="this.secondaryOverclock"
                />
                

        </div>

        <!-- <div v-for="equipment in this.equipments" v-if="equipments.length > 0">
            
                <PreviewCard
                    :equipment="equipment"
                    :selected-mods="equipmentModIds"
                />
        </div>  -->

        

        </div>
        <div class="guideAccordion text-gray-300">
            <h1 @click="guideIsOpen = !guideIsOpen" class="text-karl-orange text-xl uppercase p-4">Loadout Guide <i :class="guideIsOpen ? 'fas fa-chevron-down invertIcon': 'fas fa-chevron-down'"></i></h1>
            <div v-show="guideIsOpen" class="p-6">
                <viewer :initialValue="this.loadoutData.description" />
            </div>
        </div>

    </div>
</template>

<script>
    import store from '../store';
    import apolloQueries from '../apolloQueries';
    import gql from 'graphql-tag';
    import PreviewPageCard from './PreviewPageCard.vue';
    import '@toast-ui/editor/dist/toastui-editor-viewer.css';
    import { Viewer } from '@toast-ui/vue-editor';

    export default {
        name: 'LoadoutPreviewPage',
        props: {
            loadoutData: Object,
            primary: Object,
            primaryMods: Array,
            secondary: Object,
            secondaryMods: Object,
            overclocks: Array,
            equipments: Object,
        },
        components: {
            PreviewPageCard,
            viewer: Viewer,
        },
        data() {
            return {
                guideIsOpen: false, // closed by default
            }
        },
        computed: {
            dataReady() {
                return store.state.loadoutDetailDataReady;
            },
            loadoutDetails() {
                return store.state.loadoutDetails;
            }
        },
        methods: {

            async getLoadoutDetails(loadoutId) {
                
                const response = await this.$apollo.query({
                    query: gql`${apolloQueries.loadoutDetails(loadoutId)}`
                });
                /* todo: run these two queries in parallel */
                let userVotedStatus = false;
                try {
                    let getVoteStatus = await this.$apollo.query({
                        query: gql`query getVoteStatus($id: Int!)
                            {
                                getVoteStatus(id: $id)
                            }
                            `,
                        variables: {
                            id: parseInt(loadoutId)
                        }
                    });
                    // getVoteStatus
                    if (getVoteStatus.data.getVoteStatus === 1) {
                        userVotedStatus = true;
                    }
                } catch (e) {
                    console.warn('user not signed in, no voted status', e);
                }
                store.commit('setLoadoutDetails', {loadout: response.data.loadout, userVoted: userVotedStatus});

              
            },
            
        },
        mounted: function () {
            // get loadout id from url
            console.log('loadout from props');
            console.log(this.loadoutData);
            console.log(this.primary);
            console.log(this.secondary);
            console.log(this.equipments);
            console.log(this.primaryMods);
            console.log(this.primaryOverclock);
            
        },
        computed: {
            selectedPrimaryMods: function() {
                console.log('primaryMods');
                let computedPrimaryMods = this.loadoutData.mods.filter(mod => mod.gun_id === this.primary.id);
                computedPrimaryMods = computedPrimaryMods.map(mod => mod.id);
                console.log('primary mod ids');
                console.log(computedPrimaryMods);
                return computedPrimaryMods;
            },
            primaryOverclock: function() {
                console.log('primary overclocks');
                console.log('primary ID');
                console.log(this.primary?.id);
                let primaryOverclock = this.overclocks?.filter(oc => oc.gun_id === this.primary.id)
                console.log(primaryOverclock[0]);
                return primaryOverclock[0];
            },
            selectedSecondaryMods: function() {
                console.log('SecondaryMods');
                let computedSecondaryMods = this.loadoutData.mods.filter(mod => mod.gun_id === this.secondary.id);
                computedSecondaryMods = computedSecondaryMods.map(mod => mod.id);
                console.log('secondary mod ids');
                console.log(computedSecondaryMods);
                return computedSecondaryMods;
            },
            secondaryOverclock: function() {
                console.log('secondary overclocks');
                console.log('secondary ID');
                console.log(this.secondary?.id);
                let secondaryOverclock = this.overclocks?.filter(oc => oc.gun_id === this.secondary.id)
                console.log(secondaryOverclock[0]);
                return secondaryOverclock[0];
            }
        }
    };
</script>
