<template>
    <div class="previewHeaderBackground" v-if="dataReady">
        <div class="previewHeaderContainer" :class="getHeaderImageClass">
            <h1>{{loadoutDetails.name}}</h1>
            <!-- todo: style this! -->
            <h2>by {{loadoutDetails.author}} on {{loadoutDetails.created_at}}</h2>
            <h2>{{loadoutDetails.description}}</h2>
            <div class="previewFooter">
                <div>
                    <li class="list-group-item">
                        <i class="glyphicon glyphicon-chevron-up" @click="upvote" :class="{disabled: upvoted}"></i>
                        <span class="label label-primary">{{ votes }}</span>
                        <i class="glyphicon glyphicon-chevron-down" @click="downvote" :class="{disabled: downvoted}"></i>
                    </li>
                </div>
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
    import store from '../store';

    export default {
        name: 'PreviewHeader',
        computed: {
            dataReady() {
                return store.state.loadoutDetailDataReady;
            },
            loadoutDetails() {
                // loadout details are getting loaded by LoadoutPreview component
                return store.state.loadoutDetails;
            },
            getHeaderImageClass() {
                return `image${this.loadoutDetails.classId}`;
            },
            votes: function() {
                if (this.upvoted) {
                    return this.loadoutDetails.votes + 1;
                } else if (this.downvoted) {
                    return this.loadoutDetails.votes - 1;
                } else {
                    return this.loadoutDetails.votes;
                }
            }
        },
        methods: {
            onEditClick() {
                console.log('nav to build view');
            },
            onShareClick() {
                console.log('copy/show share link for this loadout');
            },
            upvote: function() {
                this.upvoted = !this.upvoted;
                this.downvoted = false;
            },
            downvote: function() {
                this.downvoted = !this.downvoted;
                this.upvoted = false;
            }
        },
        mounted: function () {
            console.log('loadout preview header mounted');
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
        background-image: radial-gradient(circle, rgba(255, 255, 255, 0.6) -25%, rgba(255, 255, 255, 0) 75%), url("../assets/img/full-D_image.png");
        background-repeat: no-repeat;
        background-size: 45%;
        background-position: right -10% top -10%;
    }

    .imageE {
        background-image: radial-gradient(circle, rgba(255, 255, 255, 0.6) -25%, rgba(255, 255, 255, 0) 75%), url("../assets/img/full-E_image.png");
        background-repeat: no-repeat;
        background-size: 35%;
        background-position: right -5% top -10%;
    }

    .imageG {
        background-image: radial-gradient(circle, rgba(255, 255, 255, 0.6) -25%, rgba(255, 255, 255, 0) 75%), url("../assets/img/full-G_image.png");
        background-repeat: no-repeat;
        background-size: 45%;
        background-position: right -10% top -10%;
    }

    .imageS {
        background-image: radial-gradient(circle, rgba(255, 255, 255, 0.6) -25%, rgba(255, 255, 255, 0) 75%), url("../assets/img/full-S_image.png");
        background-repeat: no-repeat;
        background-size: 35%;
        background-position: right -5% top -10%;
    }
</style>
