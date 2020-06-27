<template>
    <div class="classSelectContainer">
        <!-- todo: #move# this to loadout overview view -->
        <div>
            <div>{{characters}}</div>
            <EquipmentCard :classId="'D'" :equipmentId="'P1'" :build="'ACBCA1'"></EquipmentCard>
        </div>
        <!-- todo: #move# -->
        <ClassComponent :classId="'D'" :name="'Driller'"/>
        <ClassComponent :classId="'E'" :name="'Engineer'"/>
        <ClassComponent :classId="'G'" :name="'Gunner'"/>
        <ClassComponent :classId="'S'" :name="'Scout'"/>
        <ClassComponent :classId="'R'" :name="'Robots'"/>
    </div>
</template>

<script>
    import ClassComponent from './ClassComponent.vue';
    import EquipmentCard from './EquipmentCard.vue';
    import gql from 'graphql-tag';

    export default {
        name: 'ClassSelect',
        components: {
            ClassComponent,
            EquipmentCard
        },
        computed: {},
        methods: {
            async getCharacters() {
                console.time('getCharacters');
                const response = await this.$apollo.query({
                    query: gql`query {
                      characters {
                            data {
                                id
                                name
                            }
                        }
                    }`
                });
                console.log(response.data.characters);
                console.timeEnd('getCharacters');
                return response.data.characters;
            },
            async getWeapons() {
                console.time('getWeapons');
                const response = await this.$apollo.query({
                    query: gql`query {
                      characters {
                            data {
                                id
                                name
                            }
                        }
                    }`
                });
                console.log(response.data.characters);
                console.timeEnd('getWeapons');
                return response.data.characters;
            }
        },
        /* todo: examples to test api */
        apollo: {
            characters: gql`query {
              characters {
                    data {
                        id
                        name
                    }
                }
            }`
        },
        mounted: function () {
            /* todo: examples to test api */
            /*fetch('/api/characters')
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
            .then(data => console.log('guns/2/mods', data));*/

            console.log(this.$apollo.queries.characters);

            this.getCharacters().then(data => {
                console.log(data.data)
            });

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
