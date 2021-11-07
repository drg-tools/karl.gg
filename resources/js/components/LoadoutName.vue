<template>
    <div class="form-group" :class="{ 'form-group--error': $v.name.$error }">
        <label class="form__label">Name</label>
        <input
            class="
                text-gray-900
                shadow-sm
                outline-none
                block
                w-full
                sm:text-sm
                border-gray-300
                rounded-md
                p-2
                mb-2
            "
            v-model="name"
            placeholder="Karl's amazing loadout"
        />

        <div v-if="$v.name.$error">
            <div class="error" v-if="!$v.name.required">Field is required</div>
            <div class="error" v-if="!$v.name.maxLength">
                Max {{ $v.name.$params.maxLength.max }} characters.
            </div>
        </div>
    </div>
</template>

<script>
import { required, maxLength } from "vuelidate/lib/validators";

export default {
    name: "LoadoutName",
    
    data() {
        return {
            name: "",
        };
    },
    created() {
        this.unsubscribe = this.$store.subscribe((mutation, state) => {
            // TODO: make this handle equipment as well as primary and secondary
            if (
                mutation.type === "setLoadoutName"
            ) {
                console.log(`Updating loadoutname`);
                this.name = this.$store.state.loadoutName;
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
        setName(value) {
            this.name = value;
            this.setLoadoutName(value); // Commit name to store when validating
            this.$v.name.$touch();
        },
    },
    computed: {
        message: {
            get() {
                return this.$store.state.loadoutName;
            },
            set(value) {
                this.$store.commit("setLoadoutName", value);
            },
        },
    },
};
</script>