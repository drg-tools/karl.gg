<template>
    <div v-if="getLoadingStatus" class="loadingIndicator">
        <img src="/assets/img/karl-spinner-free.gif" alt="loading..."/>
    </div>
    <div v-else class="flex flex-row justify-around my-5">

        <div>
            <router-link
                to="/primary-builder">
                <div class="border-transparent border-2 p-4 hover:border-karl-orange">
                    <PreviewCard
                        v-if="getSelectedPrimaryDetails"
                        :name="getSelectedPrimaryDetails.name"
                        :icon="getSelectedPrimaryDetails.image"
                        :mods="getSelectedPrimaryDetails.mods"
                        :selected-mods="selectedPrimaryModIds.map(m => m.selectedModId)"
                        :overclock="getSelectedPrimaryDetails.overclocks.find(o => o.id === selectedPrimaryOverclockId)"
                    />
                </div>
            </router-link>

            <div class="text-center" v-if="!getSelectedPrimaryDetails">
                <h2>Select a Primary</h2>
                <RouterSelectButton routeTo="/primary-builder"/>
            </div>
        </div>

        <div>

            <router-link
                to="/secondary-builder">
                <div class="border-transparent border-2 p-4 hover:border-karl-orange">
                    <PreviewCard
                        v-if="getSelectedSecondaryDetails"
                        :name="getSelectedSecondaryDetails.name"
                        :icon="getSelectedSecondaryDetails.image"
                        :mods="getSelectedSecondaryDetails.mods"
                        :selected-mods="selectedSecondaryModIds.map(m => m.selectedModId)"
                        :overclock="getSelectedSecondaryDetails.overclocks.find(o => o.id === selectedSecondaryOverclockId)"
                    />
                </div>
            </router-link>

            <div class="text-center" v-if="!getSelectedSecondaryDetails">
                <h2>Select a Secondary</h2>
                <RouterSelectButton routeTo="/secondary-builder"/>
            </div>
        </div>

        <div v-for="equipment in loadoutClassEquipment" v-if="equipmentModIds.length > 0">
            <router-link
                to="/equipment-builder">
                <div class="border-transparent border-2 p-4 hover:border-karl-orange">
                    <PreviewCard
                        :name="equipment.name"
                        :icon="equipment.icon"
                        :mods="equipment.equipment_mods"
                        :selected-mods="equipmentModIds"
                    />
                </div>
            </router-link>
        </div>

        <div class="text-center" v-if="equipmentModIds.length === 0">
            <h2>Select Equipment</h2>
            <RouterSelectButton routeTo="/equipment-builder"/>
        </div>

    </div>
</template>

<script>
import RouterSelectButton from "./RouterSelectButton";
import PreviewCard from "./PreviewCard";
import {mapGetters} from "vuex";

export default {
    name: "SelectContainer",
    components: {RouterSelectButton, PreviewCard},

    methods: {
        goBack() {
            window.history.length > 1
                ? this.$router.go(-1)
                : this.$router.push("/");
        },
    },
    computed: {
        ...mapGetters([
            'selectedPrimaryOverclockId',
            'getSelectedPrimaryDetails',
            'selectedPrimaryModIds',
            'selectedSecondaryOverclockId',
            'getSelectedSecondaryDetails',
            'selectedSecondaryModIds',
            'getLoadingStatus',
            'loadoutClassEquipment',
            'getSelectedEquipment',
            'equipmentModIds'
        ])
    },
};
</script>
