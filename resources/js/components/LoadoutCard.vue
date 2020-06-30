<template>
    <div class="loadoutCardContainer">
        <div class="titleRow">
            <img :src="getIconFromPath" class="classIcon" />
            <h1 class="buildName">{{name}}</h1>
        </div>
        <div class="subtitleRow">
            <h1>{{salutes}}</h1>
            <h1>{{author}}</h1>
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
            primaryId: String,
            secondaryId: String
        },
        components: {},
        computed: {
            getIconFromPath: function () {
                console.log(this)
                console.log(this.iconPath)
                return `../assets/img/${this.iconPath}`
            },
        },
        methods: {},
        apollo: {},
        mounted: function () {
            console.log("mounted loadout card")
        }
    };
</script>

<style>
    .loadoutCardContainer {
        display: flex;
        flex-wrap: nowrap;
        flex-direction: column;
        border-top: 5px solid #fc9e00;
        background-color: #352e1e;
        margin-bottom: 0.5rem;
    }

    .titleRow {
        display: flex;
    }
    .subtitleRow {
        display: flex;
    }

    .classIcon {
        width: 3rem;
        height: 3rem;
    }

    .buildName {
        background-color: aliceblue;
        margin: 0;
    }

</style>
