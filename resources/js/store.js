import Vue from 'vue';
import Vuex, { Store } from 'vuex';
import VuexPersist from 'vuex-persist';
import gql from 'graphql-tag';
import apolloQueries from './apolloQueries';
import { Apollo } from './apollo';
import * as IconList from './IconsList';
import { sortedLastIndexOf } from 'lodash';



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
        hydrateClassData({commit, dispatch}, newClassId) {
            let classData = dispatch('getClassData', newClassId).then(result =>{
                // Use our data response to hydrate all needed class data
                // Commit this directly to store, called each time you select a class
                commit('setloadoutClassData', result);
            }).catch(err => {
                throw err;
            });
        },
        setSelectedClass({ commit,dispatch }, newClassIdInput) {
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
        setSelectedPrimary({commit}, newLoadoutItem) {
            
            // TODO: For equipments only, do not clear their array in the store when a new one is added
            //           If it's a primary or secondary, wipe the existing and save the new one
            // TODO: Make this ID's
            
            commit('clearSelectedPrimary')
            commit('setSelectedPrimary', newLoadoutItem)
           
        },
        setSelectedPrimaryMod({commit, state}, selectedModObject) {
            // Expect to only receive 1 ID at a time
            // we'll fire this when you click on something
            // Issue: Keeping track of mod tiers?
            
            // When you select a mod, we will try to push it to the array of mods
            // We will quickly check to see if there's any other selected mods in this tier for your weapon        
            

            // We will group the object like this to keep track of all the tiers
            // Since you can only have 1 item selected per tier, we have to clear the previous one
            // This will also allow our frontend to keep track of how to display them
            console.log(selectedModObject)
            console.log(selectedModObject.selectedModTier)

            // if( state.selectedPrimaryMods != null ) {
            //     // If we have a mod in this tier already,
            //     // clear the mod before committing our new one
            //     commit('clearSelectedPrimaryMod', selectedModObject[1])
            // } 

            if(state.selectedPrimaryMods.length != 0 ) {
                // We have selected mods
                console.log('you have selected mods')
                let currentTierSelection = state.selectedPrimaryMods.filter(mod => mod.selectedModTier === selectedModObject.selectedModTier)
                if(currentTierSelection.length != 0) {
                    console.log('current tier selection')
                    console.log(currentTierSelection)
                    commit('clearSelectedPrimaryMod', selectedModObject.selectedModTier)
                }
            
            }
            // console.log(state.selectedPrimaryMods.filter(mod => mod.selectedModTier === selectedModObject.selectedModTier))



            commit('setSelectedPrimaryMod', {
                selectedModId: selectedModObject.selectedModId, 
                selectedModTier: selectedModObject.selectedModTier
            })
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
            if(state.selectedPrimaryMods.filter(mod => mod.selectedModId === modId).length != 0)
                return true
            return false
        },
    }
})
