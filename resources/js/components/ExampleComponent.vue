<template>
    <div>
        <ul>
            <li v-for="character in characters">{{ character.name }}</li>
        </ul>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                characters: []
            }
        },

        mounted () {
            fetch('/api/characters')
                .then(response => response.json())
                .then(data => this.characters = data)
        }
    }
</script>
