<template>
    <div class="classSelectContainer">
        <h1>Select class:</h1>
        <div v-on:click="selectClass('D')" class="classSelect"
             :class="[selectedClass === 'D' ? 'classSelectActive' : '']">
            <img src="../assets/img/50px-D_icon-hex.png" class="classIcon"/>
            <h2>Driller</h2>
        </div>
        <div v-on:click="selectClass('E')" class="classSelect"
             :class="[selectedClass === 'E' ? 'classSelectActive' : '']">
            <img src="../assets/img/50px-E_icon-hex.png" class="classIcon"/>
            <h2>Engineer</h2>
        </div>
        <div v-on:click="selectClass('G')" class="classSelect"
             :class="[selectedClass === 'G' ? 'classSelectActive' : '']">
            <img src="../assets/img/50px-G_icon-hex.png" class="classIcon"/>
            <h2>Gunner</h2>
        </div>
        <div v-on:click="selectClass('S')" class="classSelect"
             :class="[selectedClass === 'S' ? 'classSelectActive' : '']">
            <img src="../assets/img/50px-S_icon-hex.png" class="classIcon"/>
            <h2>Scout</h2>
        </div>
    </div>
</template>

<script>
    import store from '../store';

    export default {
        name: 'ClassSelect',
        computed: {
            selectedClass: function () {
                return store.state.loadout.selectedClassId;
            }
        },
        methods: {
            selectClass: function (classId) {
                console.log('select', classId);
                store.commit('selectLoadoutClass', {classId: classId});
            }
        },
        mounted: function () {
            console.log('class select mounted');
            console.log(store.state);
        }
    };
</script>

<style scoped>
    .classSelectContainer {
        border-top: 5px solid #fc9e00;
        display: flex;

    }

    .classSelectContainer > h1 {
        margin: 0;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        padding-right: 2rem
    }

    .classSelect {
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .classSelect:hover {
        background: #fc9e00;
    }

    .classSelect > h2 {
        color: #ADA195;
        padding-left: 1rem;
        padding-right: 2rem;
        margin-block-start: 0;
        margin-block-end: 0;
        margin-inline-start: 0;
        margin-inline-end: 0;
    }

    .classSelect > img {
        opacity: 0.4;
    }

    .classSelectActive > h2 {
        color: #ffffff;
    }

    .classSelectActive > img {
        opacity: 1;
    }
</style>
