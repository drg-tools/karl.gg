<template>
    <div>
        <div class="previewHeaderBackground text-gray-300">
            <div class="flex justify-between items-center">
                <h2 class="p-4">
                    by
                    <a
                        class="text-karl-orange"
                        :href="'/profile/' + creatorId"
                        >{{ creatorName }}</a
                    >
                    on
                    {{ updatedAt }}
                </h2>
                <div class="flex">
                    <div class="button" v-on:click="onEditClick">
                        <span class="buttonText" v-if="userOwnsLoadout"
                            >EDIT</span
                        >
                        <span class="buttonText" v-else>COPY</span>
                    </div>
                    <input type="hidden" v-model="this.pageUrl" />
                    <div
                        class="button"
                        v-clipboard:copy="pageUrl"
                        v-clipboard:success="onCopy"
                        v-clipboard:error="onError"
                    >
                        <h1 class="buttonText">SHARE</h1>
                    </div>
                </div>
                <!-- todo: use texts from old karl and style toast messages -->
            </div>
            <div
                class="previewHeaderContainer p-4"
                :class="getHeaderImageClass"
            >
                <div class="previewFooter mt-4">
                    <!-- todo: tooltip on salutes container! -->
                    <div
                        v-on:click="onToggleVote"
                        class="
                            bg-gray-900
                            font-bold
                            sm:rounded
                            text-center
                            p-2
                            cursor-pointer
                            hover:bg-gray-800
                        "
                    >
                        <span>Salutes</span>
                        <img
                            src="/assets/img/bosco.png"
                            :class="getUserVotedState"
                            class="bosco-salute"
                        />
                        <span class="salute-count">{{
                            loadoutDetails.votes
                        }}</span>
                    </div>
                </div>
            </div>

            <!-- todo: style modals nicely -->
            <modal
                name="upvoteMessageModal"
                class="loadoutModal"
                :adaptive="true"
                :height="250"
            >
                <div class="contentContainer">
                    <h1 class="modalTitle">{{ messageTitle }}</h1>
                    <h2>{{ messageText }}</h2>
                </div>
                <div class="buttonContainer">
                    <div class="button" v-on:click="onCloseMessageModal">
                        <h1 class="buttonText">CLOSE</h1>
                    </div>
                </div>
            </modal>
        </div>
    </div>
</template>

<script>
import gql from "graphql-tag";
import { get } from "lodash";

export default {
    name: "PreviewHeader",
    props: ["pageUrl", "loadoutId", "creatorId", "creatorName","updatedAt"],
    data: function () {
        return {
            messageTitle: "",
            messageText: "",
            userVotedStatus: false,
        };
    },
    computed: {
        getUserVotedState() {
            // show bosco in disabled state if user has not yet voted or is not able to vote
            this.getUserVotedStatus();
            return this.userVotedStatus ? "" : "disabled";
        },
        userOwnsLoadout() {
            return this.creatorId === this.$userId;
        },
    },
    methods: {
        onToggleVote() {
            // toggle vote
            this.setVotes(this.loadoutId)
                .then((numberOfVotes) => {
                    store.commit("setLoadoutVotedState", {
                        userVoted: !userVoted,
                        newNumberOfVotes: numberOfVotes,
                    });
                })
                .catch((err) => {
                    console.warn("error voting", err);
                    // user cannot vote!
                    this.messageTitle = "Cannot vote :(";
                    this.messageText =
                        "Sorry, you need to be signed in to vote on Loadouts.";
                    this.$modal.show("upvoteMessageModal");
                });
        },
        onCloseMessageModal() {
            this.$modal.hide("upvoteMessageModal");
        },
        onEditClick() {
            window.location.href = `${window.location.origin}/build/${this.loadoutDetails.loadoutId}`;
        },
        onCopy: function (e) {
            this.$toasted.info("You just copied: " + this.pageUrl, {
                position: "top-center",
                duration: 5000,
            });
        },
        onError: function (e) {
            this.$toasted.error("Failed to copy URL", {
                position: "top-center",
                duration: 5000,
            });
        },
        async setVotes(loadoutId) {
            try {
                const result = await this.$apollo.mutate({
                    mutation: gql`
                        mutation upVoteLoadout($id: Int!) {
                            upVoteLoadout(id: $id) {
                                votes
                            }
                        }
                    `,
                    variables: {
                        id: parseInt(loadoutId),
                    },
                });
                return result.data.upVoteLoadout.votes;
            } catch (err) {
                console.warn("error on voting!", err);
                throw err;
            }
        },
        async getUserVotedStatus() {
            /* todo: run these two queries in parallel */
            let userVotedStatus = false;
            try {
                let getVoteStatus = await this.$apollo.query({
                    query: gql`
                        query getVoteStatus($id: Int!) {
                            getVoteStatus(id: $id)
                        }
                    `,
                    variables: {
                        id: parseInt(this.loadoutId),
                    },
                });
                // getVoteStatus
                if (getVoteStatus.data.getVoteStatus === 1) {
                    userVotedStatus = true;
                }
            } catch (e) {
                console.warn("user not signed in, no voted status", e);
            }
        },
    },
    mounted: function () {},
};
</script>

<style scoped>
.previewHeaderBackground {
    width: 100%;
    /*background: linear-gradient(0deg, rgba(34, 193, 195, 0) 0%, #352e1e 100%);*/
}

.previewHeaderContainer {
    /* height: 20rem; */
    /*background-color: #352e1e;*/
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
    background-image: url("/assets/img/full-D_image.png");
    background-repeat: no-repeat;
    background-size: 45%;
    background-position: right -10% top -10%;
}

.imageE {
    background-image: url("/assets/img/full-E_image.png");
    background-repeat: no-repeat;
    background-size: 35%;
    background-position: right -5% top -10%;
}

.imageG {
    background-image: url("/assets/img/full-G_image.png");
    background-repeat: no-repeat;
    background-size: 45%;
    background-position: right -10% top -10%;
}

.imageS {
    background-image: url("/assets/img/full-S_image.png");
    background-repeat: no-repeat;
    background-size: 35%;
    background-position: right -5% top -10%;
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

.loadoutDescription {
    white-space: pre-line; /* for those that don't know break-spaces */
}

.loadoutDescription {
    white-space: break-spaces;
}

@media (max-width: 770px) {
    .equipmentCards {
        flex-wrap: wrap;
    }

    .imageD {
        background-position: bottom -50px right -40px;
        background-size: 300px;
    }

    .imageE {
        background-position: bottom -150px right -40px;
        background-size: 250px;
    }

    .imageG {
        background-position: bottom -50px right -40px;
        background-size: 300px;
    }

    .imageS {
        background-position: bottom -150px right -40px;
        background-size: 250px;
    }

    .buttonContainer {
        flex-wrap: wrap;
    }
}
</style>
