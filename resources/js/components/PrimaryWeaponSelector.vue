<template>
    <div class="weaponSelectContainer" v-on:click="selectEquipment()">
        <div class="flexboxWeaponSelect" :class="[getIsSelected() ? 'equipmentActive' : 'equipment']">

            <img
                class="w-24 p-4 filter invert"
                :src="`/assets/${gun.image}.svg`" :alt="`${gun.name} icon`"/>
        </div>

        <div :class="[{ equipmentTextActive: getIsSelected() }, 'equipmentText']">
            <h4>{{ gun.name }}</h4>
        </div>
    </div>
</template>

<script>
export default {
    name: "PrimaryWeaponSelector",
    props: ['gun', 'setSelected'],
    created() {
        if (this.setSelected) {
            this.selectEquipment();
        }
    },
    methods: {
        selectEquipment() {
            this.$store.dispatch('setSelectedPrimary', this.gun.id);
        },
        getIsSelected() {
            return this.$store.getters.getIsSelectedPrimary(this.gun.id);
        }
    },
}
</script>

