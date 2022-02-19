import Vue from "vue";
import Vuex, { Store } from "vuex";
import VuexPersist from "vuex-persist";
import gql from "graphql-tag";
import apolloQueries from "./apolloQueries";
import { Apollo } from "./apollo";
import * as IconList from "./IconsList";
import { buildComboIndexFromGun } from "./utils";

Vue.use(Vuex);

// Persist storage while you still browse with us
// TODO: Clear items when a new loadout is submitted
const vuexPersist = new VuexPersist({
    key: "karl-gg",
    storage: window.sessionStorage,
});

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
        selectedClass: "",
        loadoutName: "",
        loadoutDescription: "",
        loadoutClassData: "", // Use this as source of truth, only call this. Save ID's in other manipulators
        icons: IconList,
        selectedPrimaryId: "",
        selectedPrimaryMods: [],
        selectedPrimaryOverclockId: "",
        selectedSecondaryId: "",
        selectedSecondaryMods: [],
        selectedSecondaryOverclockId: "",
        selectedEquipmentId: "",
        selectedEquipmentMods: [],
        lastSelectedItemId: "",
        lastSelectedItemObject: {}, // object we will set
        lastSelectedItemType: "", // primary-mod, secondary-mod, primary-oc etc.
    },
    mutations: {
        setLoadingStatus(state, newLoadingStatus) {
            state.loadingStatus = newLoadingStatus;
        },
        setAuthUser(state, user) {
            state.user = user;
        },
        setSelectedClass(state, newValue) {
            state.selectedClass = newValue;
        },
        clearSelectedClass(state) {
            state.selectedClass = "";
        },
        setLoadoutName(state, newValue) {
            state.loadoutName = newValue;
        },
        clearLoadoutName(state) {
            state.LoadoutName = "";
        },
        setLoadoutDescription(state, newValue) {
            state.loadoutDescription = newValue;
        },
        clearLoadoutDescription(state) {
            state.LoadoutDescription = "";
        },
        setloadoutClassData(state, classData) {
            state.loadoutClassData = classData;
        },
        clearloadoutClassData(state) {
            state.loadoutClassData = "";
        },
        setSelectedPrimary(state, newValue) {
            state.selectedPrimaryId = newValue;
        },
        clearSelectedPrimary(state) {
            state.selectedPrimaryId = "";
        },
        setSelectedPrimaryMod(state, newValue) {
            state.selectedPrimaryMods.push(newValue);
        },
        setAllSelectedPrimaryMods(state, newValue) {
            state.selectedPrimaryMods = newValue;
        },
        clearSelectedPrimaryMod(state, modTier) {
            state.selectedPrimaryMods = state.selectedPrimaryMods.filter(
                (mod) => mod.selectedModTier !== modTier
            );
        },
        clearAllSelectedPrimaryMods(state) {
            state.selectedPrimaryMods = [];
        },
        setSelectedPrimaryOverclock(state, newValue) {
            state.selectedPrimaryOverclockId = newValue;
        },
        clearSelectedPrimaryOverclock(state) {
            state.selectedPrimaryOverclockId = "";
        },
        setSelectedSecondary(state, newValue) {
            state.selectedSecondaryId = newValue;
        },
        clearSelectedSecondary(state) {
            state.selectedSecondaryId = "";
        },
        setSelectedSecondaryMod(state, newValue) {
            state.selectedSecondaryMods.push(newValue);
        },
        setAllSelectedSecondaryMods(state, newValue) {
            state.selectedSecondaryMods = newValue;
        },
        clearSelectedSecondaryMod(state, modTier) {
            state.selectedSecondaryMods = state.selectedSecondaryMods.filter(
                (mod) => mod.selectedModTier !== modTier
            );
        },
        clearAllSelectedSecondaryMods(state) {
            state.selectedSecondaryMods = [];
        },
        setSelectedSecondaryOverclock(state, newValue) {
            state.selectedSecondaryOverclockId = newValue;
        },
        clearSelectedSecondaryOverclock(state) {
            state.selectedSecondaryOverclockId = "";
        },
        setSelectedEquipment(state, newValue) {
            state.selectedEquipmentId = newValue;
        },
        clearSelectedEquipment(state) {
            state.selectedEquipmentId = "";
        },
        setSelectedEquipmentMod(state, modId) {
            state.selectedEquipmentMods.push(modId);
        },
        clearSelectedEquipmentMods(state) {
            state.selectedEquipmentMods = [];
        },
        setAllSelectedEquipmentMod(state, newValues) {
            state.selectedEquipmentMods = newValues;
        },
        clearSelectedEquipmentMod(state, modId) {
            state.selectedEquipmentMods = state.selectedEquipmentMods.filter(
                (mod) => mod !== modId
            );
        },
        setLastSelectedItemId(state, newValue) {
            state.lastSelectedItemId = newValue;
        },
        clearLastSelectedItemId(state) {
            state.lastSelectedItemId = "";
        },
        setLastSelectedItemObject(state, newValue) {
            state.lastSelectedItemObject = newValue;
        },
        clearLastSelectedItemObject(state) {
            state.lastSelectedItemObject = "";
        },
        setLastSelectedItemType(state, newValue) {
            state.lastSelectedItemType = newValue;
        },
        clearLastSelectedItemType(state) {
            state.lastSelectedItemType = "";
        },
        clearAllLastSelectedData(state) {
            // Easier than writing 3 calls each time
            state.lastSelectedItemId = "";
            state.lastSelectedItemObject = "";
            state.lastSelectedItemType = "";

        }
    },
    actions: {
        async saveLoadout({ state, commit, dispatch }, updateId) {
            if (state.loadoutName === "") {
                // loadout name can't be null
                // This will be handled by frontend validation, but consider this server-side as a double-check
                return;
            }

            // We need to flatten
            let combinedModArray = [];
            let combinedOverClockIdArray = [];
            let combinedEquipmentModArray = state.selectedEquipmentMods; // This will be empty array if null anyways

            combinedModArray = combinedModArray.concat(
                state.selectedPrimaryMods,
                state.selectedSecondaryMods
            );

            if (state.selectedPrimaryOverclockId != "") {
                combinedOverClockIdArray.push(state.selectedPrimaryOverclockId);
                // Need to make array of strings ID's for gql
                combinedOverClockIdArray = combinedOverClockIdArray.map(Number);
            }

            if (state.selectedSecondaryOverclockId != "") {
                combinedOverClockIdArray.push(
                    state.selectedSecondaryOverclockId
                );
                // Need to make array of strings ID's for gql
                combinedOverClockIdArray = combinedOverClockIdArray.map(Number);
            }

            let combinedModIdArray = combinedModArray.map(
                (e) => e.selectedModId
            );
            // Need to make array of strings ID's for gql
            combinedModIdArray = combinedModIdArray.map(Number);
            combinedEquipmentModArray = combinedEquipmentModArray.map(Number);

            // Here's what we need to save
            let variables = "";
            let loadoutData = "";
            // This is where we actually save the loadout
            if (updateId) {
                // updating a loadout
                variables = {
                    id: parseInt(updateId),
                    name: state.loadoutName,
                    description: state.loadoutDescription,
                    character_id: parseInt(state.selectedClass),
                    mods: combinedModIdArray,
                    overclocks: combinedOverClockIdArray,
                    equipment_mods: combinedEquipmentModArray,
                    throwable_id: 1, // HARDCODED -- we don't support throwables on the UI yet. This is tech debt until then
                };
                loadoutData = await dispatch("updateLoadout", variables);
            } else {
                // creating a new loadout
                variables = {
                    name: state.loadoutName,
                    description: state.loadoutDescription,
                    character_id: parseInt(state.selectedClass),
                    mods: combinedModIdArray,
                    overclocks: combinedOverClockIdArray,
                    equipment_mods: combinedEquipmentModArray,
                    throwable_id: 1, // HARDCODED -- we don't support throwables on the UI yet. This is tech debt until then
                };
                loadoutData = await dispatch("createLoadout", variables);
            }
            return loadoutData;
        },
        async createLoadout({ state }, params) {
            let variables = {
                name: params.name,
                description: params.description,
                character_id: params.character_id,
                mods: params.mods,
                overclocks: params.overclocks,
                equipment_mods: params.equipment_mods,
                throwable_id: params.throwable_id,
            };

            // Call to the graphql mutation
            let result = Apollo.mutate({
                // Query
                mutation: gql`
                    mutation (
                        $name: String!
                        $description: String
                        $character_id: Int!
                        $mods: [Int!]!
                        $overclocks: [Int!]!
                        $equipment_mods: [Int!]!
                        $throwable_id: Int!
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
                    }
                `,
                // Parameters
                variables: variables,
            });
            return result;
        },
        async updateLoadout({ state }, params) {
            let variables = {
                id: parseInt(params.id),
                name: params.name,
                description: params.description,
                character_id: params.character_id,
                mods: params.mods,
                overclocks: params.overclocks,
                equipment_mods: params.equipment_mods,
                throwable_id: params.throwable_id,
            };

            // Call to the graphql mutation
            const result = Apollo.mutate({
                // Query
                mutation: gql`
                    mutation (
                        $id: Int!
                        $name: String!
                        $description: String
                        $character_id: Int!
                        $mods: [Int!]!
                        $overclocks: [Int!]!
                        $equipment_mods: [Int!]!
                        $throwable_id: Int!
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
                    }
                `,
                // Parameters
                variables: variables,
            });
            return result;
        },
        getClassData({ state }, classId) {
            // Parsing a response
            let selectedClassId = classId;
            return Apollo.query({
                query: gql`
                    ${apolloQueries.character(selectedClassId)}
                `,
            })
                .then((result) => {
                    return result.data.character;
                })
                .catch((err) => {
                    throw err;
                });
        },
        async getLoadoutData({ state }, loadoutId) {
            // Parsing a response
            return Apollo.query({
                query: gql`
                    ${apolloQueries.loadoutDetails(loadoutId)}
                `,
            })
                .then((result) => {
                    return result.data.loadout;
                })
                .catch((err) => {
                    throw err;
                });
        },
        hydrateClassData({ commit, dispatch }, newClassId) {
            commit("setLoadingStatus", true);
            let classData = dispatch("getClassData", newClassId)
                .then((result) => {
                    // Use our data response to hydrate all needed class data
                    // Commit this directly to store, called each time you select a class
                    commit("setloadoutClassData", result);
                    commit("setLoadingStatus", false);
                })
                .catch((err) => {
                    throw err;
                });
        },
        hydrateLoadoutEditData({ state, commit, dispatch }, loadoutData) {
            dispatch("setSelectedClass", loadoutData.character.id);

            commit("setLoadoutName", loadoutData.name);

            if (loadoutData?.description) {
                commit("setLoadoutDescription", loadoutData.description);
            }

            let primaryGunMods = loadoutData?.mods.filter(
                (mod) => mod.gun.character_slot == 1
            );
            let secondaryGunMods = loadoutData?.mods.filter(
                (mod) => mod.gun.character_slot == 2
            );

            // As long as the pgm array is filtered correctly, this will always work.
            // Just take the first mod and pull the gun ID
            if (primaryGunMods[0]?.gun?.id) {
                commit("setSelectedPrimary", primaryGunMods[0]?.gun?.id);
                primaryGunMods = primaryGunMods.map((mod) => ({
                    selectedModId: mod.id,
                    selectedModTier: mod.mod_tier,
                }));
                commit("setAllSelectedPrimaryMods", primaryGunMods);
            }

            if (secondaryGunMods[0]?.gun?.id) {
                commit("setSelectedSecondary", secondaryGunMods[0]?.gun?.id);
                secondaryGunMods = secondaryGunMods.map((mod) => ({
                    selectedModId: mod.id,
                    selectedModTier: mod.mod_tier,
                }));
                commit("setAllSelectedSecondaryMods", secondaryGunMods);
            }

            let selectedPrimaryOverclock = "";
            let selectedSecondaryOverclock = "";

            selectedPrimaryOverclock = loadoutData.overclocks?.filter(
                (oc) => oc.gun.character_slot === 1
            );
            selectedSecondaryOverclock = loadoutData.overclocks?.filter(
                (oc) => oc.gun.character_slot === 2
            );

            if (selectedPrimaryOverclock[0]?.id) {
                commit(
                    "setSelectedPrimaryOverclock",
                    selectedPrimaryOverclock[0]?.id
                );
            }

            if (selectedSecondaryOverclock[0]?.id) {
                commit(
                    "setSelectedSecondaryOverclock",
                    selectedSecondaryOverclock[0]?.id
                );
            }

            let selectedEquipmentId = "";
            let selectedEquipmentMods = [];

            // Map the equipment_mods by their equipment ID's to quickly grab that
            // NOTE: This ends up with a bunch of duplicate keys because of how we map
            selectedEquipmentId = loadoutData?.equipment_mods.map(
                (mod) => mod.equipment.id
            );
            // Pull the first equipment ID and set it as selected
            selectedEquipmentId = selectedEquipmentId[0];

            selectedEquipmentMods = loadoutData?.equipment_mods.map(
                (mod) => mod.id
            );

            if (selectedEquipmentId) {
                commit("setSelectedEquipment", selectedEquipmentId);
                commit("setAllSelectedEquipmentMod", selectedEquipmentMods);
            }
        },
        setSelectedClass({ commit, dispatch }, newClassIdInput) {
            // clear the previously selected class
            commit("clearloadoutClassData");
            // also clear the previously selected data
            commit("clearSelectedPrimary");
            commit("clearAllSelectedPrimaryMods");
            commit("clearSelectedPrimaryOverclock");

            commit("clearSelectedSecondary");
            commit("clearAllSelectedSecondaryMods");
            commit("clearSelectedSecondaryOverclock");

            commit("clearSelectedEquipment");
            commit("clearSelectedEquipmentMods");
            
            commit("clearAllLastSelectedData");


            // dispatch an action which will commit our new class data to store
            dispatch("hydrateClassData", newClassIdInput);

            // Set newly selected class only after we have:
            // 1. Deleted old class data
            // 2. Hydrated new class data
            commit("setSelectedClass", newClassIdInput);
        },
        clearSelectedPrimary({ commit }) {
            commit("clearSelectedPrimary");
            commit("clearAllSelectedPrimaryMods");
            commit("clearSelectedPrimaryOverclock");
            commit("clearAllLastSelectedData");
        },
        clearSelectedSecondary({ commit }) {
            commit("clearSelectedSecondary");
            commit("clearAllSelectedSecondaryMods");
            commit("clearSelectedSecondaryOverclock");
            commit("clearAllLastSelectedData");
        },
        clearSelectedEquipment({ commit }) {
            commit("clearSelectedEquipment");
            commit("clearSelectedEquipmentMods");
            commit("clearAllLastSelectedData");
        },
        setSelectedPrimary({ commit }, newLoadoutItem) {
            commit("clearSelectedPrimary");
            commit("clearAllSelectedPrimaryMods");
            commit("clearSelectedPrimaryOverclock");
            commit("clearAllLastSelectedData");
            commit("setSelectedPrimary", newLoadoutItem);
        },
        setSelectedPrimaryMod({ commit, state, dispatch }, selectedModObject) {
            let currentTierSelection = state.selectedPrimaryMods.filter(
                (mod) =>
                    mod.selectedModTier === selectedModObject.selectedModTier
            );
            let sameSelection = false;
            if (state.selectedPrimaryMods.length != 0) {
                // We have selected mods
                if (currentTierSelection.length != 0) {
                    commit(
                        "clearSelectedPrimaryMod",
                        selectedModObject.selectedModTier
                    );
                    if (
                        currentTierSelection[0].selectedModId ==
                        selectedModObject.selectedModId
                    ) {
                        sameSelection = true;
                        // TODO: Clear selected data here as well
                    }
                }
            }
            if (!sameSelection) {
                commit("setSelectedPrimaryMod", {
                    selectedModId: selectedModObject.selectedModId,
                    selectedModTier: selectedModObject.selectedModTier,
                });

                dispatch('setLastSelectedItemAttributes', [selectedModObject.selectedModId, "primary-mod"]);
            }
        },
        setSelectedSecondary({ commit }, newLoadoutItem) {
            commit("clearSelectedSecondary");
            commit("clearAllSelectedSecondaryMods");
            commit("clearSelectedSecondaryOverclock");
            commit("clearAllLastSelectedData");
            commit("setSelectedSecondary", newLoadoutItem);
        },
        setSelectedSecondaryMod({ commit, state }, selectedModObject) {
            let currentTierSelection = state.selectedSecondaryMods.filter(
                (mod) =>
                    mod.selectedModTier === selectedModObject.selectedModTier
            );
            let sameSelection = false;
            if (state.selectedSecondaryMods.length != 0) {
                // We have selected mods
                if (currentTierSelection.length != 0) {
                    commit(
                        "clearSelectedSecondaryMod",
                        selectedModObject.selectedModTier
                    );
                    if (
                        currentTierSelection[0].selectedModId ==
                        selectedModObject.selectedModId
                    ) {
                        sameSelection = true;
                        // TODO: Clear selected data here as well
                    }
                }
            }
            if (!sameSelection) {
                commit("setSelectedSecondaryMod", {
                    selectedModId: selectedModObject.selectedModId,
                    selectedModTier: selectedModObject.selectedModTier,
                });
                // set selected item
                // type primary-mod
                // etc
                // maybe do a call here to also hydrate that object?
            }
        },

        setSelectedEquipment({ commit }, selected) {
            commit("clearSelectedEquipment");
            commit("clearAllLastSelectedData");
            commit("setSelectedEquipment", selected);
        },

        setSelectedEquipmentMod({ commit, dispatch, state }, selectedMod) {
            // Do we have a mod id in this tier, for this equipment already? If so, let's remove all other mods on that tier from store.
            const currentEquipmentId = state.selectedEquipmentId;
            const currentEquipment = state.loadoutClassData?.equipments?.filter(
                (e) => e.id === currentEquipmentId
            );
            let currentTierSelectionMods =
                currentEquipment[0].equipment_mods.filter(
                    (mod) => mod.mod_tier === selectedMod.modTier
                );
            let currentTierSelection = "";
            currentTierSelection = currentTierSelectionMods.filter((mod) =>
                state.selectedEquipmentMods.includes(mod.id)
            );

            let sameSelection = false;

            if (currentEquipment[0]) {
                currentEquipment[0].equipment_mods
                    .filter((m) => m.mod_tier === selectedMod.modTier)
                    .map((m) => commit("clearSelectedEquipmentMod", m.id));
                if (
                    currentTierSelection.length > 0 &&
                    currentTierSelection[0].id == selectedMod.modId
                ) {
                    sameSelection = true;
                    // TODO: Clear selected data here as well
                }
            }

            if (!sameSelection) {
                commit("setSelectedEquipmentMod", selectedMod.modId);
                // set selected item
                // type primary-mod
                // etc
                // maybe do a call here to also hydrate that object?
            }
        },
        setLastSelectedItemAttributes({commit, dispatch, getters, state}, selectedItemArray) {
            // Ok we need to set these items within this method 
            // setLastSelectedItemId
            commit('setLastSelectedItemId',selectedItemArray[0]);            
           
            // setLastSelectedItemType
            commit('setLastSelectedItemType', selectedItemArray[1]); // primary-mod, secondary-mod, primary-oc etc.
            
            // setLastSelectedItemObject
            // need a switch here lad
            let itemDataObject = [];
            switch (selectedItemArray[1]) {
                case 'primary-mod':
                    itemDataObject = getters.getPrimaryModObjectById(selectedItemArray[0]); // it should only return 1, so just get the object
                    commit('clearLastSelectedItemObject');
                    commit('setLastSelectedItemObject', itemDataObject[0]);
                    break;
                case 'secondary-mod':
                    itemDataObject = getters.getSecondaryModObjectById(selectedItemArray[0]); // it should only return 1, so just get the object
                    commit('clearLastSelectedItemObject');
                    commit('setLastSelectedItemObject', itemDataObject[0]);
                    break;

                case 'primary-oc':
                    itemDataObject = getters.selectedPrimaryOverclock;
                    commit('clearLastSelectedItemObject');
                    commit('setLastSelectedItemObject', itemDataObject);
                    break;
                    
                case 'secondary-oc':
                    itemDataObject = getters.selectedSecondaryOverclock;;
                    commit('clearLastSelectedItemObject');
                    commit('setLastSelectedItemObject', itemDataObject);
            
                default:
                    break;
            }

            
        }
    },
    getters: {
        loadoutClassPrimaries: (state) => {
            return state.loadoutClassData?.guns?.filter(
                (gun) => gun.character_slot === 1
            );
        },
        isLoggedIn: (state) => {
            return state.user !== null;
        },
        getLoggedInUserId: (state) => {
            return state.user?.id;
        },
        loadoutClassSecondaries: (state) => {
            return state.loadoutClassData?.guns?.filter(
                (gun) => gun.character_slot === 2
            );
        },
        loadoutClassEquipment: (state) => {
            return state.loadoutClassData?.equipments;
        },
        // TODO: this should probably be getSelectedPrimaryId (store as well)
        getSelectedPrimary: (state) => {
            return state.selectedPrimaryId;
        },
        getSelectedPrimaryDetails: (state) => {
            if (!state.loadoutClassData || !state.selectedPrimaryId) {
                return null;
            }

            return state.loadoutClassData?.guns.find(
                (gun) => gun.id === state.selectedPrimaryId
            );
        },
        selectedPrimaryModIds: (state) => {
            return state.selectedPrimaryMods;
        },
        selectedPrimaryBuildMetricsCombo: (state, getters) => {
            const gun = getters.getSelectedPrimaryDetails;
            const overclockId = getters.selectedPrimaryOverclockId;
            const selectedMods = state.selectedPrimaryMods;

            return buildComboIndexFromGun(gun, overclockId, selectedMods);
        },
        selectedPrimaryOverclockId: (state) => {
            return state.selectedPrimaryOverclockId;
        },
        selectedPrimaryOverclock: (state, getters) => {
            const gun = getters.getSelectedPrimaryDetails;

            return gun?.overclocks.find(
                (o) => o.id === getters.selectedPrimaryOverclockId
            );
        },
        selectedPrimaryMods: (state, getters) => {
            const selectedModIds = getters.selectedPrimaryModIds.map(
                (m) => m.selectedModId
            );

            let selectedMods = [];

            selectedModIds.forEach(function (modId) {
                getters.getSelectedPrimaryDetails?.mods.forEach((mod) => {
                    if (mod.id == modId) {
                        selectedMods.push(mod);
                    }
                });
            });

            return selectedMods;
        },
        getSelectedSecondaryDetails: (state) => {
            if (!state.loadoutClassData || !state.selectedSecondaryId) {
                return null;
            }

            return state.loadoutClassData.guns.find(
                (gun) => gun.id === state.selectedSecondaryId
            );
        },
        selectedSecondaryModIds: (state) => {
            return state.selectedSecondaryMods;
        },
        selectedSecondaryBuildMetricsCombo: (state, getters) => {
            const gun = getters.getSelectedSecondaryDetails;
            const overclockId = getters.selectedSecondaryOverclockId;
            const selectedMods = state.selectedSecondaryMods;

            return buildComboIndexFromGun(gun, overclockId, selectedMods);
        },
        selectedSecondaryOverclockId: (state) => {
            return state.selectedSecondaryOverclockId;
        },
        selectedSecondaryOverclock: (state, getters) => {
            const gun = getters.getSelectedSecondaryDetails;

            return gun?.overclocks.find(
                (o) => o.id === getters.selectedSecondaryOverclockId
            );
        },
        selectedSecondaryMods: (state, getters) => {
            const selectedModIds = getters.selectedSecondaryModIds.map(
                (m) => m.selectedModId
            );

            let selectedMods = [];

            selectedModIds.forEach(function (modId) {
                getters.getSelectedSecondaryDetails?.mods.forEach((mod) => {
                    if (mod.id == modId) {
                        selectedMods.push(mod);
                    }
                });
            });

            return selectedMods;
        },
        getSelectedSecondary: (state) => {
            return state.selectedSecondaryId;
        },
        getSelectedEquipmentId: (state) => {
            return state.selectedEquipmentId;
        },
        selectedEquipmentDetails: (state) => {
            if (!state.loadoutClassData || !state.selectedEquipmentId) {
                return null;
            }

            return state.loadoutClassData?.equipments.find(
                (e) => e.id === state.selectedEquipmentId
            );
        },
        selectedEquipmentMods: (state, getters) => {
            if (!state.loadoutClassData || !state.selectedEquipmentId) {
                return null;
            }

            let selectedMods = [];

            state.selectedEquipmentMods.forEach(function (modId) {
                getters.selectedEquipmentDetails?.equipment_mods.forEach(
                    (mod) => {
                        if (mod.id == modId) {
                            selectedMods.push(mod);
                        }
                    }
                );
            });

            return selectedMods;
        },
        equipmentModIds: (state) => {
            return state.selectedEquipmentMods;
        },
        getSelectedClass: (state) => () => {
            return state.selectedClass;
        },
        getLoadoutDescription: (state) => () => {
            return state.loadoutDescription;
        },
        getLoadingStatus: (state) => {
            return state.loadingStatus;
        },
        // This should be by ID
        getLoadoutClassWeaponByName: (state) => (weaponName) => {
            // Pull the requested class weapon by name, which is stored in components
            return state.loadoutClassData.guns.filter(function (el) {
                return el.name == weaponName;
            });
        },
        getIsSelectedPrimary: (state) => (weaponId) => {
            return state.selectedPrimaryId === weaponId;
        },
        getIsSelectedSecondary: (state) => (weaponId) => {
            return state.selectedSecondaryId === weaponId;
        },
        getIsSelectedEquipment: (state) => (equipmentId) => {
            return state.selectedEquipmentId === equipmentId;
        },
        getIconByName: (state) => (iconName) => {
            return state.icons.default[iconName];
        },
        getOcDataByWeapon: (state) => (gunId) => {
            return state.loadoutClassData.guns.find((gun) => gun.id === gunId)
                .overclocks;
        },
        getIsPrimaryModSelected: (state) => (modId) => {
            return (
                state.selectedPrimaryMods.filter(
                    (mod) => mod.selectedModId === modId
                ).length !== 0
            );
        },
        getIsSecondaryModSelected: (state) => (modId) => {
            return (
                state.selectedSecondaryMods.filter(
                    (mod) => mod.selectedModId === modId
                ).length !== 0
            );
        },
        getIsEquipmentModSelected: (state) => (modId) => {
            return (
                state.selectedEquipmentMods.filter((mod) => mod === modId)
                    .length !== 0
            );
        },
        getPrimaryModObjectById: (state, getters) => (selectedModId) => {
            let primaryWeaponObject = getters.getSelectedPrimaryDetails;
            let lastSelectedPrimaryModArray = primaryWeaponObject.mods.filter((mod) => mod.id === selectedModId);

            return lastSelectedPrimaryModArray; // Should be length 1
        }, 
        getSecondaryModObjectById: (state, getters) => (selectedModId) => {
            let secondaryWeaponObject = getters.getSelectedSecondaryDetails;
            let lastSelectedSecondaryModArray = secondaryWeaponObject.mods.filter((mod) => mod.id === selectedModId);

            return lastSelectedSecondaryModArray; // Should be length 1
        }, 
    },
});
