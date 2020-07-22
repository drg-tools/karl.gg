<template>
    <div v-if="dataReady" class="equipmentCards">
        <EquipmentCard v-if="loadoutDetails.primaryWeapons[0]"
                       :classId="loadoutDetails.classId"
                       :equipmentId="loadoutDetails.primaryWeapons[0].id"
                       :equipmentName="loadoutDetails.primaryWeapons[0].name"
                       :icon="loadoutDetails.primaryWeapons[0].icon"
                       :overclock="loadoutDetails.primaryWeapons[0].overclocks[0]"
                       :modMatrix="loadoutDetails.primaryWeapons[0].modMatrix"
                       :build="loadoutDetails.primaryWeapons[0].modString.join('')">
        </EquipmentCard>
        <EquipmentCard v-if="loadoutDetails.secondaryWeapons[0]"
                       :classId="loadoutDetails.classId"
                       :equipmentId="loadoutDetails.secondaryWeapons[0].id"
                       :equipmentName="loadoutDetails.secondaryWeapons[0].name"
                       :icon="loadoutDetails.secondaryWeapons[0].icon"
                       :overclock="loadoutDetails.secondaryWeapons[0].overclocks[0]"
                       :modMatrix="loadoutDetails.secondaryWeapons[0].modMatrix"
                       :build="loadoutDetails.secondaryWeapons[0].modString.join('')">
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
</template>

<script>
    import store from '../store';
    import apolloQueries from '../apolloQueries';
    import gql from 'graphql-tag';
    import EquipmentCard from './EquipmentCard.vue';

    export default {
        name: 'LoadoutPreview',
        components: {
            EquipmentCard
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
                            id: loadoutId
                        }
                    });
                    console.log('getVoteStatus', getVoteStatus);
                    if (getVoteStatus.data.getVoteStatus === 1) {
                        userVotedStatus = true;
                    }
                } catch (e) {
                    console.log('user not signed in, no voted status');
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
                console.log("all base mods for equipment", allBaseMods)
                store.commit('setLoadoutDetailModMatrix', {baseMods: allBaseMods});
                return store.state.loadoutDetails;
            }
        },
        mounted: function () {
            // get loadout id from url
            let path = window.location.pathname.split('/');
            let loadoutId = path[path.length - 1];
            console.log('loadout preview mounted');
            this.getLoadoutDetails(loadoutId).then((loadoutDetails) => {
                store.commit('setLoadoutDetailDataReady', {ready: true});
                console.log('done with loadout details', loadoutDetails);
            });
        }
    };
</script>

<style scoped>
    .equipmentCards {
        width: 100%;
        display: flex;
        justify-content: space-between;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        -ms-overflow-style: -ms-autohiding-scrollbar;
        border-top: 5px solid #fc9e00;
        background: linear-gradient(0deg, rgba(34, 193, 195, 0) 0%, #352e1e 100%);
        /*background-color: #352e1e;*/
        margin-bottom: 0.5rem;
    }
</style>
