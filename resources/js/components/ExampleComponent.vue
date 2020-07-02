<template>
    <ApolloQuery :query="$options.query">
        <template slot-scope="{result: {loading, data, error }}">
            <div v-if="loading">Loading...</div>
            <div v-else-if="data">
                <ul>
                    <li v-for="gun in data.guns.data">{{gun.name}}: {{ JSON.parse(gun.json_stats) }}</li>
                </ul>
            </div>
        </template>
    </ApolloQuery>
</template>

<script>
    import gql from 'graphql-tag'

    export default {
        query: gql`
            query {
                guns {
                    data {
                        name
                        json_stats
                    }
                }
            }
        `,
    }
</script>
