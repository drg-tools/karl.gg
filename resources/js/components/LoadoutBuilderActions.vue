<template>
    <div class="flex justify-evenly my-5">
        <div class="flex flex-row">
            
                <div
                    class="
                        inline-flex
                        items-center
                        text-center
                        px-4
                        py-2
                        border border-transparent
                        text-sm
                        font-medium
                        rounded-md
                        shadow-sm
                        text-white
                        bg-orange-500
                        hover:bg-orange-700
                        focus:outline-none
                        focus:ring-2
                        focus:ring-offset-2
                        focus:ring-orange-500
                        w-full
                        md:w-auto
                    "
                    v-on:click="onSaveClick"
                >
                    SAVE
            </div>
            
        </div>
    </div>
</template>

<script>
export default {
    name: "LoadoutBuilderActions",
    methods: {
        onSaveClick() {
            // User clicked SAVE
            // Do a quick check if you're logged in or a guest
            // Set update to true if you're logged in & author
            // Fire the onAcceptSave at the proper time.
            this.getLoggedInUser().then(response => {
                let loggedInUserId = response;
                if (this.creatorId === loggedInUserId) {
                    // We know you are the creator, so you are allowed to update this existing build.
                    this.update = true;
                }
                this.$modal.show('loadingModal');
                this.onAcceptSave();
            }).catch(err => {
                console.warn('no logged in user', err);
                this.messageTitle = 'Not logged in :(';
                this.messageText = 'You can save your loadout anonymously or log in first. PLEASE NOTE: You will not be able to edit your build later. Only registered users can edit their builds.';
                this.guest = true;
                this.$modal.show('messageModal');
                /* todo: keep loadout in local storage so his stuff is not lost when he goes to create an account.. */
            });

        },
    }
};
</script>
