<template>
    <div class="loadoutCardContainer">
        <div class="titleRow">
            <img :src="getIconFromPath" class="classIcon"/>
            <h2 class="buildName">{{name}}</h2>
        </div>
        <div class="subtitleRow">
            <h2 class="salutes">{{salutes}}</h2>
            <h2 class="author">{{author}}</h2>
        </div>
        <div class="content">
            <div class="weaponContainer">
                <svg xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 180 90"
                     height="70%"
                     preserveAspectRatio="xMidYMid meet"
                     v-html="getPrimaryIcon"></svg>
            </div>
            <div class="weaponContainer">
                <svg xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 180 90"
                     height="70%"
                     preserveAspectRatio="xMidYMid meet"
                     v-html="getSecondaryIcon"></svg>
            </div>
        </div>
    </div>
</template>

<script>
    import store from '../store';

    export default {
        name: 'LoadoutCard',
        props: {
            iconPath: String,
            name: String,
            author: String,
            classId: String,
            salutes: Number,
            primary: String,
            secondary: String
        },
        components: {},
        computed: {
            getIconFromPath: function () {
                console.log(this);
                console.log(this.iconPath);
                return `../assets/img/${this.iconPath}`;
            },
            getPrimaryIcon: function () {
                return store.state.icons.equipment[`${this.classId}_${this.primary}`]
            },
            getSecondaryIcon: function () {
                console.log(store.state.icons.equipment)
                console.log(`${this.classId}_${this.secondary}`)
                return store.state.icons.equipment[`${this.classId}_${this.secondary}`]
            }
        },
        methods: {},
        apollo: {},
        mounted: function () {
            console.log('mounted loadout card');
        }
    };
</script>

<style>
    .loadoutCardContainer {
        display: flex;
        flex-wrap: nowrap;
        width: 30%;
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

    h2 {
        font-size: 1.6rem;
        font-weight: normal;
        font-family: BebasNeue, sans-serif;
    }

    .titleRow {
        display: flex;
        height: 2.5rem;
        align-items: center;
        background: rgb(26, 85, 176);
        background: linear-gradient(90deg, rgba(26, 85, 176, 1) 10%, rgba(0, 0, 0, 0) 100%);
    }

    .subtitleRow {
        display: flex;
        height: 2.5rem;
        align-items: center;
        background: rgba(0,0,0, 0.6);
        background: linear-gradient(90deg, rgba(0,0,0,0.6) 10%, rgba(0,0,0,0) 100%);
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

    .content {
        display: flex;
    }

    .weaponContainer {
        width: 50%;

        display: flex;
        flex-direction: column;
        align-items: center;
        height: 5rem;
    }
    .weaponContainer > svg {
        margin: 0.5rem;
        fill: #ADA195;
    }


</style>
