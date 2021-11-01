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
           

            let loggedInStatus = this.getIsLoggedIn();


            if(loggedInStatus) {
                console.log('logged in')
                // TODO: Authenticated save
                // Editing validation
            } else {
                console.log('guest detected');
                // Do anonymous submit
                // Start anonymous submit
                this.$store.dispatch('saveAnonymousLoadout');
            }

        },
        getIsLoggedIn() {
            return this.$store.getters.isLoggedIn;
        }
    }
};
</script>
