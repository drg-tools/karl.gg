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
        loadoutName: "", // TODO: In the component Debounce on this
        loadoutDescription: "", // TODO: In the component Debounce on this
        loadoutClassData: "", // Use this as source of truth, only call this. Save ID's in other manipulators
        icons: IconList,
        selectedPrimary: "",
        selectedPrimaryMods: [],
        selectedPrimaryOverclock: "",
        selectedSecondary: "",
        selectedSecondaryMods: [],
        selectedSecondaryOverclock: "",
        selectedEquipment: "",
        selectedEquipmentMods: [],
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
            state.selectedPrimary = newValue;
        },
        clearSelectedPrimary(state) {
            state.selectedPrimary = "";
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
            state.selectedPrimaryOverclock = newValue;
        },
        clearSelectedPrimaryOverclock(state) {
            state.selectedPrimaryOverclock = "";
        },
        setSelectedSecondary(state, newValue) {
            state.selectedSecondary = newValue;
        },
        clearSelectedSecondary(state) {
            state.selectedSecondary = "";
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
            state.selectedSecondaryOverclock = newValue;
        },
        clearSelectedSecondaryOverclock(state) {
            state.selectedSecondaryOverclock = "";
        },
        setSelectedEquipment(state, newValue) {
            state.selectedEquipment = newValue;
        },
        clearSelectedEquipment(state) {
            state.selectedEquipment = "";
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
    },
    actions: {
        async saveLoadout({ state, commit, dispatch }, updateId) {
            if (state.loadoutName === "") {
                // loadout name can't be null
                // This will be handled by frontend validation, but consider this server-side as a double-check
                console.log("loadout name is requred");
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

            if (state.selectedPrimaryOverclock != "") {
                combinedOverClockIdArray.push(state.selectedPrimaryOverclock);
                // Need to make array of strings ID's for gql
                combinedOverClockIdArray = combinedOverClockIdArray.map(Number);
            }

            if (state.selectedSecondaryOverclock != "") {
                combinedOverClockIdArray.push(state.selectedSecondaryOverclock);
                // Need to make array of strings ID's for gql
                combinedOverClockIdArray = combinedOverClockIdArray.map(Number);
            }

            console.log("combined mods:");
            console.log(combinedModArray);
            console.log("combined ocs:");
            console.log(combinedOverClockIdArray);

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
                console.log("updated id ");
                console.log(updateId);
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
                console.log("edit vars");
                console.log(variables);
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
            console.log(loadoutData);
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
            console.log("edit params");
            console.log(params);
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
            
            if(loadoutData?.description) {
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
            if(primaryGunMods[0]?.gun?.id) {
                commit("setSelectedPrimary", primaryGunMods[0]?.gun?.id);
                primaryGunMods = primaryGunMods.map((mod) => ({
                    selectedModId: mod.id,
                    selectedModTier: mod.mod_tier,
                }));
                commit("setAllSelectedPrimaryMods", primaryGunMods);
            }

            
            if(secondaryGunMods[0]?.gun?.id) {
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

            if(selectedPrimaryOverclock[0]?.id) {
                commit(
                    "setSelectedPrimaryOverclock",
                    selectedPrimaryOverclock[0]?.id
                );
            }

            if(selectedSecondaryOverclock[0]?.id) {
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

            if(selectedEquipmentId) {
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

            // TODO: add a speed bump on the class selection if you have class data

            // TODO: Clear button to allow you to clear the whole current state

            // dispatch an action which will commit our new class data to store
            dispatch("hydrateClassData", newClassIdInput);

            // Set newly selected class only after we have:
            // 1. Deleted old class data
            // 2. Hydrated new class data
            commit("setSelectedClass", newClassIdInput);
        },
        clearSelectedPrimary({commit}) {
            commit("clearSelectedPrimary");
            commit("clearAllSelectedPrimaryMods");
            commit("clearSelectedPrimaryOverclock");
        },
        clearSelectedSecondary({commit}) {
            commit("clearSelectedSecondary");
            commit("clearAllSelectedSecondaryMods");
            commit("clearSelectedSecondaryOverclock");
        },
        clearSelectedEquipment({commit}) {
            commit("clearSelectedEquipment");
            commit("clearSelectedEquipmentMods");
        },
        setSelectedPrimary({ commit }, newLoadoutItem) {
            commit("clearSelectedPrimary");
            commit("clearAllSelectedPrimaryMods");
            commit("clearSelectedPrimaryOverclock");
            commit("setSelectedPrimary", newLoadoutItem);
        },
        setSelectedPrimaryMod({ commit, state }, selectedModObject) {
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
                    }
                }
            }
            if (!sameSelection) {
                commit("setSelectedPrimaryMod", {
                    selectedModId: selectedModObject.selectedModId,
                    selectedModTier: selectedModObject.selectedModTier,
                });
            }
        },
        setSelectedSecondary({ commit }, newLoadoutItem) {
            commit("clearSelectedSecondary");
            commit("clearAllSelectedSecondaryMods");
            commit("clearSelectedSecondaryOverclock");
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
                    }
                }
            }
            if (!sameSelection) {
                commit("setSelectedSecondaryMod", {
                    selectedModId: selectedModObject.selectedModId,
                    selectedModTier: selectedModObject.selectedModTier,
                });
            }
        },

        setSelectedEquipment({ commit }, selected) {
            commit("clearSelectedEquipment");
            commit("setSelectedEquipment", selected);
        },

        setSelectedEquipmentMod({ commit, dispatch, state }, selectedMod) {
            // Do we have a mod id in this tier, for this equipment already? If so, let's remove all other mods on that tier from store.
            const currentEquipmentId = state.selectedEquipment;
            const currentEquipment = state.loadoutClassData?.equipments?.filter(
                (e) => e.id === currentEquipmentId
            );

            if (currentEquipment[0]) {
                currentEquipment[0].equipment_mods
                    .filter((m) => m.mod_tier === selectedMod.modTier)
                    .map((m) => commit("clearSelectedEquipmentMod", m.id));
            }

            commit("setSelectedEquipmentMod", selectedMod.modId);
        },
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
            return state.selectedPrimary;
        },
        getSelectedPrimaryDetails: (state) => {
            if (!state.loadoutClassData) {
                return null;
            }

            return state.loadoutClassData.guns.find(
                (gun) => gun.id === state.selectedPrimary
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
            return state.selectedPrimaryOverclock;
        },
        getSelectedSecondaryDetails: (state) => {
            if (!state.loadoutClassData) {
                return null;
            }

            return state.loadoutClassData.guns.find(
                (gun) => gun.id === state.selectedSecondary
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
            return state.selectedSecondaryOverclock;
        },
        getSelectedSecondary: (state) => {
            return state.selectedSecondary;
        },
        getSelectedEquipment: (state) => {
            return state.selectedEquipment;
        },
        equipmentModIds: (state) => {
            return state.selectedEquipmentMods;
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
                return el.name == weaponName;
            });
        },
        getIsSelectedPrimary: (state) => (weaponId) => {
            return state.selectedPrimary === weaponId;
        },
        getIsSelectedSecondary: (state) => (weaponId) => {
            return state.selectedSecondary === weaponId;
        },
        getIsSelectedEquipment: (state) => (equipmentId) => {
            return state.selectedEquipment === equipmentId;
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
        getSelectedModCosts: (state) => (itemType) => {
            // TODO: Might need a whole-class version of this component
            // TODO: Update to be whatever weapon we're on

            // Need to do a check for gun or equipment...we may pass that in as a prop to this getter
            // hardcoded for now
            if (state.loadoutClassData != "") {
                // filter selected mods for our mod ids
                let selectedModIds = [];
                let selectedItemId = "";
                switch (itemType) {
                    case "primary":
                        selectedModIds = state.selectedPrimaryMods.map(
                            (a) => a.selectedModId
                        );
                        selectedItemId = state.selectedPrimary;
                        break;
                    case "secondary":
                        selectedModIds = state.selectedSecondaryMods.map(
                            (a) => a.selectedModId
                        );
                        selectedItemId = state.selectedSecondary;
                        break;
                    case "equipment":
                        selectedModIds = state.selectedEquipmentMods;
                        selectedItemId = state.selectedEquipment;
                        break;

                    default:
                        break;
                }
                let selectedOcId = null;

                if (
                    state.selectedPrimaryOverclock != "" &&
                    itemType === "primary"
                ) {
                    selectedOcId = state.selectedPrimaryOverclock;
                } else if (
                    state.selectedSecondaryOverclock != "" &&
                    itemType === "secondary"
                ) {
                    selectedOcId = state.selectedSecondaryOverclock;
                }
                let mainItem = '';
                let itemMods = [];
                // TODO: FIx for equipment
                if(itemType === "primary" || itemType === "secondary") {
                    mainItem = state.loadoutClassData.guns.filter(
                        (gun) => gun.id == selectedItemId
                    );
                    itemMods = mainItem[0].mods;
                } else {
                    mainItem = state.loadoutClassData.equipments.filter(
                        (eq) => eq.id == selectedItemId
                    );
                    itemMods = mainItem[0].equipment_mods;
                }
                
                // This is throwing an error on secondaries sometimes
                
                

                // filter selected primary mods
                let selectedModArray = [];

                selectedModIds.forEach(function (modId) {
                    itemMods.forEach((mod) => {
                        if (mod.id == modId) {
                            selectedModArray.push(mod);
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
                    let overclockObject = mainItem[0].overclocks.filter(
                        (overclock) => overclock.id == selectedOcId
                    );

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
            let itemObject = "";
            let itemModArray = "";

            if (boolEquipment === true) {
                itemObject = state.loadoutClassData?.equipments.filter(
                    (equipment) => equipment.id == itemId
                );
                itemModArray = itemObject[0].equipment_mods;
            } else {
                itemObject = state.loadoutClassData?.guns.filter(
                    (gun) => gun.id == itemId
                );
                itemModArray = itemObject[0].mods;
            }
            return itemModArray;
        },
    },
});
