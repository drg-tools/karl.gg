<template>
    
</template>

<script>
import { required, maxLength } from "vuelidate/lib/validators";

export default {
    name: "LoadoutName",
    data() {
        return {
            name: "",
            unsubscribe: "",
        };
    },
    created() {
        this.unsubscribe = this.$store.subscribe((mutation, state) => {
            // TODO: make this handle equipment as well as primary and secondary
            if (mutation.type === "setLoadoutName") {
                console.log(`Updating loadoutname`);
                this.name = state.loadoutName;
            }
        });
    },
    beforeDestroy() {
        this.unsubscribe();
    },
    // TODO: Validations need to be hooked up with the actual saving function. Look more at that in vuelidate docs
    validations: {
        name: {
            required,
            maxLength: maxLength(255),
        },
    },
    methods: {
        debounceInput: _.debounce(function (e) {
            // this.name = e.target.value;
            this.$store.commit("setLoadoutName", e.target.value);
        }, 500),
    },
};
</script>