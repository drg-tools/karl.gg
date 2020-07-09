<template>
    <div>
        <!-- todo: autoscroll on equipment -->
        <div v-if="selectedClass === 'D'" class="equipmentComponentContainer">
            <EquipmentComponent
                v-for="(equipment, equipmentId) in drillerEquipment"
                :key="equipmentId"
                :iconPath="equipment.icon"
                :name="equipment.name"
                :classId="'D'"
                :equipmentId="equipmentId"
                :data="equipment"
            />
        </div>
        <div v-if="selectedClass === 'E'" class="equipmentComponentContainer">
            <EquipmentComponent
                v-for="(equipment, equipmentId) in engineerEquipment"
                :key="equipmentId"
                :iconPath="equipment.icon"
                :name="equipment.name"
                :classId="'E'"
                :equipmentId="equipmentId"
                :data="equipment"
            />
        </div>
        <div v-if="selectedClass === 'G'" class="equipmentComponentContainer">
            <EquipmentComponent
                v-for="(equipment, equipmentId) in gunnerEquipment"
                :key="equipmentId"
                :iconPath="equipment.icon"
                :name="equipment.name"
                :classId="'G'"
                :equipmentId="equipmentId"
                :data="equipment"
            />
        </div>
        <div v-if="selectedClass === 'S'" class="equipmentComponentContainer">
            <EquipmentComponent
                v-for="(equipment, equipmentId) in scoutEquipment"
                :key="equipmentId"
                :iconPath="equipment.icon"
                :name="equipment.name"
                :classId="'S'"
                :equipmentId="equipmentId"
                :data="equipment"
            />
        </div>
        <div v-if="selectedClass === 'R'" class="equipmentComponentContainer">
            <EquipmentComponent
                v-for="(equipment, equipmentId) in robotEquipment"
                :key="equipmentId"
                :iconPath="equipment.icon"
                :name="equipment.name"
                :classId="'R'"
                :equipmentId="equipmentId"
                :data="equipment"
            />
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
                return store.state.selected.class;
            },
            drillerEquipment() {
                return store.state.tree.D;
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
        methods: {},
        mounted: function () {
        }
    };
</script>

<style>
    .equipmentComponentContainer {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        -ms-overflow-style: -ms-autohiding-scrollbar;
        border-top: 5px solid #fc9e00;
        background-color: #352e1e;
        margin-bottom: 0.5rem;
    }
</style>
