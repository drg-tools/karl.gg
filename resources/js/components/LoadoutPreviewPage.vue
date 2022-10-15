<template>
    <div class="equipmentCards text-gray-300" id="equipmentCards">

        <div class="flex flex-row flex-wrap justify-around my-5">
            <div class="mb-4" v-if="primary">

                <PreviewCard
                    :name="primary.name"
                    :icon="primary.image"
                    :mods="primaryMods"
                    :selected-mods="selectedPrimaryMods"
                    :overclock="primaryOverclock"
                />

            </div>

            <div class="mb-4" v-if="secondary">

                <PreviewCard
                    :name="secondary.name"
                    :icon="secondary.image"
                    :mods="secondaryMods"
                    :selected-mods="selectedSecondaryMods"
                    :overclock="secondaryOverclock"
                />


            </div>

            <div v-for="(selectedMods, equipmentId) in equipmentMods"
                 v-if="equipmentMods && Object.keys(equipmentMods).length > 0"
                 class="mb-4"
            >

                <PreviewCard
                    :name="getEquipmentFromAvailable(equipmentId).name"
                    :icon="getEquipmentFromAvailable(equipmentId).icon"
                    :mods="getEquipmentFromAvailable(equipmentId).equipment_mods"
                    :selected-mods="getSelectedModsForEquipment(equipmentId)"
                />
            </div>

            <div class="mb-4" v-if="throwable">

                <div
                        class="
                            border-transparent border-2
                            p-4
                            hover:border-karl-orange
                        "
                    >
                        <img
                        class="w-24 p-4 filter invert mx-auto"
                        :src="`/assets/${throwable.icon}.svg`" :alt="`${throwable.name} icon`"/>

                        <div class="text-gray-300">{{throwable.name}}</div>

                    </div>

            </div>


        </div>
        <div class="guideAccordion text-gray-300">
            <h2 @click="guideIsOpen = !guideIsOpen" class="text-karl-orange text-xl uppercase p-4">Loadout Guide <i
                :class="guideIsOpen ? 'fas fa-chevron-down invertIcon': 'fas fa-chevron-down'"></i></h2>
            <div v-show="guideIsOpen" class="p-6">
                <v-md-preview :text="loadoutData.description"></v-md-preview>
            </div>
        </div>

    </div>
</template>

<script>
import PreviewCard from "./PreviewCard";
import get from 'lodash-es/get';

export default {
    name: 'LoadoutPreviewPage',
    props: {
        loadoutData: Object,
        primary: Object,
        primaryMods: {
            type: Array,
            default() {
                return []
            }
        },
        secondary: Object,
        secondaryMods: {
            type: Array,
            default() {
                return []
            }
        },
        overclocks: {
            type: Array,
            default() {
                return []
            }
        },
        availableEquipment: {
            type: Array,
            default() {
                return []
            }
        },
        equipmentMods: Object,
        throwable: Object,
    },
    components: {
        PreviewCard
    },
    data() {
        return {
            guideIsOpen: true, // closed by default
        }
    },
    methods: {
        /**
         * Equipment Mods are passed in as an object keyed by the equipment id for each equipment that has mods for this
         * loadout. This function will grab the mods from the specified equipment id that are present in this loadout.
         *
         * @param id
         * @returns Array
         */
        getSelectedModsForEquipment(id) {
            const mods = get(this.equipmentMods, id, []);

            return mods.map(m => m.id)
        },

        /**
         * Get equipment from available equipment by id
         *
         * @param id
         * @returns {*}
         */
        getEquipmentFromAvailable(id) {
            return this.availableEquipment.find(e => e.id === parseInt(id));
        }

    },
    computed: {
        selectedPrimaryMods: function () {
            return this.loadoutData.mods
                .filter(mod => mod.gun_id === this.primary.id)
                .map(mod => mod.id);
        },
        primaryOverclock: function () {
            return this.overclocks?.find(oc => oc.gun_id === this.primary.id);
        },
        selectedSecondaryMods: function () {
            return this.loadoutData.mods
                .filter(mod => mod.gun_id === this.secondary.id)
                .map(mod => mod.id);
        },
        secondaryOverclock: function () {
            return this.overclocks?.find(oc => oc.gun_id === this.secondary.id)
        }
    }
};
</script>
