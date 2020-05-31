<template>
    <div class="classSelectContainer">
        <ClassComponent :classId="'D'" :name="'Driller'"/>
        <ClassComponent :classId="'E'" :name="'Engineer'"/>
        <ClassComponent :classId="'G'" :name="'Gunner'"/>
        <ClassComponent :classId="'S'" :name="'Scout'"/>
        <ClassComponent :classId="'R'" :name="'Robots'"/>
        <!--<div class="linkArea" v-on:click="getLink()">
            <label for="idCopyTextArea" class="linkText">Generate</label>
            <input id="idCopyTextArea" class="copyTextArea" disabled/>
        </div>-->
        <!-- todo: put the generate link somewhere else in the ui -->
    </div>
</template>

<script>
    import ClassComponent from './ClassComponent.vue';

    export default {
        name: 'ClassSelect',
        components: {
            ClassComponent
        },
        computed: {},
        methods: {},
        mounted: function () {
            /* todo: examples to test api */
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
        }
    };
</script>

<style>
    .classSelectContainer {
        display: flex;
        flex-wrap: wrap;
        border-top: 5px solid #fc9e00;
        background-color: #352e1e;
        margin-bottom: 0.5rem;
    }
</style>
