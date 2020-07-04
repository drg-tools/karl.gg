<template>
    <div id="app" class="karlApp">
        <!--<div class="classSelectContainer">
            <ClassSelect :classId="'D'" :name="'Driller'"/>
            <ClassSelect :classId="'E'" :name="'Engineer'"/>
            <ClassSelect :classId="'G'" :name="'Gunner'"/>
            <ClassSelect :classId="'S'" :name="'Scout'"/>
            <ClassSelect :classId="'R'" :name="'Robots'"/>
            <div class="linkArea" v-on:click="getLink()">
                <label for="idCopyTextArea" class="linkText">Generate</label>
                <input id="idCopyTextArea" class="copyTextArea" disabled/>
            </div>
            &lt;!&ndash;upload configuration for management approval&ndash;&gt;
            &lt;!&ndash;todo: use https://sharingbuttons.io/ ?&ndash;&gt;
        </div>-->
        <!--<div>
            &lt;!&ndash; todo: autoscroll on equipment &ndash;&gt;
            <div v-if="selectedClass === 'D'" class="equipmentSelectContainer">
                <EquipmentSelect
                    v-for="(equipment, equipmentId) in drillerEquipment"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :classId="'D'"
                    :equipmentId="equipmentId"
                    :data="equipment"
                />
            </div>
            <div v-if="selectedClass === 'E'" class="equipmentSelectContainer">
                <EquipmentSelect
                    v-for="(equipment, equipmentId) in engineerEquipment"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :classId="'E'"
                    :equipmentId="equipmentId"
                    :data="equipment"
                />
            </div>
            <div v-if="selectedClass === 'G'" class="equipmentSelectContainer">
                <EquipmentSelect
                    v-for="(equipment, equipmentId) in gunnerEquipment"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :classId="'G'"
                    :equipmentId="equipmentId"
                    :data="equipment"
                />
            </div>
            <div v-if="selectedClass === 'S'" class="equipmentSelectContainer">
                <EquipmentSelect
                    v-for="(equipment, equipmentId) in scoutEquipment"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :classId="'S'"
                    :equipmentId="equipmentId"
                    :data="equipment"
                />
            </div>
            <div v-if="selectedClass === 'R'" class="equipmentSelectContainer">
                <EquipmentSelect
                    v-for="(equipment, equipmentId) in robotEquipment"
                    :key="equipmentId"
                    :iconPath="equipment.icon"
                    :name="equipment.name"
                    :classId="'R'"
                    :equipmentId="equipmentId"
                    :data="equipment"
                />
            </div>
        </div>-->
        <div class="mainContainer">
            <StatsDisplay/>
            <ModificationSelect/>
        </div>
        <p class="versionNumber">KARL v1.6.8 Based on DRG v0.29.39685.0</p>

        <!-- todo: start by getting dps stats from api for the chosen build -->
        <!-- todo: /api/guns/1/stats/ABAAC2 ? -->
    </div>
</template>

<script>
    import ClassSelect from './components/ClassSelect.vue';
    import EquipmentSelect from './components/EquipmentSelect.vue';
    import ModificationSelect from './components/ModificationSelect.vue';
    import StatsDisplay from './components/StatsDisplay.vue';
    import store from './store';
    import Lzs from 'lz-string';

    let toastOptions = {
        theme: 'bubble',
        position: 'top-center',
        duration: 3000
    };

    export default {
        name: 'app',
        components: {
            ClassSelect,
            EquipmentSelect,
            ModificationSelect,
            StatsDisplay
        },
        computed: {
            svg() {
                return store.state.testJS;
            },
            selectedClass() {
                return store.state.selected.class;
            },
            drillerEquipment() {
                return store.state.tree.D;
            },
            engineerEquipment() {
                return store.state.tree.E;
            },
            gunnerEquipment() {
                return store.state.tree.G;
            },
            scoutEquipment() {
                return store.state.tree.S;
            },
            robotEquipment() {
                return store.state.tree.R;
            },
            classes() {
                return store.state.tree;
            },
            getStoreState() {
                return store.state;
            }
        },
        methods: {
            getLink() {
                let dataParts = store.state.dataParts;
                let classInFocus = store.state.selected.class;
                let equipmentInFocus = store.state.selected.equipment;
                dataParts[classInFocus][equipmentInFocus].push('focus');
                // todo: this focus mechanic is not ideal, put in object instead of array: {... focus: {class: S, equipment: S2} ...}
                let parts = JSON.stringify(dataParts);
                let uri = `https://surmiran.github.io/karl/?=${Lzs.compressToEncodedURIComponent(parts)}`;
                let copyTextarea = document.getElementById('idCopyTextArea');
                copyTextarea.disabled = false;
                copyTextarea.value = uri;
                copyTextarea.focus();
                copyTextarea.select();
                try {
                    let successful = document.execCommand('copy');

                    if (successful) {
                        this.$toasted.show('Loadout was sent to management for approval (copied to clipboard)', toastOptions);
                    } else {
                        this.$toasted.show('I can\'t feel my beard!', toastOptions);
                    }
                } catch (err) {
                    this.$toasted.show('By the beard! Copying to clipboard was not successful', toastOptions);
                }
                copyTextarea.disabled = true;
            }
        },
        mounted: function () {
            let dataString = window.location.search.substr(2);

            if (dataString) {
                try {
                    let parts = JSON.parse(Lzs.decompressFromEncodedURIComponent(dataString));
                    this.$toasted.show('For Karl!', toastOptions);
                    store.commit('loadFromLink', parts);
                } catch (err) {
                    this.$toasted.show('Management is unhappy about the shared link.', toastOptions);
                    console.log(err);
                }
            }

            fetch('/api/characters')
            .then(response => response.json())
            // .then(data => this.characters = data)
            .then(data => console.log('characters', data));

            fetch('/api/builds')
            .then(response => response.json())
            // .then(data => this.characters = data)
            .then(data => console.log('builds', data));

            fetch('/api/guns')
            .then(response => response.json())
            .then(data => console.log('guns', data));

            fetch('/api/guns/2/mods')
            .then(response => response.json())
            .then(data => console.log('guns/2/mods', data));

            this.$nextTick(function () {
                let storeState = this.getStoreState;
                if (storeState.loadedFromLink && storeState.loadedOverclockFromLink) {
                    let selectedClassId = storeState.selected.class;
                    let selectedEquipmentId = storeState.selected.equipment;
                    let overclockId = storeState.selected.overclock;
                    let selected = false;
                    this.$children[13].selectOverclock(selectedClassId, selectedEquipmentId, overclockId, selected);
                    this.$children[13].$forceUpdate();
                    // todo: this only works for the displayed overclocks, all other shared gear does not show the selected overclock yet..
                    // todo: the whole overclock display needs to be reworked in order for this to work as intended
                }
            });
        }
    };
</script>

<!--<style>
    /*todo: only three different font sizes!*/
    /*todo: web fonts*/

    html {
        height: 100%;
    }

    body {
        display: flex;
        justify-content: center;
        /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#322c20+0,5f5137+100 */
        background: #322c20; /* Old browsers */
        background-repeat: no-repeat;
        background: -moz-linear-gradient(-45deg, #322c20 0%, #5f5137 100%) fixed; /* FF3.6-15 */
        background: -webkit-linear-gradient(-45deg, #322c20 0%, #5f5137 100%) fixed; /* Chrome10-25,Safari5.1-6 */
        background: linear-gradient(135deg, #322c20 0%, #5f5137 100%) fixed; /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#322c20', endColorstr='#5f5137', GradientType=1); /* IE6-9 fallback on horizontal gradient */
        height: 100%;
    }

    h1 {
        display: block;
        font-size: 2em;
        margin-block-start: 0.67em;
        margin-block-end: 0.67em;
        margin-inline-start: 0px;
        margin-inline-end: 0px;
        font-weight: bold;
    }

    h2 {
        display: block;
        font-size: 1.5em;
        margin-block-start: 0.83em;
        margin-block-end: 0.83em;
        margin-inline-start: 0px;
        margin-inline-end: 0px;
        font-weight: bold;
        color: #fffbff;
    }

    h4 {
        display: block;
        margin-block-start: 1.33em;
        margin-block-end: 1.33em;
        margin-inline-start: 0px;
        margin-inline-end: 0px;
        font-weight: bold;
    }

    span {
        line-height: 1.2
    }

    .karlApp {
        font-family: "Avenir", Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        margin-top: 60px;
        width: 1000px;
    }

    .versionNumber {
        font-size: 0.7rem;
        color: rgba(255, 251, 255, 0.2);
    }

    .mainContainer {
        display: flex;
        flex-wrap: wrap;
        /*height: 5rem;*/
    }

    .toasted.bubble {
        background-color: #fc9e00 !important;
        font-family: "Avenir", Helvetica, Arial, sans-serif;
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }

    .linkArea {
        cursor: pointer;
        display: flex;
        flex-direction: column;
        justify-content: center;
        width: 10rem;
        margin-left: auto;
    }

    .linkText {
        cursor: pointer;
        color: #fc9e00;
    }

    .copyTextArea {
        cursor: pointer;
        width: 80%;
    }

    .allCaps {
        text-transform: uppercase;
    }

    .defaultBackground {
        background-color: rgba(0, 0, 0, 0.2);
    }

    .equipment {
        background-color: #5b402d;
        color: #352e1e;
    }

    .equipmentActive {
        background-color: #fc9e00;
        color: #352e1e;
    }

    .equipmentIcon {
        fill: #ada195;
    }

    .equipmentIconActive {
        fill: #fffbff;
    }

    .equipmentText {
        padding-left: 0.5rem;
        padding-right: 1rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background-color: #352e1e;
        color: #ada195;
    }

    .equipmentTextActive {
        padding-left: 0.5rem;
        padding-right: 1rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background-color: #010103;
        color: #fffbff;
    }

    .classSelectContainer {
        display: flex;
        flex-wrap: wrap;
        border-top: 5px solid #fc9e00;
        background-color: #352e1e;
        margin-bottom: 0.5rem;
    }

    .equipmentSelectContainer {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        -ms-overflow-style: -ms-autohiding-scrollbar;
        border-top: 5px solid #fc9e00;
        background-color: #352e1e;
        margin-bottom: 0.5rem;
    }

    .equipmentText:hover {
        color: #fffbff;
    }

    .costList {
        display: flex;
        flex-wrap: wrap;
        color: #fffbff;
    }

    .costListItem {
        display: flex;
        align-items: center;
    }

    .costListItem > img {
        padding-right: 0.2rem;
    }

    .costListItem > span {
        padding-right: 0.6rem;
    }

    @media (max-width: 1024px) {
        h2 {
            font-size: 1.2rem;
        }

        #app {
            width: 95vw;
        }

        .linkArea {
            width: 5rem;
        }
    }

    ::-webkit-scrollbar-button {
        display: none;
        height: 12px;
        border-radius: 0px;
        background-color: #aaa;
    }

    ::-webkit-scrollbar-button:hover {
        background-color: #aaa;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #fc9e00;
        border-radius: 0px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background-color: #ffffff;
        border-radius: 0px;
    }

    ::-webkit-scrollbar-track {
        background-color: #352e1e;
        border-radius: 0px;
    }

    ::-webkit-scrollbar-track:hover {
        background-color: #352e1e;
        border-radius: 0px;
    }

    ::-webkit-scrollbar {
        width: 12px;
        height: 12px;
    }
</style>-->
