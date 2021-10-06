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
        selectedClass: '',
        loadoutName: '', // TODO: In the component Debounce on this
        loadoutDescription: '', // TODO: In the component Debounce on this
        loadoutClassData: '', // Use this as source of truth, only call this. Save ID's in other manipulators
        icons: IconList,
        selectedPrimary: '', // Make this an ID only
        selectedPrimaryMods: [],
        selectedPrimaryOverclock: '',
        // Array of ID's on the selected mods
    },
    mutations: {
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
        setSelectedPrimaryOverclock(state, newValue) {
            state.selectedPrimaryOverclock = newValue
        },
        clearSelectedPrimaryOverclock(state) {
            state.selectedPrimaryOverclock = ''
        },
    },
    actions: {
        getClassData(classId) {
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
            let classData = dispatch('getClassData', newClassId).then(result => {
                // Use our data response to hydrate all needed class data
                // Commit this directly to store, called each time you select a class
                commit('setloadoutClassData', result);
            }).catch(err => {
                throw err;
            });
        },
        setSelectedClass({ commit, dispatch }, newClassIdInput) {
            // clear the previously selected class
            commit('clearloadoutClassData')
            // also clear the previously selected data
            commit('clearSelectedPrimary')


            // TODO: Clear button to allow you to clear the whole current state
            // TODO: On initial class select, select a specific primary.
            //      Maybe on component load for primary etc.


            // dispatch an action which will commit our new class data to store
            dispatch('hydrateClassData', newClassIdInput);

            // Set newly selected class only after we have:
            // 1. Deleted old class data
            // 2. Hydrated new class data
            commit('setSelectedClass', newClassIdInput)
        },
        setSelectedPrimary({ commit }, newLoadoutItem) {

            // TODO: For equipments only, do not clear their array in the store when a new one is added
            //           If it's a primary or secondary, wipe the existing and save the new one
            // TODO: Make this ID's

            commit('clearSelectedPrimary')
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

        }
    },
    getters: {
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
        getIconByName: (state) => (iconName) => {
            return state.icons.default[iconName]
        },
        getOcDataById: (state) => (ocId, gunId) => {
            let ocWeapon = state.loadoutClassData.guns.filter(gun => gun.id === gunId);
            let ocData = ocWeapon[0].overclocks.filter(oc => oc.id === ocId);

            return ocData;
        },
        getIsSelectedMod: (state) => (modId) => {
            if (state.selectedPrimaryMods.filter(mod => mod.selectedModId === modId).length != 0)
                return true
            return false
        },
        getSelectedModCosts: (state) => {

            // Need to do a check for gun or equipment...we may pass that in as a prop to this getter
            // hardcoded for now
            if (state.loadoutClassData != '') {
                // filter selected mods for our mod ids
                let selectedModIds = state.selectedPrimaryMods.map(a => a.selectedModId);
                let selectedOcId = null;
                // maybe just send the mods??

                // TODO: Make this dynamic -- potentially send an OC object as an optional param
                if (state.selectedPrimaryOverclock != '') {
                    selectedOcId = state.selectedPrimaryOverclock;
                }

                // TODO: Make this dynamic -- GUN ID
                let mainItem = state.loadoutClassData.guns.filter(gun => gun.id == 9);
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
    }
})
