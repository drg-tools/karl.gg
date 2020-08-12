<template>
    <div class="classSelectContainer">
        <h1>Select class:</h1>
        <div v-on:click="selectClass('D')" class="classSelect"
             :class="[selectedClass === 'D' ? 'classSelectActive' : '']">
            <img src="../assets/img/50px-D_icon-hex.png" class="classIcon"/>
            <h2>Driller</h2>
        </div>
        <div v-on:click="selectClass('E')" class="classSelect"
             :class="[selectedClass === 'E' ? 'classSelectActive' : '']">
            <img src="../assets/img/50px-E_icon-hex.png" class="classIcon"/>
            <h2>Engineer</h2>
        </div>
        <div v-on:click="selectClass('G')" class="classSelect"
             :class="[selectedClass === 'G' ? 'classSelectActive' : '']">
            <img src="../assets/img/50px-G_icon-hex.png" class="classIcon"/>
            <h2>Gunner</h2>
        </div>
        <div v-on:click="selectClass('S')" class="classSelect"
             :class="[selectedClass === 'S' ? 'classSelectActive' : '']">
            <img src="../assets/img/50px-S_icon-hex.png" class="classIcon"/>
            <h2>Scout</h2>
        </div>
    </div>
</template>

<script>
    import store from '../store';
    import apolloQueries from '../apolloQueries';
    import gql from 'graphql-tag';

    const idToChar = ['A', 'B', 'C'];
    const charToModId = {A: 0, B: 1, C: 2};
    const charToId = {
        D: 3,
        E: 1,
        G: 4,
        S: 2
    };

    export default {
        name: 'ClassSelect',
        computed: {
            selectedClass: function () {
                return store.state.loadoutCreator.selectedClassId;
            }
        },
        methods: {
            selectClass: function (classId) {
                store.commit('setDataReady', {ready: false});
                this.getCharacterData(classId).then(response => {
                    /* todo: chosen primary and chosen secondary ids are not correct when switching characters */
                    let chosenPrimaryId = response.primaryWeapons ? response.primaryWeapons[0].id : response.guns[0].id;
                    let chosenSecondaryId = response.secondaryWeapons ? response.secondaryWeapons[0].id : response.guns[2].id;
                    store.commit('selectLoadoutClass', {
                        classId: classId,
                        chosenPrimaryId: chosenPrimaryId,
                        chosenSecondaryId: chosenSecondaryId
                    });
                    store.commit('setDataReady', {ready: true});
                });
            },
            async getCharacterData(classId) {
                let id = charToId[classId];
                if (store.state.loadoutCreator.baseData[classId]) {
                    // base data already there
                    return store.state.loadoutCreator.baseData[classId];
                } else {
                    // fetch base data from graphql
                    const response = await this.$apollo.query({
                        query: gql`${apolloQueries.characterById(id)}`
                    });
                    store.commit('setLoadoutCreatorBaseData', {classId: classId, baseData: response.data.character});
                    return response.data.character;
                }
            },
            async getLoadoutDetails(loadoutId) {
                if (store.state.loadoutDetails.length > 0) {
                    return store.state.loadoutDetails;
                }
                const response = await this.$apollo.query({
                    query: gql`${apolloQueries.loadoutDetails(loadoutId)}`
                });
                store.commit('setLoadoutDetails', {loadout: response.data.loadout});

                let baseModWeaponQueries = [];
                if (store.state.loadoutDetails.primaryWeapons[0]) {
                    baseModWeaponQueries.push(this.$apollo.query({
                        query: gql`${apolloQueries.getModsForGun(store.state.loadoutDetails.primaryWeapons[0].id)}`
                    }));
                }
                if (store.state.loadoutDetails.secondaryWeapons[0]) {
                    baseModWeaponQueries.push(this.$apollo.query({
                        query: gql`${apolloQueries.getModsForGun(store.state.loadoutDetails.secondaryWeapons[0].id)}`
                    }));
                }

                let baseModEquipmentQueries = store.state.loadoutDetails.equipments.map(equipment => {
                    return this.$apollo.query({
                        query: gql`${apolloQueries.getModsForEquipment(equipment.id)}`
                    });
                });
                /* todo: should i keep mod matrix here? */
                let allBaseMods = await Promise.all([...baseModWeaponQueries, ...baseModEquipmentQueries]);
                store.commit('setLoadoutDetailModMatrix', {baseMods: allBaseMods});
                return store.state.loadoutDetails;
            }
        },
        /* just keep it as an example for later, where it makes more sense to load directly from apollo */
        apollo: {
            // Query with parameters
            /*character: {
                query: gql`${apolloQueries.character}`,
                // Reactive parameters
                variables() {
                    console.log('auto apollo query', charToId[this.selectedClass]);
                    // Use vue reactive properties here
                    return {
                        id: charToId[this.selectedClass]
                    };
                }
            }*/
        },
        mounted: function () {
            let path = window.location.pathname.split('/');
            let loadoutId = path[path.length - 1];
            if (path.length === 3 && !isNaN(parseInt(loadoutId))) {
                // if there is a loadout, load it first to see what class it is! then load character data and set selected class
                this.getLoadoutDetails(loadoutId).then((loadoutDetails) => {
                    let chosenPrimaryId = loadoutDetails.primaryWeapons[0] ? loadoutDetails.primaryWeapons[0].id : undefined;
                    let chosenSecondaryId = loadoutDetails.secondaryWeapons[0] ? loadoutDetails.secondaryWeapons[0].id : undefined;

                    this.getCharacterData(loadoutDetails.classId).then(character => {
                        chosenPrimaryId = chosenPrimaryId ? chosenPrimaryId : character.guns[0].id;
                        chosenSecondaryId = chosenSecondaryId ? chosenSecondaryId : character.guns[2].id;

                        store.commit('selectLoadoutClass', {
                            classId: loadoutDetails.classId,
                            chosenPrimaryId: chosenPrimaryId,
                            chosenSecondaryId: chosenSecondaryId
                        });
                        // after char base data is loaded, loop over loadout data and select mods!
                        // todo: put into seperate function
                        let primary = loadoutDetails.primaryWeapons[0];
                        if (primary && primary.mods) {
                            for (let mod of primary.mods) {
                                store.commit('selectLoadoutMods', {
                                    classId: loadoutDetails.classId,
                                    equipmentType: 'primaryWeapons',
                                    equipmentId: primary.id,
                                    tierId: mod.mod_tier - 1,
                                    chosenMod: charToModId[mod.mod_index],
                                    chosenModId: mod.id
                                });
                            }
                        }
                        if (primary && primary.overclocks[0]) {
                            store.commit('selectLoadoutOverclocks', {
                                classId: loadoutDetails.classId,
                                equipmentType: 'primaryWeapons',
                                equipmentId: primary.id,
                                chosenOverclock: primary.overclocks[0].overclock_index - 1, // index from backend starts at one, frontend array starts at 0
                                chosenOverclockId: primary.overclocks[0].id
                            });
                        }
                        let secondary = loadoutDetails.secondaryWeapons[0];
                        if (secondary && secondary.mods) {
                            for (let mod of secondary.mods) {
                                store.commit('selectLoadoutMods', {
                                    classId: loadoutDetails.classId,
                                    equipmentType: 'secondaryWeapons',
                                    equipmentId: secondary.id,
                                    tierId: mod.mod_tier - 1,
                                    chosenMod: charToModId[mod.mod_index],
                                    chosenModId: mod.id
                                });
                            }
                        }
                        if (secondary && secondary.overclocks[0]) {
                            store.commit('selectLoadoutOverclocks', {
                                classId: loadoutDetails.classId,
                                equipmentType: 'secondaryWeapons',
                                equipmentId: secondary.id,
                                chosenOverclock: secondary.overclocks[0].overclock_index - 1, // index from backend starts at one, frontend array starts at 0
                                chosenOverclockId: secondary.overclocks[0].id
                            });
                        }
                        if (loadoutDetails.equipments && loadoutDetails.equipments.length > 0) {
                            for (let equipment of loadoutDetails.equipments) {
                                for (let mod of equipment.mods) {
                                    store.commit('selectLoadoutMods', {
                                        classId: loadoutDetails.classId,
                                        equipmentType: 'equipments',
                                        equipmentId: equipment.id,
                                        tierId: mod.mod_tier - 1,
                                        chosenMod: charToModId[mod.mod_index],
                                        chosenModId: mod.id
                                    });
                                }
                            }
                        }
                        store.commit('setDataReady', {ready: true});
                    });
                });
            } else {
                if (loadoutId === 'D' || loadoutId === 'E' || loadoutId === 'G' || loadoutId === 'S') {
                    // get base data for class
                    let characterId = loadoutId;
                    this.getCharacterData(characterId).then(character => {
                        store.commit('selectLoadoutClass', {
                            classId: characterId,
                            chosenPrimaryId: character.guns[0].id,
                            chosenSecondaryId: character.guns[2].id
                        });
                        store.commit('setDataReady', {ready: true});
                    });
                } else if (loadoutId === 'R') {
                    // random loadout
                    let characterIds = ['D', 'E', 'G', 'S'];
                    let randomId = Math.floor(Math.random() * Math.floor(4));
                    let classId = characterIds[randomId];
                    let randomPrimary = Math.floor(Math.random() * Math.floor(2));
                    let randomSecondary = Math.floor(Math.random() * Math.floor(2)) + 2;
                    this.getCharacterData(classId).then(character => {
                        store.commit('selectLoadoutClass', {
                            classId: classId,
                            chosenPrimaryId: character.guns[randomPrimary].id,
                            chosenSecondaryId: character.guns[randomSecondary].id
                        });

                        const selectRandomMods = (equipment, equipmentType) => {
                            for (let tierId in equipment.mods) {
                                let tier = equipment.mods[tierId];
                                let randomModId = Math.floor(Math.random() * Math.floor(tier.length));
                                let randomMod = tier[randomModId];
                                store.commit('selectLoadoutMods', {
                                    classId: classId,
                                    equipmentType: equipmentType,
                                    equipmentId: equipment.id,
                                    tierId: tierId,
                                    chosenMod: randomModId,
                                    chosenModId: randomMod.id
                                });
                            }
                            if (equipment.overclocks) {
                                let randomOverclockId = Math.floor(Math.random() * Math.floor(equipment.overclocks.length));
                                let randomOverclock = equipment.overclocks[randomOverclockId];
                                store.commit('selectLoadoutOverclocks', {
                                    classId: classId,
                                    equipmentType: equipmentType,
                                    equipmentId: equipment.id,
                                    chosenOverclock: randomOverclockId,
                                    chosenOverclockId: randomOverclock.id
                                });
                            }
                        };
                        selectRandomMods(character.guns[randomPrimary], 'primaryWeapons');
                        selectRandomMods(character.guns[randomSecondary], 'secondaryWeapons');
                        for (let equipment of character.equipments) {
                            selectRandomMods(equipment, 'equipments');
                        }
                        store.commit('setDataReady', {ready: true});
                    });
                } else {
                    // get character data directly
                    this.getCharacterData(this.selectedClass).then(character => {
                        store.commit('setDataReady', {ready: true});
                    });
                }
            }
        }
    };
</script>

<style scoped>
    .classSelectContainer {
        border-top: 5px solid #fc9e00;
        display: flex;
        flex-wrap: wrap;
    }

    .classSelectContainer > h1 {
        margin: 0;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        padding-right: 2rem
    }

    .classSelect {
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .classSelect:hover {
        background: #fc9e00;
    }

    .classSelect > h2 {
        color: #ADA195;
        padding-left: 1rem;
        padding-right: 2rem;
        margin-block-start: 0;
        margin-block-end: 0;
        margin-inline-start: 0;
        margin-inline-end: 0;
    }

    .classSelect > img {
        opacity: 0.4;
    }

    .classSelectActive > h2 {
        color: #ffffff;
    }

    .classSelectActive > img {
        opacity: 1;
    }

    @media (max-width: 770px) {
        .classSelectContainer h1 {
            width: 100%;
        }
        .classSelectContainer .classSelect {
            width: 50%;
        }
        .classSelectContainer .classSelect .classIcon {
            width: 40px;
            height: 40px;
        }
    }
</style>
