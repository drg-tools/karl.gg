import Vue from 'vue';
import Vuex, { Store } from 'vuex';
import VuexPersist from 'vuex-persist';
import gql from 'graphql-tag';
import apolloQueries from './apolloQueries';
import { Apollo } from './apollo';
import * as IconList from './IconsList';
import { over, sortedLastIndexOf } from 'lodash';



Vue.use(Vuex);

// Persist storage while you still browse with us
// TODO: Clear items when a new loadout is submitted
const vuexPersist = new VuexPersist({
    key: 'karl-gg',
    storage: window.sessionStorage
})

/** 
 * For Reference:
 *      1 = Engineer
 *      2 = Scout
 *      3 = Driller
 *      4 = Gunner
 * For Characters. We leverage everything off that
 */

/**
 * Note about state logic:
 *  You should not have nested state objects
 *      It won't work anyways, but it's an anti-pattern as well
 *  If you are thinking you need a nested state placeholder, find a way to split it
 *  State objects should be single-layer
 *      You can store objects within state, but each state placeholder should be single-layer
 */


export default new Vuex.Store({
    // plugins: [vuexPersist.plugin], // Disable when debugging locally
    state: {
        loadingStatus: false,
        selectedClass: '',
        loadoutName: '', // TODO: In the component Debounce on this
        loadoutDescription: '', // TODO: In the component Debounce on this
        loadoutClassData: '', // Use this as source of truth, only call this. Save ID's in other manipulators
        icons: IconList,
        selectedPrimary: '',
        selectedPrimaryMods: [],
        selectedPrimaryOverclock: '',
        selectedSecondary: '',
        selectedSecondaryMods: [],
        selectedSecondaryOverclock: '',
        // TODO: Need to potentially rework the selected equipment methodology
        //          This won't work with how the frontend is doing it
        //          Plus, we need to be able to save multiple equipments at the same time
        //          The existing methodology only allows for one at a time, which makes sense for primary and secondary
        
        
        // Maybe do a 'currentlySelectedEquipment' and and then manage the rest like this 
        currentlySelectedEquipment: '',
        selectedEquipment1: '', 
        selectedEquipment1Mods: [],
        selectedEquipment2: '',
        selectedEquipment2Mods: [],
        selectedEquipment3: '',
        selectedEquipment3Mods: [],
    },
    mutations: {
        setLoadingStatus(state, newLoadingStatus) {
            state.loadingStatus = newLoadingStatus;
        },
        setSelectedClass(state, newValue) {
            state.selectedClass = newValue
        },
        clearSelectedClass(state) {
            state.selectedClass = ''
        },
        setLoadoutName(state, newValue) {
            state.loadoutName = newValue
        },
        clearLoadoutName(state) {
            state.LoadoutName = ''
        },
        setLoadoutDescription(state, newValue) {
            state.loadoutDescription = newValue
        },
        clearLoadoutDescription(state) {
            state.LoadoutDescription = ''
        },
        setSelectedClass(state, newValue) {
            state.selectedClass = newValue
        },
        setloadoutClassData(state, classData) {
            state.loadoutClassData = classData
        },
        clearloadoutClassData(state) {
            state.loadoutClassData = ''
        },
        setSelectedPrimary(state, newValue) {
            state.selectedPrimary = newValue
        },
        clearSelectedPrimary(state) {
            state.selectedPrimary = ''
        },
        setSelectedPrimaryMod(state, newValue) {
            state.selectedPrimaryMods.push(newValue)
        },
        clearSelectedPrimaryMod(state, modTier) {
            state.selectedPrimaryMods = state.selectedPrimaryMods.filter(mod => mod.selectedModTier !== modTier)
        },
        clearAllSelectedPrimaryMods(state) {
            state.selectedPrimaryMods = []
        },
        setSelectedPrimaryOverclock(state, newValue) {
            state.selectedPrimaryOverclock = newValue
        },
        clearSelectedPrimaryOverclock(state) {
            state.selectedPrimaryOverclock = ''
        },
        setSelectedSecondary(state, newValue) {
            state.selectedSecondary = newValue
        },
        clearSelectedSecondary(state) {
            state.selectedSecondary = ''
        },
        setCurrentlySelectedEquipment(state, newValue) {
            state.currentlySelectedEquipment = newValue
        },
        clearCurrentlySelectedEquipment(state) {
            state.currentlySelectedEquipment = ''
        },
        setSelectedSecondaryMod(state, newValue) {
            state.selectedSecondaryMods.push(newValue)
        },
        clearSelectedSecondaryMod(state, modTier) {
            state.selectedSecondaryMods = state.selectedSecondaryMods.filter(mod => mod.selectedModTier !== modTier)
        },
        clearAllSelectedSecondaryMods(state) {
            state.selectedSecondaryMods = []
        },
        setSelectedSecondaryOverclock(state, newValue) {
            state.selectedSecondaryOverclock = newValue
        },
        clearSelectedSecondaryOverclock(state) {
            state.selectedSecondaryOverclock = ''
        },
        setSelectedEquipment1(state, newValue) {
            state.selectedEquipment1 = newValue
        },
        clearSelectedEquipment1(state) {
            state.selectedEquipment1 = ''
        },
        setSelectedEquipment1Mod(state, newValue) {
            state.selectedEquipment1Mods.push(newValue)
        },
        clearSelectedEquipment1Mod(state, modTier) {
            state.selectedEquipment1Mods = state.selectedEquipment1Mods.filter(mod => mod.selectedModTier !== modTier)
        },
        setSelectedEquipment2(state, newValue) {
            state.selectedEquipment2 = newValue
        },
        clearSelectedEquipment2(state) {
            state.selectedEquipment2 = ''
        },
        setSelectedEquipment2Mod(state, newValue) {
            state.selectedEquipment2Mods.push(newValue)
        },
        clearSelectedEquipment2Mod(state, modTier) {
            state.selectedEquipment1Mods = state.selectedEquipment1Mods.filter(mod => mod.selectedModTier !== modTier)
        },
        setSelectedEquipment3(state, newValue) {
            state.selectedEquipment3 = newValue
        },
        clearSelectedEquipment3(state) {
            state.selectedEquipment3 = ''
        },
        setSelectedEquipment3Mod(state, newValue) {
            state.selectedEquipment3Mods.push(newValue)
        },
        clearSelectedEquipment3Mod(state, modTier) {
            state.selectedEquipment3Mods = state.selectedEquipment1Mods.filter(mod => mod.selectedModTier !== modTier)
        },
        
    },
    actions: {
        getClassData({ state }, classId) {
            // Parsing a response 
            let selectedClassId = classId;
            return Apollo.query({
                query: gql`${apolloQueries.character(selectedClassId)}`
            }).then(result => {
                return result.data.character;
            }).catch(err => {
                throw err;
            });
        },
        hydrateClassData({ commit, dispatch }, newClassId) {
            commit('setLoadingStatus', true)
            let classData = dispatch('getClassData', newClassId).then(result => {
                // Use our data response to hydrate all needed class data
                // Commit this directly to store, called each time you select a class
                commit('setloadoutClassData', result);
                commit('setLoadingStatus', false)
            }).catch(err => {
                throw err;
            });
        },
        setSelectedClass({ commit, dispatch }, newClassIdInput) {
            // clear the previously selected class
            commit('clearloadoutClassData')
            // also clear the previously selected data
            commit('clearSelectedPrimary')
           // TODO: Clear all previously selected data on class swap
           // TODO: add a speed bump on the class selection if you have class data


            // TODO: Clear button to allow you to clear the whole current state


            // dispatch an action which will commit our new class data to store
            dispatch('hydrateClassData', newClassIdInput);

            // Set newly selected class only after we have:
            // 1. Deleted old class data
            // 2. Hydrated new class data
            commit('setSelectedClass', newClassIdInput)
        },
        setSelectedPrimary({ commit }, newLoadoutItem) {

            commit('clearSelectedPrimary')
            commit('clearAllSelectedPrimaryMods')
            commit('clearSelectedPrimaryOverclock')
            commit('setSelectedPrimary', newLoadoutItem)

        },
        setSelectedPrimaryMod({ commit, state }, selectedModObject) {

            let currentTierSelection = state.selectedPrimaryMods.filter(mod => mod.selectedModTier === selectedModObject.selectedModTier)
            let sameSelection = false;
            if (state.selectedPrimaryMods.length != 0) {
                // We have selected mods
                if (currentTierSelection.length != 0) {

                    commit('clearSelectedPrimaryMod', selectedModObject.selectedModTier)
                    if (currentTierSelection[0].selectedModId == selectedModObject.selectedModId) {
                        sameSelection = true
                    }
                }

            }
            if (!sameSelection) {
                commit('setSelectedPrimaryMod', {
                    selectedModId: selectedModObject.selectedModId,
                    selectedModTier: selectedModObject.selectedModTier
                })
            }

        },
        setSelectedSecondary({ commit }, newLoadoutItem) {

            commit('clearSelectedSecondary')
            commit('clearAllSelectedSecondaryMods')
            commit('clearSelectedSecondaryOverclock')
            commit('setSelectedSecondary', newLoadoutItem)

        },
        setSelectedSecondaryMod({ commit, state }, selectedModObject) {

            let currentTierSelection = state.selectedSecondaryMods.filter(mod => mod.selectedModTier === selectedModObject.selectedModTier)
            let sameSelection = false;
            if (state.selectedSecondaryMods.length != 0) {
                // We have selected mods
                if (currentTierSelection.length != 0) {

                    commit('clearSelectedSecondaryMod', selectedModObject.selectedModTier)
                    if (currentTierSelection[0].selectedModId == selectedModObject.selectedModId) {
                        sameSelection = true
                    }
                }

            }
            if (!sameSelection) {
                commit('setSelectedSecondaryMod', {
                    selectedModId: selectedModObject.selectedModId,
                    selectedModTier: selectedModObject.selectedModTier
                })
            }

        },
        setSelectedEquipment1({ commit }, newLoadoutItem) {
            
            commit('clearCurrentlySelectedEquipment')
            commit('setCurrentlySelectedEquipment', newLoadoutItem)
            commit('setSelectedEquipment1', newLoadoutItem)

        },
        setSelectedEquipment1Mod({ commit, state }, selectedModObject) {

            let currentTierSelection = state.selectedEquipment1Mods.filter(mod => mod.selectedModTier === selectedModObject.selectedModTier)
            let sameSelection = false;
            if (state.selectedEquipment1Mods.length != 0) {
                // We have selected mods
                if (currentTierSelection.length != 0) {

                    //TODO: We need to clear the specific equipment for the specific tier here
                    commit('clearAllSelectedEquipmentMod', selectedModObject.selectedModTier)
                    if (currentTierSelection[0].selectedModId == selectedModObject.selectedModId) {
                        sameSelection = true
                    }
                }

            }
            if (!sameSelection) {
                commit('setSelectedEquipment1Mod', {
                    selectedModId: selectedModObject.selectedModId,
                    selectedModTier: selectedModObject.selectedModTier
                })
            }

        },
        setSelectedEquipment2({ commit }, newLoadoutItem) {
            
            commit('clearCurrentlySelectedEquipment')
            commit('setCurrentlySelectedEquipment', newLoadoutItem)
            commit('setSelectedEquipment2', newLoadoutItem)

        },
        setSelectedEquipment2Mod({ commit, state }, selectedModObject) {

            let currentTierSelection = state.selectedEquipment2Mods.filter(mod => mod.selectedModTier === selectedModObject.selectedModTier)
            let sameSelection = false;
            if (state.selectedEquipment2Mods.length != 0) {
                // We have selected mods
                if (currentTierSelection.length != 0) {

                    commit('clearAllSelectedEquipmentMod', selectedModObject.selectedModTier)
                    if (currentTierSelection[0].selectedModId == selectedModObject.selectedModId) {
                        sameSelection = true
                    }
                }

            }
            if (!sameSelection) {
                commit('setSelectedEquipment2Mod', {
                    selectedModId: selectedModObject.selectedModId,
                    selectedModTier: selectedModObject.selectedModTier
                })
            }

        },
        setSelectedEquipment3({ commit }, newLoadoutItem) {
            
            commit('clearCurrentlySelectedEquipment')
            commit('setCurrentlySelectedEquipment', newLoadoutItem)
            commit('setSelectedEquipment3', newLoadoutItem)

        },
        setSelectedEquipment3Mod({ commit, state }, selectedModObject) {

            let currentTierSelection = state.selectedEquipment3Mods.filter(mod => mod.selectedModTier === selectedModObject.selectedModTier)
            let sameSelection = false;
            if (state.selectedEquipment3Mods.length != 0) {
                // We have selected mods
                if (currentTierSelection.length != 0) {

                    commit('clearAllSelectedEquipmentMod', selectedModObject.selectedModTier)
                    if (currentTierSelection[0].selectedModId == selectedModObject.selectedModId) {
                        sameSelection = true
                    }
                }

            }
            if (!sameSelection) {
                commit('setSelectedEquipment3Mod', {
                    selectedModId: selectedModObject.selectedModId,
                    selectedModTier: selectedModObject.selectedModTier
                })
            }

        },
    },
    getters: {
        getLoadingStatus: (state) => {
          return state.loadingStatus  
        },
        // This should be by ID
        getLoadoutClassWeaponByName: (state) => (weaponName) => {
            // Pull the requested class weapon by name, which is stored in components
            return state.loadoutClassData.guns.filter(function (el) {
                return el.name == weaponName
            })
        },
        // This should be by ID
        getIsSelectedPrimary: (state) => (weaponId) => {
            return state.selectedPrimary === weaponId
        },
        getIsSelectedSecondary: (state) => (weaponId) => {
            return state.selectedSecondary === weaponId
        },
        getIsCurrentlySelectedEquipment: (state) => (equipmentId) => {
            return state.currentlySelectedEquipment === equipmentId
        },
        getIconByName: (state) => (iconName) => {
            return state.icons.default[iconName]
        },
        getOcDataByWeapon: (state) => (gunId) => {
            let ocWeapon = state.loadoutClassData.guns.filter(gun => gun.id === gunId);
            let ocData = ocWeapon[0].overclocks;

            return ocData;
        },
        getIsSelectedMod: (state) => (modId) => {
            if (state.selectedPrimaryMods.filter(mod => mod.selectedModId === modId).length != 0)
                return true
            return false
        },
        getSelectedModCosts: (state) => (itemType) => {
            // TODO: Might need a whole-class version of this component
            // TODO: Update to be whatever weapon we're on

            // Need to do a check for gun or equipment...we may pass that in as a prop to this getter
            // hardcoded for now
            if (state.loadoutClassData != '') {
                // filter selected mods for our mod ids
                let selectedModIds = [];
                let selectedItemId = '';
                switch (itemType) {
                    case "primary":
                        selectedModIds = state.selectedPrimaryMods.map(a => a.selectedModId);
                        selectedItemId = state.selectedPrimary;
                        break;
                    case "secondary":
                        selectedModIds = state.selectedSecondaryMods.map(a => a.selectedModId);
                        selectedItemId = state.selectedSecondary;
                        break;
                    
                
                    default:
                        break;
                }
                let selectedOcId = null;

                if (state.selectedPrimaryOverclock != '' &&  itemType === "primary") {
                    selectedOcId = state.selectedPrimaryOverclock;
                } else if (state.selectedSecondaryOverclock != '' &&  itemType === "secondary") {
                    selectedOcId = state.selectedSecondaryOverclock;
                }

                // TODO: Make this dynamic -- GUN ID
                let mainItem = state.loadoutClassData.guns.filter(gun => gun.id == selectedItemId);
                let itemMods = mainItem[0].mods;

                // filter selected primary mods 
                let selectedModArray = [];

                selectedModIds.forEach(function (modId) {
                    itemMods.forEach(mod => {
                        if (mod.id == modId) {
                            selectedModArray.push(mod)
                        }
                    });
                });

                // I'll need to figure out which weapon we're picking
                // maybe send primary or secondary? 
                // Won't work for equipment
                // Probably need to pass the gun or equipment ID
                // use like item ID or something
                let creditsCost = selectedModArray
                    .map((mod) => mod.credits_cost)
                    .reduce((prev, curr) => prev + curr, 0);
                let magniteCost = selectedModArray
                    .map((mod) => mod.magnite_cost)
                    .reduce((prev, curr) => prev + curr, 0);
                let bismorCost = selectedModArray
                    .map((mod) => mod.bismor_cost)
                    .reduce((prev, curr) => prev + curr, 0);
                let umaniteCost = selectedModArray
                    .map((mod) => mod.umanite_cost)
                    .reduce((prev, curr) => prev + curr, 0);
                let enorCost = selectedModArray
                    .map((mod) => mod.enor_pearl_cost)
                    .reduce((prev, curr) => prev + curr, 0);
                let jadizCost = selectedModArray
                    .map((mod) => mod.jadiz_cost)
                    .reduce((prev, curr) => prev + curr, 0);


                // This is an internal function variable, so we will always be able to trust this block
                // It will be established based on the param in the first few lines of the function
                if (selectedOcId) {
                    let overclockObject = mainItem[0].overclocks.filter(overclock => overclock.id == selectedOcId);

                    creditsCost += overclockObject[0].credits_cost;
                    magniteCost += overclockObject[0].magnite_cost;
                    bismorCost += overclockObject[0].bismor_cost;
                    umaniteCost += overclockObject[0].umanite_cost;
                    enorCost += overclockObject[0].enorCost_cost;
                    jadizCost += overclockObject[0].jadiz_cost;
                }



                let costObject = {
                    creditsCost: creditsCost,
                    magniteCost: magniteCost,
                    bismorCost: bismorCost,
                    umaniteCost: umaniteCost,
                    enorCost: enorCost,
                    jadizCost: jadizCost,
                };
                return costObject;


            }
        },
        getModsForMatrix: (state) => (itemId, boolEquipment) => {
            let itemObject = '';
            let itemModArray = '';           
            
            if(boolEquipment === true) {
                itemObject = state.loadoutClassData.equipments.filter(equipment => equipment.id == itemId);
                itemModArray = itemObject[0].equipment_mods;
            } else {
                itemObject = state.loadoutClassData.guns.filter(gun => gun.id == itemId);
                itemModArray = itemObject[0].mods;
            }
            console.log('itemModArray');
            console.log(itemModArray);
            return itemModArray;

        }
    }
})
