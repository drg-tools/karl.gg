<template>
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
                <h2 class="salutes">{{salutes}}</h2>
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
</template>

<script>
    import store from '../store';

    export default {
        name: 'LoadoutCard',
        props: {
            loadoutId: String,
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
                return store.state.icons.equipment[`${this.classId}_${this.primary}`];
            },
            getSecondaryIcon: function () {
                console.log(store.state.icons.equipment);
                console.log(`${this.classId}_${this.secondary}`);
                return store.state.icons.equipment[`${this.classId}_${this.secondary}`];
            }
        },
        methods: {
            onLoadoutClick: function () {
                console.log('nav to preview', this.classId, this.loadoutId);
                window.location.href = `${window.location.origin}/preview/${this.loadoutId}`;
            }
        },
        apollo: {},
        mounted: function () {
            console.log('mounted loadout card');
        }
    };
</script>
<!-- todo: screw the border or make the whole card svg-->
<style scoped>
    .loadoutCardContainer {
        display: flex;
        flex-wrap: nowrap;
        width: 100%;
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

    /* todo: remove all font-size, font-weight, font-family and color from h1-h4 in components! */
    h2 {
        /*font-size: 1.6rem;*/
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


</style>
