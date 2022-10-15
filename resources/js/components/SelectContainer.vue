<template>
    <div v-if="getLoadingStatus" class="loadingIndicator">
        <img src="/assets/img/karl-spinner-free.gif" alt="loading..." />
    </div>
    <div v-else class="flex flex-row flex-wrap gap-4 justify-around items-center my-5">
        <div>
            <router-link v-if="getSelectedPrimaryDetails" to="/primary-builder">
                <div
                    class="
                        border-transparent border-2
                        p-4
                        hover:border-karl-orange
                    "
                >
                    <PreviewCard
                        :name="getSelectedPrimaryDetails.name"
                        :icon="getSelectedPrimaryDetails.image"
                        :mods="getSelectedPrimaryDetails.mods"
                        :selected-mods="
                            selectedPrimaryModIds.map((m) => m.selectedModId)
                        "
                        :overclock="
                            getSelectedPrimaryDetails.overclocks.find(
                                (o) => o.id === selectedPrimaryOverclockId
                            )
                        "
                    />
                </div>
            </router-link>
            <div class="flex justify-center">
                <button
                    v-on:click="clearSelectedPrimary"
                    v-if="getSelectedPrimaryDetails"
                    class="
                        inline-flex
                        items-center
                        text-center
                        px-4
                        py-2
                        border border-transparent
                        text-sm
                        font-bold
                        rounded-md
                        shadow-sm
                        text-white
                        bg-red-500
                        hover:bg-red-700
                        focus:outline-none
                        focus:ring-2
                        focus:ring-offset-2
                        focus:ring-red-500
                        w-full
                        md:w-auto
                    "
                >
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>

            <div class="text-center" v-if="!getSelectedPrimaryDetails">
                <h2>Select a Primary</h2>
                <RouterSelectButton routeTo="/primary-builder" />
            </div>
        </div>

        <div>
            <router-link
                v-if="getSelectedSecondaryDetails"
                to="/secondary-builder"
            >
                <div
                    class="
                        border-transparent border-2
                        p-4
                        hover:border-karl-orange
                    "
                >
                    <PreviewCard
                        :name="getSelectedSecondaryDetails.name"
                        :icon="getSelectedSecondaryDetails.image"
                        :mods="getSelectedSecondaryDetails.mods"
                        :selected-mods="
                            selectedSecondaryModIds.map((m) => m.selectedModId)
                        "
                        :overclock="
                            getSelectedSecondaryDetails.overclocks.find(
                                (o) => o.id === selectedSecondaryOverclockId
                            )
                        "
                    />
                </div>
            </router-link>
            <div class="flex justify-center">
                <button
                    v-on:click="clearSelectedSecondary"
                    v-if="getSelectedSecondaryDetails"
                    class="
                        inline-flex
                        items-center
                        text-center
                        px-4
                        py-2
                        border border-transparent
                        text-sm
                        font-bold
                        rounded-md
                        shadow-sm
                        text-white
                        bg-red-500
                        hover:bg-red-700
                        focus:outline-none
                        focus:ring-2
                        focus:ring-offset-2
                        focus:ring-red-500
                        w-full
                        md:w-auto
                    "
                >
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>

            <div class="text-center" v-if="!getSelectedSecondaryDetails">
                <h2>Select a Secondary</h2>
                <RouterSelectButton routeTo="/secondary-builder" />
            </div>
        </div>
        <div class="flex items-center flex-col">
            <div class="flex flex-row">
                <div
                    v-for="equipment in loadoutClassEquipment"
                    v-if="equipmentModIds.length > 0"
                >
                    <router-link to="/equipment-builder">
                        <div
                            @click="setSelectedEquipment(equipment.id)"
                            class="
                                border-transparent border-2
                                p-4
                                hover:border-karl-orange
                            "
                        >
                            <PreviewCard
                                :name="equipment.name"
                                :icon="equipment.icon"
                                :mods="equipment.equipment_mods"
                                :selected-mods="equipmentModIds"
                            />
                        </div>
                    </router-link>
                </div>
            </div>
            <div class="flex flex-row justify-center">
                <button
                    v-on:click="clearSelectedEquipment"
                    v-if="equipmentModIds.length > 0"
                    class="
                        inline-flex
                        items-center
                        text-center
                        px-4
                        py-2
                        border border-transparent
                        text-sm
                        font-bold
                        rounded-md
                        shadow-sm
                        text-white
                        bg-red-500
                        hover:bg-red-700
                        focus:outline-none
                        focus:ring-2
                        focus:ring-offset-2
                        focus:ring-red-500
                        w-full
                        md:w-auto
                    "
                >
                <i class="fas fa-trash-alt mr-2"></i> Clear All
                </button>
            </div>

            <div class="text-center" v-if="equipmentModIds.length === 0">
                <h2>Select Equipment</h2>
                <RouterSelectButton routeTo="/equipment-builder" />
            </div>

            
        </div>
        <div>
            <router-link
                v-if="getSelectedSecondaryDetails"
                to="/secondary-builder"
            >
                <div
                    class="
                        border-transparent border-2
                        p-4
                        hover:border-karl-orange
                    "
                >
                    <PreviewCard
                        :name="getSelectedSecondaryDetails.name"
                        :icon="getSelectedSecondaryDetails.image"
                        :mods="getSelectedSecondaryDetails.mods"
                        :selected-mods="
                            selectedSecondaryModIds.map((m) => m.selectedModId)
                        "
                        :overclock="
                            getSelectedSecondaryDetails.overclocks.find(
                                (o) => o.id === selectedSecondaryOverclockId
                            )
                        "
                    />
                </div>
            </router-link>
            
        </div>
    </div>
</template>

<script>
import RouterSelectButton from "./RouterSelectButton";
import PreviewCard from "./PreviewCard";
import { mapActions, mapGetters } from "vuex";

export default {
    name: "SelectContainer",
    components: { RouterSelectButton, PreviewCard },
    // TODO: Update actions/getters to ensure it's all right
    methods: {
        goBack() {
            window.history.length > 1
                ? this.$router.go(-1)
                : this.$router.push("/");
        },
        ...mapActions([
            "setSelectedEquipment",
            "clearSelectedSecondary",
            "clearSelectedPrimary",
            "clearSelectedEquipment",
            "clearSelectedThrowable",
        ]),
    },
    computed: {
        ...mapGetters([
            "selectedPrimaryOverclockId",
            "getSelectedPrimaryDetails",
            "selectedPrimaryModIds",
            "selectedSecondaryOverclockId",
            "getSelectedThrowableId",
            "getSelectedSecondaryDetails",
            "selectedSecondaryModIds",
            "getLoadingStatus",
            "loadoutClassEquipment",
            "getSelectedEquipmentId",
            "equipmentModIds",
        ]),
    },
};
</script>
