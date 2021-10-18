<template>
<!-- TODO: Add state for selected on these guys -->
    <!-- TODO: Add components for all weapons/classes -->
    <div class="weaponSelectContainer" v-on:click="selectEquipment()">
        <div class="flexboxWeaponSelect" :class="[getIsSelected() ? 'equipmentActive' : 'equipment']">
           
            <svg xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 180 90"
                 :class="[getIsSelected() ? 'equipmentIconActive' : 'equipmentIcon']"
                 height="50%"
                 preserveAspectRatio="xMidYMid meet"
                 v-html="equipmentIcon"></svg>
        </div>
       
        <div :class="[getIsSelected() ? 'equipmentTextActive' : 'equipmentText']">
            <h4>Reinforced Power Drills</h4>
        </div>
    </div>
</template>
<script> 
    export default {
        name: 'EquipmentPowerDrill',
        data: function () {
            return {
                equipmentIcon: this.$store.state.icons.default.D_E_Drill_SVG,
            }
        },
        created() {
            // Set this guy to selected by default
            // Only do this on the first secondary of all selected class
            // This just keeps everything working smoothly
            this.$store.dispatch('setSelectedEquipment1', "1");
        },
        methods: {
            selectEquipment() {
                // Select this particular equipment by sending the store our weapon's data to select
                // Maybe just send it the id, and then let the store pass the data around
                
                this.$store.dispatch('setSelectedEquipment1', "1");
            },
            getIsSelected() {
                return this.$store.getters.getIsCurrentlySelectedEquipment("1");
            }
        },
    };
</script>

