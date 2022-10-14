<template>
    <div class="weaponSelectContainer" v-on:click="selectEquipment()">
        <div class="flexboxWeaponSelect" :class="[getIsSelected() ? 'equipmentActive' : 'equipment']">

            <img
                class="w-24 p-4 filter invert"
                :src="`/assets/${throwable.icon}.svg`" :alt="`${throwable.name} icon`"/>
        </div>

        <div :class="[{ equipmentTextActive: getIsSelected() }, 'equipmentText']">
            <h4>{{ throwable.name }}</h4>
        </div>
    </div>
</template>

<!-- TODO: I am certain that icon won't work, need to check on that. Maybe emulate overclock icon instead of that img I have. -->

<script>
export default {
    name: "PrimaryWeaponSelector",
    props: ['throwable', 'setSelected'],
    created() {
        if (this.setSelected) {
            this.selectEquipment();
        }
    },
    methods: {
        selectEquipment() {
            this.$store.dispatch('setSelectedThrowableId', this.throwable.id);
        },
        getIsSelected() {
            return this.$store.getters.getIsSelectedThrowableId(this.throwable.id);
        },
        getIconPath(iconName) {
            return this.$store.getters.getIconByName(iconName);
        },
    },
}
</script>

