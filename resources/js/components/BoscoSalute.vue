<template>
    <div>
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
            <span class="salute-count">{{ mutableNumberOfVotes }}</span>
        </div>
    </div>
</template>

<script>
import gql from "graphql-tag";
import { get } from "lodash";

export default {
    name: "BoscoSalute",
    props: ["loadoutId", "numberOfVotes"],
    data: function () {
        return {
            userVotedStatus: false,
            mutableNumberOfVotes: this.numberOfVotes,
        };
    },
    computed: {
        getUserVotedState() {
            // show bosco in disabled state if user has not yet voted or is not able to vote
            this.getUserVotedStatus();
            return this.userVotedStatus ? "" : "disabled";
        },
    },
    methods: {
        onToggleVote() {
            // toggle vote
            this.setVotes()
                .then((numberOfVotes) => {
                    this.userVotedStatus = !this.userVotedStatus;
                    this.mutableNumberOfVotes = numberOfVotes;
                })
                .catch((err) => {
                    console.warn("error voting", err);
                    // user cannot vote!
                });
        },
        async setVotes() {
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
                        id: parseInt(this.loadoutId),
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
                    this.userVotedStatus = true;
                }
            } catch (e) {
                console.warn("user not signed in, no voted status", e);
            }
        },
    },
    watch: {
        userVotedStatus: function (newValue, oldValue) {
            this.userVotedStatus = newValue;
        },
    },
};
</script>


