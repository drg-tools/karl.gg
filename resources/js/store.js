import Vue from 'vue';
import Vuex from 'vuex';
import VuexPersist from 'vuex-persist';
import IconList from './IconsList';
import gql from 'graphql-tag';
import apolloQueries from './apolloQueries';
import { Apollo } from './apollo';


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
        selectedClass: '', // We may need to store a user's loadout on all classes, but use selected class to know which one to save.
        loadoutName: '',
        loadoutDescription: '',
        loadoutClassData: ''

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
    },
    actions: {
        // set the latest class select by user
        // also hydrate the newly selected class for them
        // ensure you clear the previously selected class
        // Theory: users don't need to hold all 4 classes in their store synchronously
        async getClassData({state}, className) {
            // might need to do this in the indivudual components. Vuex not playing nice
            console.log('classname to be: ' + JSON.stringify(className.className));
            let selectedClassName = String(JSON.stringify(className.className));
            console.log('classname to be: ' + selectedClassName);
            return Apollo.query({
                query: gql`${apolloQueries.characterByName(selectedClassName)}`
            }).then(result => {
                return result.data.characterByName;
                // console.log(result.data.characterByName);
                //  result.data.characterByName;
            }).catch(err => {
                throw err;
            });
        },
        async hydrateClassData({commit, dispatch}, newClassName) {
            let classData = await dispatch('getClassData', {
                className: newClassName
            }).then(result =>{
                // Use our data response to hydrate all needed class data
                commit('setloadoutClassData', result);
            }).catch(err => {
                throw err;
            });
        },
        async setSelectedClass({ commit,dispatch, state }, newClassNameInput) {
            // clear the previously selected class
            commit('clearloadoutClassData')

            dispatch('hydrateClassData', newClassNameInput);
            
            // Set newly selected class only after we have:
            // 1. Deleted old class data
            // 2. Hydrated new class data
            commit('setSelectedClass', newClassNameInput)
        }
    },
})
