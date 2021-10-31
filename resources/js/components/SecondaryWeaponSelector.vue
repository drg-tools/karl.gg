<template>
    <div class="weaponSelectContainer" v-on:click="selectEquipment()">
        <div class="flexboxWeaponSelect" :class="[getIsSelected() ? 'equipmentActive' : 'equipment']">

            <img
                class="w-24 p-4 filter invert"
                :src="`/assets/${gun.image}.svg`" :alt="`${gun.name} icon`"/>
        </div>

        <div :class="[getIsSelected() ? 'equipmentTextActive' : 'equipmentText']">
            <h4>{{ gun.name }}</h4>
        </div>
    </div>
</template>

<script>
export default {
    name: "SecondaryWeaponSelector",
    props: ['gun', 'setSelected'],
    created() {
        if(this.setSelected) {
            this.selectEquipment();
        }
    },
    methods: {
        selectEquipment() {
            this.$store.dispatch('setSelectedSecondary', this.gun.id);
        },
        getIsSelected() {
            return this.$store.getters.getIsSelectedSecondary(this.gun.id);
        }
    },
}
</script>

