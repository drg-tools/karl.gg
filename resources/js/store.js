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
        driller: '',
        engineer: '',
        gunner: '',
        scout: '',

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
        setDrillerData(state, drillerData) {
            state.driller = drillerData
        },
        clearDrillerData(state) {
            state.driller = ''
        },
        setEngineerData(state, engineerData) {
            state.engineer = engineerData
        },
        clearEngineerData(state) {
            state.engineer = ''
        },
        setGunnerData(state, gunnerData) {
            state.gunner = gunnerData
        },
        clearGunnerData(state) {
            state.gunner = ''
        },
        setScoutData(state, scoutData) {
            state.scout = scoutData
        },
        clearScoutData(state) {
            state.scout = ''
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
                // console.log(result.data.characterByName);
                //  result.data.characterByName;
            }).catch(err => {
                throw err;
            });

            
        },
        async setSelectedClass({ commit,dispatch, state }, newClass) {
            // clear the previously selected class
            switch (state.selectedClass) {
                case 'driller':
                    console.log('driller was previously and will be cleared')
                    commit('clearDrillerData')
                    break;
                case 'engineer':
                    console.log('engineer was previously and will be cleared')
                    commit('clearEngineerData')
                    break;
                case 'gunner':
                    console.log('gunner was previously and will be cleared')
                    commit('clearGunnerData')
                    break;
                case 'scout':
                    console.log('scout was previously and will be cleared')
                    commit('clearScoutData')
                    break;

                default:
                    break;
            }

            
            
            // Use our data response to hydrate all needed class data
            switch (newClass) {
                case 'driller':
                    console.log('driller will now be loaded and is new selected class')
                    let data = await dispatch('getClassData', {
                        className: 'driller'
                    }).then(result =>{
                        console.log('driller should now be set to:')
                        console.log(result)
                    })
                    commit('setDrillerData', data.characterByName)
                    console.log('driller should now be set to:')
                    console.log(state.driller)
                    break;
                case 'engineer':
                    console.log('engineer will now be loaded and is new selected class')
                    break;
                case 'gunner':
                    console.log('gunner will now be loaded and is new selected class')
                    break;
                case 'scout':
                    console.log('scout will now be loaded and is new selected class')
                    break;

                default:
                    break;
            }

            
            // Set newly selected class only after we have:
            // 1. Deleted old class data
            // 2. Hydrated new class data
            commit('setSelectedClass', newClass)
        }
    },
})
