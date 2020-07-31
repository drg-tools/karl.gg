<template>
<div class="loadoutCards wide">
    <div class="loadoutCardContainer" v-on:click="onLoadoutClick">
        <div class="titleRow">
            <div class="titleContentLeft">
                <img :src="getIconFromPath" class="classIcon"/>
                <h2 class="buildName">{{name}}</h2>
            </div>
            <div class="titleContentRight">
                <div class="weaponContainer">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 180 90"
                         height="70%"
                         preserveAspectRatio="xMidYMid meet"
                         v-html="getPrimaryIcon"></svg>
                </div>
            </div>
        </div>
        <div class="subtitleRow">
            <div class="titleContentLeft">
                <h2 class="salutes">{{votes}}</h2>
                <h2 class="author">{{author}}</h2>
            </div>
            <div class="titleContentRight">
                <div class="weaponContainer">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 180 90"
                         height="70%"
                         preserveAspectRatio="xMidYMid meet"
                         v-html="getSecondaryIcon"></svg>
                </div>
                
            </div>
        </div>
    </div>
    <div class="buttonColumn">

             <div class="buttonContainer" v-if="editEnabled">
                <div class="button" v-on:click="onEditLoadout">
                    <h1 class="buttonText">EDIT</h1>
                </div>
            </div>
            
             <div class="buttonContainer" v-if="deleteEnabled">
                <div class="button" v-on:click="onDeleteLoadout">
                    <h1 class="buttonText">DELETE</h1>
                </div>
            </div>
    </div>
    </div>
</template>

<script>
    import store from '../store';

    export default {
        name: 'LoadoutCard',
        props: {
            loadoutId: String,
            editEnabled: Boolean,
            deleteEnabled: Boolean,
            name: String,
            author: String,
            classId: String,
            votes: Number,
            primary: String,
            secondary: String
        },
        components: {},
        computed: {
            getIconFromPath: function () {
                return `../assets/img/50px-${this.classId}_icon.png`;
            },
            getPrimaryIcon: function () {
                return store.state.icons.equipment[this.primary];
            },
            getSecondaryIcon: function () {
                return store.state.icons.equipment[this.secondary];
            }
        },
        methods: {
            onLoadoutClick: function (event) {
                if (event.target.className !== "button" && event.target.className !== "buttonText") {
                    window.location.href = `${window.location.origin}/preview/${this.loadoutId}`;
                }
            },
            onEditLoadout: function () {
                window.location.href = `${window.location.origin}/build/${this.loadoutId}`;
            },
            onDeleteLoadout: function () {
                console.log("delete");
                /* todo: delete loadout */
            }
        },
        apollo: {},
        mounted: function () {
            console.log('mounted loadout card');
            console.log(this.loadoutId);
            console.log(this.name);
            console.log(this.author);
            console.log(this.classId);
            console.log(this.primary);
            console.log(this.secondary);
        }
    };
</script>
<!-- todo: screw the border or make the whole card svg-->
<style scoped>
    .loadoutCardContainer {
        display: flex;
        flex-wrap: nowrap;
        width: 28.3rem;
        height: 5.3rem;
        flex-direction: column;
        border-top: 5px solid #fc9e00;
        background-color: #5C4F35;
        margin-bottom: 0.5rem;
        cursor: pointer;
    }

    .loadoutCardContainer:hover {
        background-color: #fc9e00;
    }

    .loadoutCardContainer:hover svg {
        fill: #ffffff;
    }


    .titleRow {
        display: flex;
        height: 2.5rem;
        align-items: center;
        justify-content: space-between;
        background: rgb(26, 85, 176);
        background: linear-gradient(90deg, rgba(26, 85, 176, 1) 10%, rgba(0, 0, 0, 0) 100%);
    }

    .subtitleRow {
        display: flex;
        height: 2.5rem;
        align-items: center;
        justify-content: space-between;
        background: rgba(0, 0, 0, 0.6);
        background: linear-gradient(90deg, rgba(0, 0, 0, 0.6) 10%, rgba(0, 0, 0, 0) 100%);
    }

    .titleContentLeft {
        display: flex;
        align-items: center;
    }

    .titleContentRight {
        display: flex;
        align-items: center;
    }

    .classIcon {
        width: 2.5rem;
        height: 2.5rem;
    }

    .buildName {
        margin: 0 0 0 0.5rem;
    }

    .salutes {
        width: 2.5rem;
        text-align: center;
        color: #FC9E00;
    }

    .author {
        margin: 0 0 0 0.5rem;
    }

    .titleContentLeft {
        display: flex;
    }

    .weaponContainer {
        /*width: 50%;*/
        display: flex;
        flex-direction: column;
        align-items: center;
        height: 2.5rem;
    }

    .weaponContainer > svg {
        margin: 0.5rem;
        fill: #ADA195;
    }

    .loadoutCards.wide {
        width: 60%;
        margin: auto;
    }
    .buttonColumn {
        width: 20%;
        display: flex;
        flex-direction: column;
    }
    .button {
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 0 0.5rem 1rem;
            min-width: 8rem;
            height: 2.2rem;
            background: linear-gradient(90deg, #fc9e00 4%, rgba(0, 0, 0, 0) 4%, rgba(0, 0, 0, 0) 8%, #fc9e00 8%, #fc9e00 92%, rgba(0, 0, 0, 0) 92%, rgba(0, 0, 0, 0) 96%, #fc9e00 96%);
        }

</style>