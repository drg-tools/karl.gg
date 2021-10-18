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
            <h4>Satchel Charge</h4>
        </div>
    </div>
</template>
<!-- todo: differentiate selected primary and secondary weapons visibly hide text from not-selected weapon atm -->
<script> 
    export default {
        name: 'EquipmentSatchelCharge',
        data: function () {
            return {
                equipmentIcon: this.$store.state.icons.default.D_E_Satchel_SVG,
            }
        },
        methods: {
            selectEquipment() {
                // Select this particular equipment by sending the store our weapon's data to select
                // Maybe just send it the id, and then let the store pass the data around
                
                this.$store.dispatch('setSelectedEquipment2', "2");
            },
            getIsSelected() {
                return this.$store.getters.getIsCurrentlySelectedEquipment("2");
            }
        },
    };
</script>

