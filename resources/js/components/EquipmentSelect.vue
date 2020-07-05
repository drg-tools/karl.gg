<template>
    <div>
        <!-- driller -->
        <div v-if="selectedClass === 'D'" class="equipmentSelectContainer">
            <div class="primariesContainer">
                <EquipmentComponent
                    v-for="(equipment, equipmentId) in classPrimaries('D')"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :classId="'D'"
                    :equipmentId="equipmentId"
                />
            </div>
            <div class="secondariesContainer">
                <EquipmentComponent
                    v-for="(equipment, equipmentId) in classSecondaries('D')"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :classId="'D'"
                    :equipmentId="equipmentId"
                />
            </div>
            <div class="equipmentContainer">
                <EquipmentComponent
                    v-for="(equipment, equipmentId) in classEquipments('D')"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :classId="'D'"
                    :equipmentId="equipmentId"
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
                    :classId="'E'"
                    :equipmentId="equipmentId"
                />
            </div>
            <div class="secondariesContainer">
                <EquipmentComponent
                    v-for="(equipment, equipmentId) in classSecondaries('E')"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :classId="'E'"
                    :equipmentId="equipmentId"
                />
            </div>
            <div class="equipmentContainer">
                <EquipmentComponent
                    v-for="(equipment, equipmentId) in classEquipments('E')"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :classId="'E'"
                    :equipmentId="equipmentId"
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
                    :classId="'G'"
                    :equipmentId="equipmentId"
                />
            </div>
            <div class="secondariesContainer">
                <EquipmentComponent
                    v-for="(equipment, equipmentId) in classSecondaries('G')"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :classId="'G'"
                    :equipmentId="equipmentId"
                />
            </div>
            <div class="equipmentContainer">
                <EquipmentComponent
                    v-for="(equipment, equipmentId) in classEquipments('G')"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :classId="'G'"
                    :equipmentId="equipmentId"
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
                    :classId="'S'"
                    :equipmentId="equipmentId"
                />
            </div>
            <div class="secondariesContainer">
                <EquipmentComponent
                    v-for="(equipment, equipmentId) in classSecondaries('S')"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :classId="'S'"
                    :equipmentId="equipmentId"
                />
            </div>
            <div class="equipmentContainer">
                <EquipmentComponent
                    v-for="(equipment, equipmentId) in classEquipments('S')"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :classId="'S'"
                    :equipmentId="equipmentId"
                />
            </div>
        </div>
    </div>
</template>

<script>
    import EquipmentComponent from './EquipmentComponent.vue';
    import store from '../store';

    export default {
        name: 'EquipmentSelect',
        components: {
            EquipmentComponent
        },
        computed: {
            selectedClass() {
                return store.state.loadout.selectedClassId;
            },
            classIds() {
                return ["D", "E", "G", "S"]
            },

            engineerEquipment() {
                return store.state.tree.E;
            },
            gunnerEquipment() {
                return store.state.tree.G;
            },
            scoutEquipment() {
                return store.state.tree.S;
            },
            robotEquipment() {
                return store.state.tree.R;
            }
        },
        methods: {
            classPrimaries(classId) {
                let tree =  store.state.tree[classId];
                return {P1: tree.P1, P2: tree.P2}
            },
            classSecondaries(classId) {
                let tree =  store.state.tree[classId];
                return {S1: tree.S1, S2: tree.S2}
            },
            classEquipments(classId) {
                /* todo: don't hard code this stuff */
                let tree =  store.state.tree[classId];
                return {E1: tree.E1, E2: tree.E2, E3: tree.E3};
            },
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

    .secondariesContainer {
        display: flex;
        width: 48%;
        border-top: 5px solid #fc9e00;
        background-color: #352e1e;
        margin-bottom: 0.5rem;
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
