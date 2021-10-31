<template>
    <div class="weaponSelectContainer" v-on:click="selectEquipment()">
        <div class="flexboxWeaponSelect" :class="[getIsSelected() ? 'equipmentActive' : 'equipment']">

            <img
                class="w-24 p-4 filter invert"
                :src="`/assets/${equipment.icon}.svg`" :alt="`${equipment.name} icon`"/>
        </div>

        <div :class="[getIsSelected() ? 'equipmentTextActive' : 'equipmentText']">
            <h4>{{ equipment.name }}</h4>
        </div>
    </div>
</template>

<script>
export default {
    name: "EquipmentSelector",
    props: ['equipment', 'equipmentSlot'],
    // created() {
    //     if(this.setSelected) {
    //         this.selectEquipment();
    //     }
    // },
    methods: {
        selectEquipment() {
            // TODO: Store these differently, rather than interpolating :-/
            this.$store.dispatch(`setSelectedEquipment${this.equipmentSlot}`, this.equipment.id);
        },
        getIsSelected() {
            return this.$store.getters.getIsCurrentlySelectedEquipment(this.equipment.id);
        }
    },
}
</script>

