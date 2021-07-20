<template>
    <div class="weaponSelectContainer" v-on:click="selectEquipment()">
        <div class="flexboxWeaponSelect equipment">
            <!-- :class="[getSelected ? 'equipmentActive' : 'equipment']" -->
            <svg xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 180 90"
                 class="equipmentIcon"
                 height="50%"
                 preserveAspectRatio="xMidYMid meet"
                 v-html="this.$store.state.icons.primaries.D_P1_CRSPR"></svg>
        </div>
        <!-- :class="[getSelected ? 'equipmentIconActive' : 'equipmentIcon']" -->
        <!-- <div :class="[getSelected ? 'equipmentTextActive' : 'equipmentText']"> -->
        <div class="equipmentText">
            <h4>{{ name }}</h4>
        </div>
    </div>
</template>
<!-- todo: differentiate selected primary and secondary weapons visibly hide text from not-selected weapon atm -->
<script>
    export default {
        name: 'PrimaryFlamethrower',
        props: {
            iconPath: String,
            name: String,
            classId: String,
            character_slot: Number,
            equipmentId: String
        },
        computed: {
            getSelected: function () {
                if (this.character_slot) {
                    return store.state.loadoutCreator.selectedEquipmentId === this.equipmentId
                        && store.state.loadoutCreator.selectedEquipmentType.includes('Weapons');
                } else {
                    return store.state.loadoutCreator.selectedEquipmentId === this.equipmentId
                        && store.state.loadoutCreator.selectedEquipmentType === 'equipments';
                }
            },
            equipmentNameVisible: function () {
                if (this.character_slot === 1) {
                    return store.state.loadoutCreator.chosenPrimaryId === this.equipmentId;
                } else if (this.character_slot === 2) {
                    return store.state.loadoutCreator.chosenSecondaryId === this.equipmentId;
                }
                return true;
            }
        },
        methods: {
            selectEquipment() {
                store.commit('selectLoadoutEquipment', {
                    equipmentId: this.equipmentId,
                    character_slot: this.character_slot
                });
            }
        }
    };
</script>

<style scoped>
    /* todo: proper styles for this stuff, it's messy atm */
    h4 {
        white-space: nowrap;
    }

    .equipmentActive {
        background-color: #fc9e00;
        color: #352e1e;
    }

    .equipment {
        background-color: #111927;
        color: #352e1e;
    }

    .equipmentIcon {
        fill: #ada195;
    }

    .equipmentIconActive {
        fill: #fffbff;
    }

    h4 {
        margin: 0;
        white-space: pre-wrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .largeText {
        font-size: 1.4rem;
    }

    .equipmentText {
        flex: auto;
        padding-left: 0.5rem;
        padding-right: 1rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background-color: #4B5563;
        color: #ada195;
    }

    .equipmentTextActive {
        flex: auto;
        padding-left: 0.5rem;
        padding-right: 1rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background-color: #111927;
        color: #fffbff;
    }

    .weaponSelectContainer {
        flex: auto;
        display: flex;
        cursor: pointer;
    }

    .weaponSelectContainer:hover .equipmentText {
        color: #fffbff;
    }

    .weaponSelectContainer:hover .equipmentIcon {
        fill: #fffbff;
    }

    .flexboxWeaponSelect {
        display: flex;
        align-items: center;
        height: 4rem;
    }

    .flexboxWeaponSelect > svg {
        margin: 0.5rem;
    }
</style>
