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
                 v-html="gunIcon"></svg>
        </div>
       
        <div :class="[getIsSelected() ? 'equipmentTextActive' : 'equipmentText']">
            <h4>Reinforced Power Drills</h4>
        </div>
    </div>
</template>
<!-- todo: differentiate selected primary and secondary weapons visibly hide text from not-selected weapon atm -->
<script> 
    export default {
        name: 'SecondarySubata',
        data: function () {
            return {
                gunIcon: this.$store.state.icons.default.D_E_Drill_SVG,
            }
        },
        created() {
            // Set this guy to selected by default
            // Only do this on the first secondary of all selected class
            // This just keeps everything working smoothly
            this.$store.dispatch('setSelectedSecondary', "11");
        },
        methods: {
            selectEquipment() {
                // Select this particular equipment by sending the store our weapon's data to select
                // Maybe just send it the id, and then let the store pass the data around
                
                this.$store.dispatch('setSelectedSecondary', "11");
            },
            getIsSelected() {
                return this.$store.getters.getIsSelectedSecondary("11");
            }
        },
    };
</script>

