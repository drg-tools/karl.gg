<template>
    <div v-if="!dataReady" class="loadingIndicator">
        <img src="../assets/img/karl-spinner-free.gif" alt="loading...">
    </div>
    <div v-else-if="dataReady">
        <!-- driller -->
        <div v-if="selectedClass === 'D'" class="equipmentSelectContainer">
            <div class="primariesContainer">
                <!-- todo: icon missing from equipment backend.. get from store -->
                <EquipmentComponent
                    v-for="(equipment, equipmentId) in classPrimaries('D')"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :character_slot="equipment.character_slot"
                    :classId="'D'"
                    :equipmentId="equipment.id"
                />
            </div>
            <div class="secondariesContainer">
                <EquipmentComponent
                    v-for="(equipment, equipmentId) in classSecondaries('D')"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :character_slot="equipment.character_slot"
                    :classId="'D'"
                    :equipmentId="equipment.id"
                />
            </div>
            <div class="equipmentContainer">
                <EquipmentComponent
                    v-for="(equipment, equipmentId) in classEquipments('D')"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :character_slot="equipment.character_slot"
                    :classId="'D'"
                    :equipmentId="equipment.id"
                />
            </div>
        </div>
        <!-- engineer -->
        <div v-if="selectedClass === 'E'" class="equipmentSelectContainer">
            <div class="primariesContainer">
                <EquipmentComponent
                    v-for="(equipment, equipmentId) in classPrimaries('E')"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :character_slot="equipment.character_slot"
                    :classId="'E'"
                    :equipmentId="equipment.id"
                />
            </div>
            <div class="secondariesContainer">
                <EquipmentComponent
                    v-for="(equipment, equipmentId) in classSecondaries('E')"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :character_slot="equipment.character_slot"
                    :classId="'E'"
                    :equipmentId="equipment.id"
                />
            </div>
            <div class="equipmentContainer">
                <EquipmentComponent
                    v-for="(equipment, equipmentId) in classEquipments('E')"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :character_slot="equipment.character_slot"
                    :classId="'E'"
                    :equipmentId="equipment.id"
                />
            </div>
        </div>
        <!-- gunner -->
        <div v-if="selectedClass === 'G'" class="equipmentSelectContainer">
            <div class="primariesContainer">
                <EquipmentComponent
                    v-for="(equipment, equipmentId) in classPrimaries('G')"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :character_slot="equipment.character_slot"
                    :classId="'G'"
                    :equipmentId="equipment.id"
                />
            </div>
            <div class="secondariesContainer">
                <EquipmentComponent
                    v-for="(equipment, equipmentId) in classSecondaries('G')"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :character_slot="equipment.character_slot"
                    :classId="'G'"
                    :equipmentId="equipment.id"
                />
            </div>
            <div class="equipmentContainer">
                <EquipmentComponent
                    v-for="(equipment, equipmentId) in classEquipments('G')"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :character_slot="equipment.character_slot"
                    :classId="'G'"
                    :equipmentId="equipment.id"
                />
            </div>
        </div>
        <!-- scout -->
        <div v-if="selectedClass === 'S'" class="equipmentSelectContainer">
            <div class="primariesContainer">
                <EquipmentComponent
                    v-for="(equipment, equipmentId) in classPrimaries('S')"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :character_slot="equipment.character_slot"
                    :classId="'S'"
                    :equipmentId="equipment.id"
                />
            </div>
            <div class="secondariesContainer">
                <EquipmentComponent
                    v-for="(equipment, equipmentId) in classSecondaries('S')"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :character_slot="equipment.character_slot"
                    :classId="'S'"
                    :equipmentId="equipment.id"
                />
            </div>
            <div class="equipmentContainer">
                <EquipmentComponent
                    v-for="(equipment, equipmentId) in classEquipments('S')"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :character_slot="equipment.character_slot"
                    :classId="'S'"
                    :equipmentId="equipment.id"
                />
            </div>
        </div>
    </div>
</template>

<script>
    import EquipmentComponent from './EquipmentComponent.vue';
    import store from '../store';
    import apolloQueries from '../apolloQueries';
    import gql from 'graphql-tag';

    const charToId = {
        D: 3,
        E: 1,
        G: 4,
        S: 2
    };

    export default {
        name: 'EquipmentSelect',
        components: {
            EquipmentComponent
        },
        computed: {
            selectedClass() {
                return store.state.loadoutCreator.selectedClassId;
            },
            classIds() {
                return ['D', 'E', 'G', 'S'];
            },

            dataReady() {
                return store.state.loadoutCreator.dataReady;
            }

        },
        methods: {
            classPrimaries(classId) {
                return store.getters.getPrimariesByClass(classId);
            },
            classSecondaries(classId) {
                return store.getters.getSecondariesByClass(classId);
            },
            classEquipments(classId) {
                return store.getters.getEquipmentsByClass(classId);
            }
        },
        apollo: {
            // Query with parameters
            /*character: {
                query: gql`${apolloQueries.character}`,
                // Reactive parameters
                variables() {
                    console.log('auto apollo query', charToId[this.selectedClass]);
                    // Use vue reactive properties here
                    return {
                        id: charToId[this.selectedClass]
                    };
                }
            }*/
        },
        mounted: function () {
        }
    };
</script>

<style scoped>
    .equipmentSelectContainer {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .primariesContainer {
        display: flex;
        width: 48%;
        border-top: 5px solid #fc9e00;
        background-color: #352e1e;
        margin-bottom: 0.5rem;
    }
    @media (max-width: 770px) {
        .primariesContainer {
            width: 100%;
        }
    }

    .secondariesContainer {
        display: flex;
        width: 48%;
        border-top: 5px solid #fc9e00;
        background-color: #352e1e;
        margin-bottom: 0.5rem;
    }
    @media (max-width: 770px) {
        .secondariesContainer {
            width: 100%;
        }
    }

    .equipmentContainer {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        -ms-overflow-style: -ms-autohiding-scrollbar;
        width: 100%;
        border-bottom: 5px solid #fc9e00;
        background-color: #352e1e;
        margin-bottom: 0.5rem;
    }
</style>
