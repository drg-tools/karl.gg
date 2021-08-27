import Vue from 'vue';
import Vuex, { Store } from 'vuex';
import VuexPersist from 'vuex-persist';
import gql from 'graphql-tag';
import apolloQueries from './apolloQueries';
import { Apollo } from './apollo';
import * as IconList from './IconsList';



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


export default new Vuex.Store({
    // plugins: [vuexPersist.plugin], // Disable when debugging locally
    state: {
        selectedClass: '',
        loadoutName: '', // TODO: In the component Debounce on this
        loadoutDescription: '', // TODO: In the component Debounce on this
        loadoutClassData: '', // Use this as source of truth, only call this. Save ID's in other manipulators
        icons: IconList,
        selectedPrimary: '', // Make this an ID only
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
        }
    },
    actions: {
        async getClassData({state}, classId) {
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
        async hydrateClassData({commit, dispatch}, newClassId) {
            let classData = await dispatch('getClassData', newClassId).then(result =>{
                // Use our data response to hydrate all needed class data
                // Commit this directly to store, called each time you select a class
                commit('setloadoutClassData', result);
            }).catch(err => {
                throw err;
            });
        },
        async setSelectedClass({ commit,dispatch, state }, newClassIdInput) {
            // clear the previously selected class
            commit('clearloadoutClassData')

            // dispatch an action which will commit our new class data to store
            dispatch('hydrateClassData', newClassIdInput);
            
            // Set newly selected class only after we have:
            // 1. Deleted old class data
            // 2. Hydrated new class data
            commit('setSelectedClass', newClassIdInput)
        },
        setSelectedPrimary({commit, state}, newLoadoutItem) {
            
            // TODO: For equipments only, do not clear their array in the store when a new one is added
            //           If it's a primary or secondary, wipe the existing and save the new one
            // TODO: Make this ID's
            
            commit('clearSelectedPrimary')
            commit('setSelectedPrimary', newLoadoutItem)
           
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
        }
    }
})
