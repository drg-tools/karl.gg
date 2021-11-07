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
            v-on:input="debounceInput"
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
            this.$store.commit("setLoadoutName",  e.target.value);
        }, 500),
    },
};
</script>