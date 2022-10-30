<template>
    <div class="weaponSelectContainer" v-on:click="selectThrowable()">
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

<script>
export default {
    name: "ThrowableSelector",
    props: ['throwable', 'setSelected'],
    created() {
        if (this.setSelected) {
            this.selectThrowable();
        }
    },
    methods: {
        selectThrowable() {
            this.$store.dispatch('setSelectedThrowable', this.throwable.id);
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

