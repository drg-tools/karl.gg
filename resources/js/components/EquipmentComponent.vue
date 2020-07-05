<template>
    <div class="weaponSelectContainer" v-on:click="selectEquipment()">
        <div class="flexboxWeaponSelect" :class="[getSelected ? 'equipmentActive' : 'equipment']">
            <svg xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 180 90"
                 :class="[getSelected ? 'equipmentIconActive' : 'equipmentIcon']"
                 :height="equipmentNameVisible ? '70%' : '50%'"
                 preserveAspectRatio="xMidYMid meet"
                 v-html="getIconFromPath"></svg>
        </div>
        <div :class="[getSelected ? 'equipmentTextActive' : 'equipmentText']"><!-- v-if="equipmentNameVisible"-->
            <h4 :class="[equipmentNameVisible ? 'largeText' : '']">{{ name }}</h4>
        </div>
    </div>
</template>
<!-- todo: differentiate selected primary and secondary weapons visibly hide text from not-selected weapon atm -->
<script>
    import store from '../store';

    export default {
        name: 'EquipmentComponent',
        props: {
            iconPath: String,
            name: String,
            classId: String,
            equipmentId: String
        },
        computed: {
            getIconFromPath: function () {
                let aPath = this.iconPath.split('.');
                if (aPath.length < 2) {
                    return '';
                }
                return store.state.icons[aPath[0]][aPath[1]];
            },
            getSelected: function () {
                return store.state.loadout.selectedEquipmentId === this.equipmentId;
            },
            equipmentNameVisible: function () {
                if (this.equipmentId.includes('P')) {
                    return store.state.loadout.chosenPrimaryId === this.equipmentId;
                } else if (this.equipmentId.includes('S')) {
                    return store.state.loadout.chosenSecondaryId === this.equipmentId;
                }
                return true;
            }
        },
        methods: {
            selectEquipment() {
                store.commit('selectLoadoutEquipment', {
                    equipmentId: this.equipmentId
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
        background-color: #5b402d;
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
        background-color: #352e1e;
        color: #ada195;
    }

    .equipmentTextActive {
        flex: auto;
        padding-left: 0.5rem;
        padding-right: 1rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background-color: #010103;
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
