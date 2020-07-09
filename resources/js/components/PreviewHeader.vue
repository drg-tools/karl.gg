<template>
    <div class="previewHeaderBackground">
        <div class="previewHeaderContainer imageD">
            <h1>preview header here plz</h1>
            <div class="previewFooter">
                <div>salutes</div>
                <div class="buttonContainer">
                    <div class="button" v-on:click="onEditClick">
                        <h1 class="buttonText">EDIT</h1>
                    </div>
                    <div class="button" v-on:click="onShareClick">
                        <h1 class="buttonText">SHARE</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'PreviewHeader',
        methods: {
            onEditClick() {
                console.log("nav to build view")
            },
            onShareClick() {
                console.log("copy/show share link for this loadout")
            }
        }
    };
</script>

<style scoped>
    .previewHeaderBackground {
        width: 100%;
        height: 100%;
        background: linear-gradient(0deg, rgba(34, 193, 195, 0) 0%, #352e1e 100%)
    }

    .previewHeaderContainer {
        height: 20rem;
        background-color: #352e1e;
        margin-bottom: 2rem;
        background-blend-mode: overlay;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .previewFooter {
        display: flex;
        justify-content: space-between;
    }

    .imageD {
        background-image: radial-gradient(circle, rgba(255,255,255,0.6) -25%, rgba(255,255,255,0) 75%), url("../assets/img/full-D_image.png");
        background-repeat: no-repeat;
        background-size: 45%;
        background-position: right -10% top -10%;
    }
    .imageE {
        background-image: radial-gradient(circle, rgba(255,255,255,0.6) -25%, rgba(255,255,255,0) 75%), url("../assets/img/full-E_image.png");
        background-repeat: no-repeat;
        background-size: 35%;
        background-position: right -5% top -10%;
    }
    .imageG {
        background-image: radial-gradient(circle, rgba(255,255,255,0.6) -25%, rgba(255,255,255,0) 75%), url("../assets/img/full-G_image.png");
        background-repeat: no-repeat;
        background-size: 45%;
        background-position: right -10% top -10%;
    }
    .imageS {
        background-image: radial-gradient(circle, rgba(255,255,255,0.6) -25%, rgba(255,255,255,0) 75%), url("../assets/img/full-S_image.png");
        background-repeat: no-repeat;
        background-size: 35%;
        background-position: right -5% top -10%;
    }
</style>
