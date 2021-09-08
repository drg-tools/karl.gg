<template>
<!-- TODO: Add state for selected on these guys -->
    <!-- TODO: Add components for all weapons/classes -->
    <div class="weaponSelectContainer" v-on:click="selectEquipment()">
        <div class="flexboxWeaponSelect" :class="[getIsSelected() ? 'equipmentActive' : 'equipment']">
            <!-- :class="[getSelected ? 'equipmentActive' : 'equipment']" -->
            <svg xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 180 90"
                 :class="[getIsSelected() ? 'equipmentIconActive' : 'equipmentIcon']"
                 height="50%"
                 preserveAspectRatio="xMidYMid meet"
                 v-html="gunIcon"></svg>
        </div>
        <!-- :class="[getSelected ? 'equipmentIconActive' : 'equipmentIcon']" -->
        <!-- <div :class="[getSelected ? 'equipmentTextActive' : 'equipmentText']"> -->
        <div :class="[getIsSelected() ? 'equipmentTextActive' : 'equipmentText']">
            <h4>CRSPR Flamethrower</h4>
        </div>
    </div>
</template>
<!-- todo: differentiate selected primary and secondary weapons visibly hide text from not-selected weapon atm -->
<script> 
    export default {
        name: 'PrimaryFlamethrower',
        data: function () {
            return {
                gunIcon: this.$store.state.icons.default.D_P1_CRSPR_SVG,
            }
        },
        created() {
            // Set this guy to selected by default
            // Only do this on the first primary of all selected class
            // This just keeps everything working smoothly
            this.$store.dispatch('setSelectedPrimary', "9");
        },
        methods: {
            selectEquipment() {
                // Select this particular equipment by sending the store our weapon's data to select
                // Maybe just send it the id, and then let the store pass the data around
                
                this.$store.dispatch('setSelectedPrimary', "9");
            },
            getIsSelected() {
                return this.$store.getters.getIsSelectedPrimary("9");
            }
        },
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
