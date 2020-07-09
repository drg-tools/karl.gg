<template>
    <div class="equipmentCards">
        <EquipmentCard :classId="'D'" :equipmentId="'P2'" :build="'ACBCA3'"></EquipmentCard>
        <EquipmentCard :classId="'D'" :equipmentId="'S1'" :build="'AACBA6'"></EquipmentCard>
        <EquipmentCard :classId="'D'" :equipmentId="'E1'" :build="'AAAB'"></EquipmentCard>
        <EquipmentCard :classId="'D'" :equipmentId="'E2'" :build="'BBAB'"></EquipmentCard>
        <EquipmentCard :classId="'D'" :equipmentId="'E3'" :build="'BAAC'"></EquipmentCard>
        <EquipmentCard :classId="'D'" :equipmentId="'E3'" :build="'BAAC'"></EquipmentCard>
    </div>
</template>

<script>
    import EquipmentCard from './EquipmentCard.vue';

    export default {
        name: 'LoadoutPreview',
        components: {
            EquipmentCard
        },
        computed: {},
        methods: {},
        mounted: function () {
            console.log('loadout preview mounted');
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
        background: linear-gradient(0deg, rgba(34,193,195,0) 0%, #352e1e 100%);
        /*background-color: #352e1e;*/
        margin-bottom: 0.5rem;
    }
</style>
