<template>
    <div class="classSelectContainer">
        <h1>Select class:</h1>
        {{character.name}}<!-- todo -->
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
    import apolloQueries from '../apolloQueries';
    import gql from 'graphql-tag';

    const charToId = {
        D: 3,
        E: 1,
        G: 4,
        S: 2
    };

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
                this.getCharacterData(classId)
                store.commit('selectLoadoutClass', {classId: classId});
            },
            /* todo: load like this and put into store */
            async getCharacterData(classId) {
                let id = charToId[classId];
                console.time('getCharacter');
                const response = await this.$apollo.query({
                    query: gql`${apolloQueries.characterById(id)}`
                });
                console.timeEnd('getCharacter');
                /* todo: store function, transform data from backend into a form that is more or less like the old one */
                /* todo: find a way to access the old calc functions until backend is ready */
                store.commit('somethingsomething', {classId: classId});
                return response.data.character;
            }
        },
        /* todo: or like this and use again in other components? apollo will cache it all but unsure if we can then use selected and stuff on that data */
        /* but accessing this data directly in template before it was loaded sucks */
        /* just keep it as an example for later, where it makes more sense */
        apollo: {
            // Query with parameters
            character: {
                query: gql`${apolloQueries.character}`,
                // Reactive parameters
                variables() {
                    console.log('auto apollo query', charToId[this.selectedClass]);
                    // Use vue reactive properties here
                    return {
                        id: charToId[this.selectedClass]
                    };
                }
            }
        },
        mounted: function () {
            console.log('class select mounted');
            console.log(this.selectedClass);
            console.log(store.state);
            // this.getCharacterData(this.selectedClass).then(character => console.log(character));
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
