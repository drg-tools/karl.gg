<template>
    <div v-if="getLoadingStatus" class="loadingIndicator">
        <img src="/assets/img/karl-spinner-free.gif" alt="loading..."/>
    </div>
    <div v-else class="flex flex-row justify-around my-5">

        <div>
            <router-link
                to="/primary-builder">
                <PreviewCard
                    v-if="getSelectedPrimaryDetails"
                    :equipment="getSelectedPrimaryDetails"
                    :selected-mods="selectedPrimaryModIds"
                    :selected-overclock-id="selectedPrimaryOverclockId"
                />
            </router-link>

            <div class="text-center" v-if="!getSelectedPrimaryDetails">
                <h2>Select a Primary</h2>
                <RouterSelectButton routeTo="/primary-builder"/>
            </div>
        </div>

        <div>

            <router-link
                to="/secondary-builder">
                <PreviewCard
                    v-if="getSelectedSecondaryDetails"
                    :equipment="getSelectedSecondaryDetails"
                    :selected-mods="selectedSecondaryModIds"
                    :selected-overclock-id="selectedSecondaryOverclockId"
                />
            </router-link>

            <div class="text-center" v-if="!getSelectedSecondaryDetails">
                <h2>Select a Secondary</h2>
                <RouterSelectButton routeTo="/secondary-builder"/>
            </div>
        </div>

        <div v-for="equipment in loadoutClassEquipment" v-if="getSelectedEquipment">
            <router-link
                to="/equipment-builder">
                <PreviewCard
                    :equipment="equipment"
                    :selected-mods="equipmentModIds"
                />
            </router-link>
        </div>

        <div class="text-center" v-if="!getSelectedEquipment">
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
