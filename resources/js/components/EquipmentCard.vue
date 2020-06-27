<template>
    <div class="equipmentCardContainer" v-on:click="navToBuildView()">
        <h4 class="equipmentTextActive">{{ getEquipmentName }}</h4>
        <div class="flexboxWeaponSelect">
            <svg xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 180 90"
                 class="equipmentIcon"
                 height="70%"
                 preserveAspectRatio="xMidYMid meet"
                 v-html="getIconFromPath"></svg>
        </div>
        <div>
            <div v-for="(tier, tierId) in getModMatrix" :key="tierId" class="modMatrixRow">
                <div v-for="(mod, modId) in tier" :key="modId" class="mod">
                    <svg viewBox="0 0 80 50"
                         height="100%"
                         :class="[mod ? 'modActive' : 'modInactive']">
                        <path
                            d="M 0.3679663,25 13.7826,0.609756 H 66.221625 L 79.636259,25 66.221625,49.390244 H 13.7826 L 0.3679663,25"/>
                    </svg>
                </div>
            </div>

            {{getModMatrix}}
            {{ getOverclockData.type }} {{ getOverclockData.name }}
            <h4>{{ classId }}</h4>
            <h4>{{ equipmentId }}</h4>
        </div>
    </div>
</template>

<script>
    import store from '../store';

    export default {
        name: 'EquipmentCard',
        props: {
            build: String,
            classId: String,
            equipmentId: String
        },
        computed: {
            getIconFromPath: function () {
                let iconPath = store.state.tree[this.classId][this.equipmentId].icon;
                let aPath = iconPath.split('.');
                if (aPath.length < 2) {
                    return '';
                }
                console.log('icons', store.state.icons);
                return store.state.icons[aPath[0]][aPath[1]];
            },
            getEquipmentName: function () {
                console.log('equipment', store.state.tree[this.classId][this.equipmentId]);
                return store.state.tree[this.classId][this.equipmentId].name;
            },
            getModMatrix: function () {
                let charToIndex = ['A', 'B', 'C'];
                let chosenMods = this.build.split('');
                console.log(chosenMods);
                let modMatrix = store.state.tree[this.classId][this.equipmentId].mods.map((tier, tierIndex) => {
                    let rowChar = chosenMods[tierIndex];
                    return tier.map((column, columnIndex) => {
                        return charToIndex[columnIndex] === rowChar;
                    });
                });
                console.log('modMatrix', modMatrix);
                return modMatrix;
            },
            getOverclockData: function () {
                let chosenMods = this.build.split('');
                let chosenOverclock;
                if (chosenMods.length === 6) {
                    chosenOverclock = chosenMods.pop();
                }
                console.log('overclock', store.state.tree[this.classId][this.equipmentId].overclocks[chosenOverclock - 1]);
                return store.state.tree[this.classId][this.equipmentId].overclocks[chosenOverclock - 1];
            },
            isSelected: function () {
                return store.state.selected.class === this.classId;
            }
        },
        methods: {
            navToBuildView() {
                console.log('nav to build view', {classID: this.classId, equipmentId: this.equipmentId});
            }
        }
    };
</script>

<style scoped>
    .equipmentCardContainer {
        display: flex;
        flex-direction: column;
        flex-wrap: nowrap;
        width: 10rem;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        -ms-overflow-style: -ms-autohiding-scrollbar;
        border-top: 5px solid #fc9e00;
        background-color: #352e1e;
        margin-bottom: 0.5rem;
    }

    .flexboxWeaponSelect {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 4rem;
    }

    .flexboxWeaponSelect > svg {
        margin: 0.5rem;
    }

    .modMatrixRow {
        display: flex;
        height: 1rem;
        margin-bottom: 0.3rem;
        /*justify-content: start;*/
        /*align-items: center;*/
    }

    .mod {
        width: 2rem;
        height: 100%;
        display: flex;
        justify-content: space-between;
    }

    .modActive {
        stroke: #fc9e00;
        opacity: 100%;
        fill: #fc9e00;
        stroke-width: 5px;
    }
    .modInactive {
        stroke: #fc9e00;
        opacity: 50%;
        fill: transparent;
        stroke-width: 5px;
    }

</style>
