<template>
<!-- TODO: Add state for selected on these guys -->
    <!-- TODO: Add components for all weapons/classes -->
    <div class="weaponSelectContainer" v-on:click="selectEquipment()">
        <div class="flexboxWeaponSelect" :class="[getIsSelected() ? 'equipmentActive' : 'equipment']">
            <!-- :class="[getSelected ? 'equipmentActive' : 'equipment']" -->
            <svg xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 180 90"
                 :class="[getIsSelected() ? 'equipmentIconActive' : 'equipmentIcon']"
                 height="50%"
                 preserveAspectRatio="xMidYMid meet"
                 v-html="gunIcon"></svg>
        </div>
        <!-- :class="[getSelected ? 'equipmentIconActive' : 'equipmentIcon']" -->
        <!-- <div :class="[getSelected ? 'equipmentTextActive' : 'equipmentText']"> -->
        <div :class="[getIsSelected() ? 'equipmentTextActive' : 'equipmentText']">
            <h4>CRSPR Flamethrower</h4>
        </div>
    </div>
</template>
<!-- todo: differentiate selected primary and secondary weapons visibly hide text from not-selected weapon atm -->
<script> 
    export default {
        name: 'PrimaryFlamethrower',
        data: function () {
            return {
                gunIcon: this.$store.state.icons.default.D_P1_CRSPR_SVG,
            }
        },
        created() {
            // Set this guy to selected by default
            // Only do this on the first primary of all selected class
            // This just keeps everything working smoothly
            this.$store.dispatch('setSelectedPrimary', "9");
        },
        methods: {
            selectEquipment() {
                // Select this particular equipment by sending the store our weapon's data to select
                // Maybe just send it the id, and then let the store pass the data around
                
                this.$store.dispatch('setSelectedPrimary', "9");
            },
            getIsSelected() {
                return this.$store.getters.getIsSelectedPrimary("9");
            }
        },
    };
</script>

