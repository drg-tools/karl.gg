import Vue from 'vue';
import Vuex from 'vuex';
import moment from 'moment';
import VuexPersist from 'vuex-persist';

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
    },
})
