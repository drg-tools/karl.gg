<template>
    <div class="table">
        <!-- todo: custom filter by class -->
        <vue-good-table
            @on-row-click="onRowClick"
            :columns="columns"
            :rows="rows"
            :sort-options="{
                enabled: true,
                initialSortBy: {field: 'salutes', type: 'desc'}
            }"
            :search-options="{
                enabled: true,
                placeholder: 'Search Loadouts',
            }"
            styleClass="vgt-table"
            :fixed-header="true"/>
    </div>
</template>

<script>
    import store from '../store';

    import 'vue-good-table/dist/vue-good-table.css';
    import {VueGoodTable} from 'vue-good-table';

    export default {
        name: 'BrowseLoadouts',

        components: {
            VueGoodTable
        },
        data() {
            return {
                columns: [
                    {
                        label: 'Class',
                        width: '5rem',
                        field: this.getClassIcon,
                        html: true,
                        tdClass: 'tableClassIcon'
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
                        field: this.getPrimaryIcon,
                        html: true,
                        tdClass: 'tableWeaponIcon'
                    },
                    {
                        label: 'Secondary',
                        width: '6rem',
                        field: this.getSecondaryIcon,
                        html: true,
                        tdClass: 'tableWeaponIcon'
                    },
                    {
                        label: 'Salutes',
                        field: 'salutes',
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
                rows: [
                    {
                        loadoutId: '111111',
                        iconPath: '50px-Driller_icon.png',
                        name: 'Karl\'s Freezer Build',
                        author: 'Karl_21347',
                        classId: 'D',
                        salutes: 47,
                        primary: 'P2_Cryo',
                        secondary: 'S1_Subata',
                        lastUpdate: '2020-02-15'
                    },
                    {
                        loadoutId: '222222',
                        iconPath: '50px-Driller_icon.png',
                        name: 'Karl\'s Flamer Build',
                        author: 'Karl_21347',
                        classId: 'D',
                        salutes: 43,
                        primary: 'P1_CRSPR',
                        secondary: 'S2_Plasma',
                        lastUpdate: '2020-02-14'
                    },
                    {
                        loadoutId: '333333',
                        iconPath: '50px-Gunner_icon.png',
                        name: 'pew pew pew',
                        author: 'redguy',
                        classId: 'G',
                        salutes: 22,
                        primary: 'P1_Lead',
                        secondary: 'S1_Bulldog',
                        lastUpdate: '2020-06-01'
                    },
                    {
                        loadoutId: '444444',
                        iconPath: '50px-Engineer_icon.png',
                        name: 'cheese party',
                        author: 'turret-master_666',
                        classId: 'E',
                        salutes: 21,
                        primary: 'P1_Warthog',
                        secondary: 'S1_PGL',
                        lastUpdate: '2020-04-02'
                    }
                ]
            };
        },
        computed: {},
        methods: {
            onRowClick: function (params) {
                console.log('nav to preview', params.row.classId, params.row.loadoutId);
                window.location.href = `${window.location.origin}/preview/${params.row.loadoutId}`;
            },
            getClassIcon: function (rowObj) {
                let classId = rowObj.classId;
                return `<img src="../assets/img/50px-${classId}_icon-hex.png" class="classIcon"/>`;
            },
            getPrimaryIcon: function (rowObj) {
                let weaponSVGPath = store.state.icons.equipment[`${rowObj.classId}_${rowObj.primary}`];
                return `<svg xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 180 90"
                     height="90%"
                     preserveAspectRatio="xMidYMid meet"
                     >${weaponSVGPath}</svg>`;
            },
            getSecondaryIcon: function (rowObj) {
                let weaponSVGPath = store.state.icons.equipment[`${rowObj.classId}_${rowObj.secondary}`];
                return `<svg xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 180 90"
                     height="90%"
                     preserveAspectRatio="xMidYMid meet"
                     >${weaponSVGPath}</svg>`;
            }
        },
        apollo: {},
        mounted: function () {
            console.log('mounted browse table');
        }
    };
</script>

<style>
    /* todo: move non scoped styles into global css*/

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
