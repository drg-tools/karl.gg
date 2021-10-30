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
            <h4>"Warthog" Auto 210</h4>
        </div>
    </div>
</template>
<!-- todo: differentiate selected primary and secondary weapons visibly hide text from not-selected weapon atm -->
<script>
export default {
    name: 'PrimaryCryo',
    data: function () {
        return {
            gunIcon: this.$store.state.icons.default.D_P2_Cryo_SVG,
        }
    },
    methods: {
        selectEquipment() {
            // Select this particular equipment by sending the store our weapon's data to select
            // Maybe just send it the id, and then let the store pass the data around

            this.$store.dispatch('setSelectedPrimary', "1");
        },
        getIsSelected() {
            return this.$store.getters.getIsSelectedPrimary("1");
        }
    },
};
</script>

