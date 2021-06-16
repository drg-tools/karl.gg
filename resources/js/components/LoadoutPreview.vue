<template>
    <div v-if="dataReady" class="equipmentCards text-gray-300">

        <div class="equipmentSection">
        <EquipmentCard v-if="loadoutDetails.primaryWeapons[0]"
                       :classId="loadoutDetails.classId"
                       :equipmentId="loadoutDetails.primaryWeapons[0].id"
                       :equipmentName="loadoutDetails.primaryWeapons[0].name"
                       :icon="loadoutDetails.primaryWeapons[0].icon"
                       :overclock="loadoutDetails.primaryWeapons[0].overclocks[0]"
                       :modMatrix="loadoutDetails.primaryWeapons[0].modMatrix"
                       :build="getBuildString(loadoutDetails.primaryWeapons[0].modString,loadoutDetails.primaryWeapons[0].overclocks[0])"
                       :equipmentType="'primary'">
        </EquipmentCard>
        <EquipmentCard v-if="loadoutDetails.secondaryWeapons[0]"
                       :classId="loadoutDetails.classId"
                       :equipmentId="loadoutDetails.secondaryWeapons[0].id"
                       :equipmentName="loadoutDetails.secondaryWeapons[0].name"
                       :icon="loadoutDetails.secondaryWeapons[0].icon"
                       :overclock="loadoutDetails.secondaryWeapons[0].overclocks[0]"
                       :modMatrix="loadoutDetails.secondaryWeapons[0].modMatrix"
                       :build="getBuildString(loadoutDetails.secondaryWeapons[0].modString,loadoutDetails.secondaryWeapons[0].overclocks[0])"
                       :equipmentType="'secondary'">
        </EquipmentCard>
        <EquipmentCard v-for="(equipment, equipmentId) in loadoutDetails.equipments" :key="equipmentId"
                       :classId="loadoutDetails.classId"
                       :equipmentId="equipment.id"
                       :equipmentName="equipment.name"
                       :icon="equipment.icon"
                       :modMatrix="equipment.modMatrix"
                       :build="equipment.modString.join('')">
        </EquipmentCard>
        </div>
        <div class="guideAccordion text-gray-300">
            <h1 @click="guideIsOpen = !guideIsOpen" class="text-karl-orange text-xl uppercase p-4">Loadout Guide <i :class="guideIsOpen ? 'fas fa-chevron-down invertIcon': 'fas fa-chevron-down'"></i></h1>
            <div v-show="guideIsOpen" class="p-6">
                <viewer :initialValue="loadoutDetails.description" />
            </div>
        </div>

    </div>
</template>

<script>
    import store from '../store';
    import apolloQueries from '../apolloQueries';
    import gql from 'graphql-tag';
    import EquipmentCard from './EquipmentCard.vue';
    import '@toast-ui/editor/dist/toastui-editor-viewer.css';
    import { Viewer } from '@toast-ui/vue-editor';

    export default {
        name: 'LoadoutPreview',
        components: {
            EquipmentCard,
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
                if (store.state.loadoutDetails.length > 0) {
                    return store.state.loadoutDetails;
                }
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

                let baseModWeaponQueries = [];
                if (store.state.loadoutDetails.primaryWeapons[0]) {
                    baseModWeaponQueries.push(this.$apollo.query({
                        query: gql`${apolloQueries.getModsForGun(store.state.loadoutDetails.primaryWeapons[0].id)}`
                    }));
                }
                if (store.state.loadoutDetails.secondaryWeapons[0]) {
                    baseModWeaponQueries.push(this.$apollo.query({
                        query: gql`${apolloQueries.getModsForGun(store.state.loadoutDetails.secondaryWeapons[0].id)}`
                    }));
                }

                let baseModEquipmentQueries = store.state.loadoutDetails.equipments.map(equipment => {
                    return this.$apollo.query({
                        query: gql`${apolloQueries.getModsForEquipment(equipment.id)}`
                    });
                });
                let allBaseMods = await Promise.all([...baseModWeaponQueries, ...baseModEquipmentQueries]);
                store.commit('setLoadoutDetailModMatrix', {baseMods: allBaseMods});


                return store.state.loadoutDetails;
            },
            getBuildString(build,oc) {
                // Empty array to hold our proper string
                let buildString = [];
                // Equivalent of null default value
                let ocString = '-';
                // Check if our OC object exists. Otherwise, return the dash
                if(oc) {
                    ocString = oc.overclock_index;
                }
                // Let's iterate 0 - 4 to add our mod tier values
                for (let i = 0; i < 5; i++) {
                    if(build[i] != null) {
                        buildString[i] = build[i];
                    } else {
                        buildString[i] = '-';
                    }
                }

                // Add the OC value on the end
                buildString[5] = ocString;

                // Return a string from our array we have been working with
                // Pass this to our equipmentCard
                return buildString.join('');
            },
        },
        mounted: function () {
            // get loadout id from url
            let path = window.location.pathname.split('/');
            let loadoutId = path[path.length - 1];
            this.getLoadoutDetails(loadoutId).then((loadoutDetails) => {
                store.commit('setLoadoutDetailDataReady', {ready: true});
            });
        }
    };
</script>

<style scoped>
    .equipmentSection {
        width: 100%;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-evenly;
        -webkit-overflow-scrolling: touch;
        -ms-overflow-style: -ms-autohiding-scrollbar;
        border-top: 5px solid #fc9e00;
        margin-bottom: 0.5rem;
    }
    .guideAccordion {
        width: 100%;
        border-top: 5px solid #fc9e00;
        margin-bottom: 0.5rem;
    }
    .invertIcon {
        transform: rotate(180deg);
    }


    @media (max-width: 770px) {
        .equipmentCards {
            flex-wrap: wrap;
        }
    }
</style>
