<template>
    <div v-if="!dataReady" class="loadingIndicator">
        <img src="../assets/img/karl-spinner-free.gif" alt="loading...">
    </div>
    <div v-else-if="dataReady" class="table">
        <!-- todo: custom filter by class -->
        <div class="classFilterContainer">
            <h1>Filter by class:</h1>
            <div v-on:click="toggleFilter('D')" class="classFilter" :class="[classFilter.D ? 'classFilterActive' : '']">
                <img src="../assets/img/50px-D_icon-hex.png" class="classIcon"/>
                <h2>Driller</h2>
            </div>
            <div v-on:click="toggleFilter('E')" class="classFilter" :class="[classFilter.E ? 'classFilterActive' : '']">
                <img src="../assets/img/50px-E_icon-hex.png" class="classIcon"/>
                <h2>Engineer</h2>
            </div>
            <div v-on:click="toggleFilter('G')" class="classFilter" :class="[classFilter.G ? 'classFilterActive' : '']">
                <img src="../assets/img/50px-G_icon-hex.png" class="classIcon"/>
                <h2>Gunner</h2>
            </div>
            <div v-on:click="toggleFilter('S')" class="classFilter" :class="[classFilter.S ? 'classFilterActive' : '']">
                <img src="../assets/img/50px-S_icon-hex.png" class="classIcon"/>
                <h2>Scout</h2>
            </div>
        </div>
        <vue-good-table
            @on-row-click="onRowClick"
            :columns="columns"
            :rows="tableData"
            :sort-options="{
                enabled: true,
                initialSortBy: {field: 'votes', type: 'desc'}
            }"
            :search-options="{
                enabled: true,
                placeholder: 'Search Loadouts',
            }"
            styleClass="vgt-table"
            :fixed-header="true">
            <template slot="table-row" slot-scope="props">
                <span v-if="props.column.field === 'classId' && props.row.classId === 'D'">
                    <img src="../assets/img/50px-D_icon-hex.png" class="classIcon" alt="Driller icon"/>
                </span>
                <span v-else-if="props.column.field === 'classId' && props.row.classId === 'E'">
                    <img src="../assets/img/50px-E_icon-hex.png" class="classIcon" alt="Engineer icon"/>
                </span>
                <span v-else-if="props.column.field === 'classId' && props.row.classId === 'G'">
                    <img src="../assets/img/50px-G_icon-hex.png" class="classIcon" alt="Gunner icon"/>
                </span>
                <span v-else-if="props.column.field === 'classId' && props.row.classId === 'S'">
                    <img src="../assets/img/50px-S_icon-hex.png" class="classIcon" alt="Scout icon"/>
                </span>
                <span v-else-if="props.column.field === 'primary'">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 180 90"
                         height="90%"
                         preserveAspectRatio="xMidYMid meet"
                    ><path :d="getPath(props.row.primary)"></path></svg>
                </span>
                <span v-else-if="props.column.field === 'secondary'">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 180 90"
                         height="90%"
                         preserveAspectRatio="xMidYMid meet"
                    ><path :d="getPath(props.row.secondary)"></path></svg>
                </span>
            </template>
        </vue-good-table>
    </div>
</template>

<script>
    import store from '../store';
    import apolloQueries from '../apolloQueries';
    import gql from 'graphql-tag';

    import 'vue-good-table/dist/vue-good-table.css';
    import {VueGoodTable} from 'vue-good-table';

    export default {
        name: 'BrowseLoadouts',

        components: {
            VueGoodTable
        },
        data() {
            return {
                classFilter: {
                    D: false,
                    E: false,
                    G: false,
                    S: false
                },
                columns: [
                    {
                        label: 'classId',
                        width: '5rem',
                        field: 'classId'
                    },
                    {
                        label: 'Name',
                        field: 'name'
                    },
                    {
                        label: 'Author',
                        field: 'author'
                    },
                    {
                        label: 'Primary',
                        width: '6rem',
                        field: 'primary',
                        tdClass: 'tableWeaponIcon'
                    },
                    {
                        label: 'Secondary',
                        width: '6rem',
                        field: 'secondary',
                        tdClass: 'tableWeaponIcon'
                    },
                    {
                        label: 'Salutes',
                        field: 'votes',
                        type: 'number',
                        width: '5rem'
                    },
                    {
                        label: 'Last update',
                        field: 'lastUpdate',
                        type: 'date',
                        dateInputFormat: 'yyyy-MM-dd',
                        dateOutputFormat: 'dd.MM.yyyy',
                        width: '8rem'
                    }
                ],
                rows: this.getBrowseLoadouts()
            };
        },
        computed: {
            dataReady() {
                return store.state.browseDataReady;
            },
            loadoutRows() {
                return store.state.browseLoadouts;
            },
            tableData: function () {
                let filterby = Object.values(this.classFilter).filter(value => !!value);
                let rows = this.loadoutRows;
                if (filterby.length > 0) {
                    return rows.filter(row => this.classFilter[row.classId]);
                } else {
                    return rows;
                }

            }
        },
        methods: {
            async getBrowseLoadouts() {
                if (store.state.browseLoadouts.length > 0) {
                    return store.state.browseLoadouts;
                }
                const response = await this.$apollo.query({
                    query: gql`${apolloQueries.popularLoadouts}`
                });
                store.commit('setBrowseLoadouts', {loadouts: response.data.loadouts});
                return store.state.browseLoadouts;
            },
            onRowClick: function (params) {
                console.log('nav to preview', params.row.classId, params.row.loadoutId);
                window.location.href = `${window.location.origin}/preview/${params.row.loadoutId}`;
            },
            toggleFilter: function (classId) {
                Vue.set(this.classFilter, classId, !this.classFilter[classId]);
            },
            getPath: function (id) {
                // get svg path by weapon id. workaround for improved performance
                return store.state.icons.equipment[id].replace('<path d="', '').replace('"/>', '');
            }
        },
        apollo: {},
        mounted: function () {
            console.log('mounted browse table');
            this.getBrowseLoadouts().then((browseLoadouts) => {
                store.commit('setBrowseDataReady', {ready: true});
                console.log('done with browse loadouts', browseLoadouts);
            });
        }
    };
</script>

<style>
    /* todo: move non scoped styles into global css*/
    .classFilterContainer {
        border-top: 5px solid #fc9e00;
        display: flex;
        flex-wrap: wrap;
    }

    .classFilterContainer > h1 {
        margin: 0;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        padding-right: 2rem
    }

    .classFilter {
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .classFilter:hover {
        background: #fc9e00;
    }

    .classFilter > h2 {
        color: #ADA195;
        padding-left: 1rem;
        padding-right: 2rem;
        margin-block-start: 0;
        margin-block-end: 0;
        margin-inline-start: 0;
        margin-inline-end: 0;
    }

    .classFilter > img {
        opacity: 0.4;
    }

    .classFilterActive > h2 {
        color: #ffffff;
    }

    .classFilterActive > img {
        opacity: 1;
    }

    .table {
        width: 100%
    }

    table.vgt-table {
        border: none;
        border-right: 1px solid #352E1E
    }

    table.vgt-table thead th {
        background: #fc9e00;
        color: #000000;
        font-family: BebasNeue, sans-serif;
    }

    table.vgt-table thead tr {
        border-bottom: 3px solid #4d422e;
    }

    table.vgt-table th {
        border: 1px solid #fc9e00
    }

    table.vgt-table tr {
        background: #352E1E;
        cursor: pointer;
    }

    table.vgt-table td {
        color: #ffffff;
        border-bottom: 3px solid #4d422e;
    }

    table.vgt-table td.tableClassIcon {
        padding-top: 0.2rem;
        padding-bottom: 0.1rem;
    }

    table.vgt-table td.tableWeaponIcon {
        padding-top: 0.2rem;
        padding-bottom: 0.1rem;
        height: 3rem;
        fill: #ADA195;
    }

    table.vgt-table tr.clickable:hover {
        background-color: #fc9e00;
    }

    table.vgt-table tr.clickable:hover svg {
        fill: #ffffff;
    }

    table.vgt-table thead th.sorting-desc:before {
        border-top: 5px solid #000000;
    }

    table.vgt-table thead th.sorting-asc:after {
        border-bottom: 5px solid #000000;
    }

    .vgt-global-search {
        border: none;
        margin-bottom: 1rem;
        background: #fc9e00;
    }

    .vgt-input {
        background-color: #4d422e;
        color: #fffbff;
        border-color: #fc9e00;
        border-radius: 0;
    }

    .vgt-input:focus {
        border-color: #fffbff;
    }

    .vgt-input::placeholder {
        color: #fffbff;
        opacity: 0.6;
    }

    .input__icon {
        border-color: #ADA195
    }


</style>
