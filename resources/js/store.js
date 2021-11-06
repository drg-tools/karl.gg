import Vue from 'vue';
import Vuex, {Store} from 'vuex';
import VuexPersist from 'vuex-persist';
import gql from 'graphql-tag';
import apolloQueries from './apolloQueries';
import {Apollo} from './apollo';
import * as IconList from './IconsList';
import {concat, over, sortedLastIndexOf, toInteger} from 'lodash';


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
        user: null,
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
        // currentlySelectedEquipment: '',
        // selectedEquipment1: '',
        // selectedEquipment1Mods: [],
        // selectedEquipment2: '',
        // selectedEquipment2Mods: [],
        // selectedEquipment3: '',
        // selectedEquipment3Mods: [],

        selectedEquipment: '',
        selectedEquipmentMods: []
    },
    mutations: {
        setLoadingStatus(state, newLoadingStatus) {
            state.loadingStatus = newLoadingStatus;
        },
        setAuthUser(state, user) {
            state.user = user;
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
        setAllSelectedPrimaryMods(state, newValue) {
            state.selectedPrimaryMods = newValue;
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
        setSelectedSecondaryMod(state, newValue) {
            state.selectedSecondaryMods.push(newValue)
        },
        setAllSelectedSecondaryMods(state, newValue) {
            state.selectedSecondaryMods = newValue;
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
        setSelectedEquipment(state, newValue) {
            state.selectedEquipment = newValue
        },
        clearSelectedEquipment(state) {
            state.selectedEquipment = ''
        },
        setSelectedEquipmentMod(state, modId) {
            state.selectedEquipmentMods.push(modId);
        },
        setAllSelectedEquipmentMod(state, newValues) {
            state.selectedEquipmentMods = newValues;
        },
        clearSelectedEquipmentMod(state, modId) {
            state.selectedEquipmentMods = state.selectedEquipmentMods.filter(mod => mod !== modId);
        },

    },
    actions: {
        async saveLoadout({state,commit, dispatch}) {
            if(state.loadoutName === '') {
                // loadout name can't be null
                // TODO: return a more meaningful message
                console.log('loadout name is requred')
                return;
            }
            
           
            // We need to flatten 
            let combinedModArray = [];
            let combinedOverClockIdArray = [];
            let combinedEquipmentModArray = state.selectedEquipmentMods; // This will be empty array if null anyways

            combinedModArray = combinedModArray.concat(state.selectedPrimaryMods, state.selectedSecondaryMods)
            
            if(state.selectedPrimaryOverclock != '') {
                combinedOverClockIdArray.push(state.selectedPrimaryOverclock);
                // Need to make array of strings ID's for gql
                combinedOverClockIdArray = combinedOverClockIdArray.map(Number);
            }

            if(state.selectedSecondaryOverclock != '') {
                combinedOverClockIdArray.push(state.selectedSecondaryOverclock);
                // Need to make array of strings ID's for gql
                combinedOverClockIdArray = combinedOverClockIdArray.map(Number);
            }

            console.log('combined mods:');
            console.log(combinedModArray);
            console.log('combined ocs:');
            console.log(combinedOverClockIdArray);

            let combinedModIdArray = combinedModArray.map(e => e.selectedModId);
            // Need to make array of strings ID's for gql
            combinedModIdArray = combinedModIdArray.map(Number);
            combinedEquipmentModArray = combinedEquipmentModArray.map(Number);
            
            // Here's what we need to save
            let variables = {
                name: state.loadoutName,
                description: state.loadoutDescription,
                character_id: state.selectedClass,
                mods: combinedModIdArray,
                overclocks: combinedOverClockIdArray,
                equipment_mods: combinedEquipmentModArray,
                throwable_id: 1 // HARDCODED -- we don't support throwables on the UI yet. This is tech debt until then
            };
            
            // This is where we actually save the loadout
            let loadoutData = await dispatch('createLoadout', variables);
            console.log(loadoutData);
            return loadoutData;
        },
        async createLoadout({state}, params) {
            let variables = {
                name: params.name,
                description: params.description,
                character_id: params.character_id,
                mods: params.mods,
                overclocks: params.overclocks,
                equipment_mods: params.equipment_mods,
                throwable_id: params.throwable_id
            };
            
            // Call to the graphql mutation
            let result = Apollo.mutate({
                // Query
                mutation: gql`mutation (
                    $name: String!,
                    $description: String,
                    $character_id: Int!,
                    $mods: [Int!]!,
                    $overclocks: [Int!]!,
                    $equipment_mods: [Int!]!,
                    $throwable_id: Int!,
                    ) {
                        createLoadout(
                            name: $name
                            description: $description
                            character_id: $character_id
                            mods: $mods
                            overclocks: $overclocks
                            equipment_mods: $equipment_mods
                            throwable_id: $throwable_id
                          ) {
                          id
                          name
                          description
                        }
                      }`,
                // Parameters
                variables: variables
            });
            return result;
        },
        updateLoadout(params) {
            let variables = {
                id: parseInt(params.loadout_id),
                name: params.name,
                description: params.description,
                character_id: params.character_id,
                mods: params.mods,
                overclocks: params.overclocks,
                equipment_mods: params.equipment_mods,
                throwable_id: params.throwable_id
            };

            // Call to the graphql mutation
            const result = Apollo.mutate({
                // Query
                mutation: gql`mutation (
                    $id: Int!,
                    $name: String!,
                    $description: String,
                    $character_id: Int!,
                    $mods: [Int!]!,
                    $overclocks: [Int!]!,
                    $equipment_mods: [Int!]!,
                    $throwable_id: Int!,
                    ) {
                        updateLoadout(
                            id: $id
                            name: $name
                            description: $description
                            character_id: $character_id
                            mods: $mods
                            overclocks: $overclocks
                            equipment_mods: $equipment_mods
                            throwable_id: $throwable_id
                          ) {
                          id
                          name
                          description
                        }
                      }`,
                // Parameters
                variables: variables
            }).then(result => {
                console.log('result of loadout UPDATE');
                console.log(result);
                return result;
            }).catch(err => {
                throw err;
            });
           
        },
        getClassData({state}, classId) {
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
        async getLoadoutData({state}, loadoutId) {
            // Parsing a response
            return Apollo.query({
                query: gql`${apolloQueries.loadoutDetails(loadoutId)}`
            }).then(result => {
                return result.data.loadout;
            }).catch(err => {
                throw err;
            });
        },
        hydrateClassData({commit, dispatch}, newClassId) {
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
        hydrateLoadoutEditData({state,commit,dispatch}, loadoutData) {
            console.log('selected class:');
            console.log(loadoutData.character.id);
            // TODO: this needs to now somehow set the icon on the frontend
            //       I think frontend icon isn't leveraging global state?
            dispatch('setSelectedClass', loadoutData.character.id);

            console.log('loadoutName');
            console.log(loadoutData.name);
            commit('setLoadoutName', loadoutData.name);

            console.log('loadoutDescription');
            console.log(loadoutData.description); 
            commit('setLoadoutDescription', loadoutData.description);

            
            
            let primaryGunMods = loadoutData.mods.filter(mod => mod.gun.character_slot == 1);
            let secondaryGunMods = loadoutData.mods.filter(mod => mod.gun.character_slot == 2);
            console.log('selectedPrimary');
            // As long as the pgm array is filtered correctly, this will always work.
            // Just take the first mod and pull the gun ID
            console.log(primaryGunMods[0].gun.id);
            commit('setSelectedPrimary', primaryGunMods[0].gun.id);
            
            
            
            

            console.log('selectedPrimaryMods');
            // Flatten the array to just ID's
            primaryGunMods = primaryGunMods.map(mod => mod.id);
            commit('setAllSelectedPrimaryMods', primaryGunMods);



            console.log(primaryGunMods);                    
            console.log('selectedSecondary');
            console.log(secondaryGunMods[0].gun.id);
            commit('setSelectedSecondary', secondaryGunMods[0].gun.id);
            

            console.log('selectedSecondaryMods');
            secondaryGunMods = secondaryGunMods.map(mod => mod.id);
            console.log(secondaryGunMods);
            commit('setSelectedSecondaryMod', secondaryGunMods);

            

            let selectedPrimaryOverclock = '';
            let selectedSecondaryOverclock = '';

            selectedPrimaryOverclock = loadoutData.overclocks?.filter(oc => oc.gun.character_slot === 1);
            selectedSecondaryOverclock = loadoutData.overclocks?.filter(oc => oc.gun.character_slot === 2);

            console.log('selectedPrimaryOverclock');
            console.log(selectedPrimaryOverclock[0].id);
            commit('setSelectedPrimaryOverclock', selectedPrimaryOverclock[0].id);
            


            console.log('selectedSecondaryOverclock');
            console.log(selectedSecondaryOverclock[0].id);
            commit('setSelectedSecondaryOverclock', selectedSecondaryOverclock[0].id);

            


            let selectedEquipmentIds = [];
            let selectedEquipmentMods = [];
            
            // Map the equipment_mods by their equipment ID's to quickly grab that
            selectedEquipmentIds = loadoutData.equipment_mods.map(mod => mod.equipment.id);

            selectedEquipmentMods = loadoutData.equipment_mods.map(mod => mod.id);

            console.log('equipment selected');
            console.log(selectedEquipmentIds);
            commit('setSelectedEquipment', selectedEquipmentIds);
            

            console.log('equipment mods selected');
            console.log(selectedEquipmentMods);
            commit('setAllSelectedEquipmentMod', selectedEquipmentMods);

        },
        setSelectedClass({commit, dispatch}, newClassIdInput) {
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
        setSelectedPrimary({commit}, newLoadoutItem) {

            commit('clearSelectedPrimary')
            commit('clearAllSelectedPrimaryMods')
            commit('clearSelectedPrimaryOverclock')
            commit('setSelectedPrimary', newLoadoutItem)

        },
        setSelectedPrimaryMod({commit, state}, selectedModObject) {

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
        setSelectedSecondary({commit}, newLoadoutItem) {

            commit('clearSelectedSecondary')
            commit('clearAllSelectedSecondaryMods')
            commit('clearSelectedSecondaryOverclock')
            commit('setSelectedSecondary', newLoadoutItem)

        },
        setSelectedSecondaryMod({commit, state}, selectedModObject) {

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
        setSelectedEquipment1({commit}, newLoadoutItem) {

            commit('clearCurrentlySelectedEquipment')
            commit('setCurrentlySelectedEquipment', newLoadoutItem)
            commit('setSelectedEquipment1', newLoadoutItem)

        },
        setSelectedEquipment1Mod({commit, state}, selectedModObject) {

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
        setSelectedEquipment2({commit}, newLoadoutItem) {

            commit('clearCurrentlySelectedEquipment')
            commit('setCurrentlySelectedEquipment', newLoadoutItem)
            commit('setSelectedEquipment2', newLoadoutItem)

        },
        setSelectedEquipment2Mod({commit, state}, selectedModObject) {

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
        setSelectedEquipment3({commit}, newLoadoutItem) {

            commit('clearCurrentlySelectedEquipment')
            commit('setCurrentlySelectedEquipment', newLoadoutItem)
            commit('setSelectedEquipment3', newLoadoutItem)

        },
        setSelectedEquipment3Mod({commit, state}, selectedModObject) {

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

        setSelectedEquipment({commit}, selected) {
            commit('clearSelectedEquipment')
            commit('setSelectedEquipment', selected)
        },

        setSelectedEquipmentMod({commit, dispatch, state}, selectedMod) {

            // Do we have a mod id in this tier, for this equipment already? If so, let's remove all other mods on that tier from store.
            const currentEquipmentId = state.selectedEquipment;
            const currentEquipment = state.loadoutClassData?.equipments?.filter(e => e.id === currentEquipmentId);

            if (currentEquipment[0]) {
                currentEquipment[0].equipment_mods
                    .filter(m => m.mod_tier === selectedMod.modTier)
                    .map(m => commit('clearSelectedEquipmentMod', m.id));
            }

            commit('setSelectedEquipmentMod', selectedMod.modId);
        },
    },
    getters: {
        loadoutClassPrimaries: (state) => {
            return state.loadoutClassData?.guns?.filter(gun => gun.character_slot === 1);
        },
        isLoggedIn: (state) => {
            return state.user !== null;
        },
        loadoutClassSecondaries: (state) => {
            return state.loadoutClassData?.guns?.filter(gun => gun.character_slot === 2);
        },
        loadoutClassEquipment: (state) => {
            return state.loadoutClassData?.equipments;
        },
        getSelectedPrimary: (state) => {
            return state.selectedPrimary;
        },
        getSelectedSecondary: (state) => {
            return state.selectedSecondary;
        },
        getSelectedEquipment: (state) => {
            return state.selectedEquipment;
        },
        getSelectedClass: (state) => () => {
            return state.selectedClass;
        },
        getLoadingStatus: (state) => {
            return state.loadingStatus;
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
        getIsSelectedEquipment: (state) => (equipmentId) => {
            return state.selectedEquipment === equipmentId
        },
        getIconByName: (state) => (iconName) => {
            return state.icons.default[iconName]
        },
        getOcDataByWeapon: (state) => (gunId) => {
            let ocWeapon = state.loadoutClassData.guns.filter(gun => gun.id === gunId);
            let ocData = ocWeapon[0].overclocks;

            return ocData;
        },
        getIsPrimaryModSelected: (state) => (modId) => {
            return state.selectedPrimaryMods.filter(mod => mod.selectedModId === modId).length !== 0;
        },
        getIsSecondaryModSelected: (state) => (modId) => {
            return state.selectedSecondaryMods.filter(mod => mod.selectedModId === modId).length !== 0;
        },
        getIsEquipmentModSelected: (state) => (modId) => {
            return state.selectedEquipmentMods.filter(mod => mod === modId).length !== 0
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

                if (state.selectedPrimaryOverclock != '' && itemType === "primary") {
                    selectedOcId = state.selectedPrimaryOverclock;
                } else if (state.selectedSecondaryOverclock != '' && itemType === "secondary") {
                    selectedOcId = state.selectedSecondaryOverclock;
                }

                // TODO: FIx for equipment
                let mainItem = state.loadoutClassData.guns.filter(gun => gun.id == selectedItemId);
                // This is throwing an error on secondaries sometimes
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

            if (boolEquipment === true) {
                itemObject = state.loadoutClassData.equipments.filter(equipment => equipment.id == itemId);
                itemModArray = itemObject[0].equipment_mods;
            } else {
                itemObject = state.loadoutClassData.guns.filter(gun => gun.id == itemId);
                itemModArray = itemObject[0].mods;
            }
            return itemModArray;

        }
    }
})
