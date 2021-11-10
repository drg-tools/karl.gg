<template>
    <div class="equipmentCards text-gray-300">

        <div class="flex flex-row justify-around my-5">
            <div>

                <PreviewCard
                    v-if="primary"
                    :name="primary.name"
                    :icon="primary.image"
                    :mods="primaryMods"
                    :selected-mods="selectedPrimaryMods"
                    :overclock="primaryOverclock"
                />

            </div>

            <div>

                <PreviewCard
                    v-if="secondary"
                    :name="secondary.name"
                    :icon="secondary.image"
                    :mods="secondaryMods"
                    :selected-mods="selectedSecondaryMods"
                    :overclock="secondaryOverclock"
                />


            </div>

            <div v-for="equipment in availableEquipment" v-if="equipmentMods && Object.keys(equipmentMods).length > 0">


                <PreviewCard
                    :name="equipment.name"
                    :icon="equipment.icon"
                    :mods="equipment.equipment_mods"
                    :selected-mods="getSelectedModsForEquipment(equipment.id)"
                />
            </div>


        </div>
        <div class="guideAccordion text-gray-300">
            <h1 @click="guideIsOpen = !guideIsOpen" class="text-karl-orange text-xl uppercase p-4">Loadout Guide <i
                :class="guideIsOpen ? 'fas fa-chevron-down invertIcon': 'fas fa-chevron-down'"></i></h1>
            <div v-show="guideIsOpen" class="p-6">
                <viewer :initialValue="loadoutData.description"/>
            </div>
        </div>

    </div>
</template>

<script>
import apolloQueries from '../apolloQueries';
import gql from 'graphql-tag';
import '@toast-ui/editor/dist/toastui-editor-viewer.css';
import {Viewer} from '@toast-ui/vue-editor';
import PreviewCard from "./PreviewCard";

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
    },
    components: {
        viewer: Viewer,
        PreviewCard
    },
    data() {
        return {
            guideIsOpen: false, // closed by default
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
            const mods = _.get(this.equipmentMods, id, []);

            return mods.map(m => m.id)
        },

        async getLoadoutDetails(loadoutId) {

            const response = await this.$apollo.query({
                query: gql`${apolloQueries.loadoutDetails(loadoutId)}`
            });
            

        },

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
