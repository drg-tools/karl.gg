<template>
    <div class="weaponSelectContainer" v-on:click="selectEquipment()">
        <div class="flexboxWeaponSelect" :class="[getIsSelected() ? 'equipmentActive' : 'equipment']">

            <img
                class="w-24 p-4 filter invert"
                :src="`/assets/${equipment.icon}.svg`" :alt="`${equipment.name} icon`"/>
        </div>

        <div :class="[{ equipmentTextActive: getIsSelected() }, 'equipmentText']">
            <h4>{{ equipment.name }}</h4>
        </div>
    </div>
</template>

<script>
export default {
    name: "EquipmentSelector",
    props: ['equipment', 'equipmentSlot', 'setSelected'],
    created() {
        if(this.setSelected) {
            this.selectEquipment();
        }
    },
    methods: {
        selectEquipment() {
            this.$store.dispatch(`setSelectedEquipment`, this.equipment.id);
        },
        getIsSelected() {
            return this.$store.getters.getIsSelectedEquipment(this.equipment.id);
        }
    },
}
</script>

