<template>
    <ApolloQuery :query="$options.query">
	<template slot-scope="{result: {loading, data, error }}">
	    <div v-if="loading">Loading...</div>
	    <div v-else-if="data">
		<ul>
		    <li v-for="character in data.characters.data">{{ character.name }}</li>
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
	      characters {
		data {
		  id
		  name
		}
	      }
            }
	`
    }
</script>
