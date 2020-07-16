<template>
    <div v-if="!dataReady" class="loadingIndicator">
        <img src="../assets/img/karl-spinner-free.gif" alt="loading...">
    </div>
    <div v-else-if="dataReady" class="previewHeaderBackground">
        <div class="previewHeaderContainer" :class="getHeaderImageClass">
            <h1>{{loadoutDetails.name}}</h1>
            <!-- todo: style this! -->
            <h2>by {{loadoutDetails.author}} on {{loadoutDetails.created_at}}</h2>
            <h2>{{loadoutDetails.description}}</h2>
            <div class="previewFooter">
                <!-- todo: tooltip on salutes container! -->
                <div v-on:click="onToggleVote" class="salutes-container">
                    <h3>Salutes</h3>
                    <img src="../assets/img/bosco.png" :class="getUserVotedState" class="bosco-salute"/>
                    <span class="salute-count">{{ loadoutDetails.votes }}</span>
                </div>
                <div class="buttonContainer">
                    <div class="button" v-on:click="onEditClick">
                        <h1 class="buttonText">EDIT</h1>
                    </div>
                    <input type="hidden" v-model="this.pageUrl" />
                    <div class="button"
                        v-clipboard:copy="pageUrl"
                        v-clipboard:success="onCopy"
                        v-clipboard:error="onError">
                        <h1 class="buttonText">SHARE</h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- todo: style modals nicely -->
        <modal name="upvoteMessageModal" class="loadoutModal">
            <h1 class="modalTitle">{{messageTitle}}</h1>
            <h2>{{messageText}}</h2>
            <div class="buttonContainer">
                <div class="button" v-on:click="onCloseMessageModal">
                    <h1 class="buttonText">CLOSE</h1>
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
    import store from '../store';
    import gql from 'graphql-tag';

    export default {
        name: 'PreviewHeader',
        props: ['pageUrl'],
        data: function () {
            return {
                messageTitle: '',
                messageText: '',
            };
        },
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
            getUserVotedState() {
                // show bosco in disabled state if user has not yet voted or is not able to vote
                return this.loadoutDetails.userVoted ? '' : 'disabled';
            }
        },
        methods: {
            onToggleVote() {
                // toggle vote
                let userVoted = this.loadoutDetails.userVoted;
                this.setVotes(this.loadoutDetails.loadoutId).then(numberOfVotes => {
                    console.log('new votes result', numberOfVotes);
                    store.commit('setLoadoutVotedState', {userVoted: !userVoted, newNumberOfVotes: numberOfVotes});
                }).catch(err => {
                    console.log('error voting', err);
                    // user cannot vote!
                    this.messageTitle = 'Cannot vote :(';
                    this.messageText = 'Sorry, you need to be signed in to vote on Loadouts.';
                    this.$modal.show('upvoteMessageModal');
                });
            },
            onCloseMessageModal() {
                this.$modal.hide('upvoteMessageModal');
            },
            onEditClick() {
                console.log('nav to build view', {
                    classID: this.loadoutDetails.classId,
                    loadoutId: this.loadoutDetails.loadoutId
                });
                window.location.href = `${window.location.origin}/build/${this.loadoutDetails.loadoutId}`;
            },
            onShareClick() {
                console.log('generate share link without saving');
            },
            onCopy: function (e) {
                this.$toasted.info('You just copied: ' + this.pageUrl , { 
                    position: "top-center", 
                    duration : 5000
                });
            },
            onError: function (e) {
                this.$toasted.error('Failed to copy URL' , { 
                    position: "top-center", 
                    duration : 5000
                });
            },
            async setVotes(loadoutId) {
                try {
                    const result = await this.$apollo.mutate({
                        mutation: gql`mutation upVoteLoadout($id: Int!)
                            {
                                upVoteLoadout(id: $id) {
                                    votes
                                }
                            }
                            `,
                        variables: {
                            id: loadoutId
                        }
                    });
                    return result.data.upVoteLoadout.votes;
                } catch (err) {
                    console.log('error on voting!');
                    throw(err);
                }
            }
        },
        mounted: function () {
            console.log('loadout preview header mounted'); 
            console.log(this.pageUrl);
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
        /* height: 20rem; */
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

    .salutes-container {
        cursor: pointer;
        padding: 5px;
        border-radius: 3px;
        background: rgba(24, 17, 11, .6);
        color: #FFF;
        font-weight: bold;
        text-align: center;
        font-size: 26px;
    }
    .salutes-container:hover > h3 {
        text-decoration: underline;
    }

    .disabled {
        filter: gray; /* IE6-9 */
        -webkit-filter: grayscale(1); /* Google Chrome, Safari 6+ & Opera 15+ */
        filter: grayscale(1); /* Microsoft Edge and Firefox 35+ */
    }

    .bosco-salute {
        display: block;
        margin: auto;
    }
</style>
