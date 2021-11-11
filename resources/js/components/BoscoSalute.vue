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
                        <span class="salute-count">{{
                            loadoutDetails.votes
                        }}</span>
                    </div>
                </div>
    </div>
</template>

<script>
import gql from "graphql-tag";
import { get } from "lodash";

export default {
  name: "PreviewHeader",
  props: ["pageUrl", "loadoutId", "creatorId", "creatorName", "updatedAt"],
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


