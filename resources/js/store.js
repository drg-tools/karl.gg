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


export default new Vuex.Store({
    plugins: [vuexPersist.plugin],
    state: {
        selectedClass: '',
        loadoutName: '',
        loadoutDescription: '',
        loadoutClassData: '',
        icons: IconList,
        selectedPrimary: '',
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
        setSelectedPrimary(state, newValues) {
            state.selectedPrimary = newValues
        },
        clearSelectedPrimary(state) {
            state.selectedPrimary = ''
        }
    },
    actions: {
        async getClassData({state}, className) {
            // Parsing a response 
            let selectedClassName = className;
            return Apollo.query({
                query: gql`${apolloQueries.characterByName(selectedClassName)}`
            }).then(result => {
                return result.data.characterByName;
            }).catch(err => {
                throw err;
            });
        },
        async hydrateClassData({commit, dispatch}, newClassName) {
            let classData = await dispatch('getClassData', newClassName).then(result =>{
                // Use our data response to hydrate all needed class data
                // Commit this directly to store, called each time you select a class
                commit('setloadoutClassData', result);
            }).catch(err => {
                throw err;
            });
        },
        async setSelectedClass({ commit,dispatch, state }, newClassNameInput) {
            // clear the previously selected class
            commit('clearloadoutClassData')

            // dispatch an action which will commit our new class data to store
            dispatch('hydrateClassData', newClassNameInput);
            
            // Set newly selected class only after we have:
            // 1. Deleted old class data
            // 2. Hydrated new class data
            commit('setSelectedClass', newClassNameInput)
        },
        setSelectedPrimary({commit, state}, newLoadoutItem) {
            
            // TODO: For equipments only, do not clear their array in the store when a new one is added
            //           If it's a primary or secondary, wipe the existing and save the new one
            let selectedItem = state.loadoutClassData.guns.filter(items => items.name == newLoadoutItem.itemName)
            
            commit('clearSelectedPrimary')
            commit('setSelectedPrimary', selectedItem)
           
        }
    },
    getters: {
        getLoadoutClassWeaponByName: (state) => (weaponName) => { 
            // Pull the requested class weapon by name, which is stored in components
            return state.loadoutClassData.guns.filter(function (el) {
                return el.name == weaponName
            })
        },
        getIsSelectedPrimary: (state) => (weaponName) => {
            return state.selectedPrimary[0].name === weaponName
        }
    }
})
