<template>
<div>
   <v-content>
    <router-view></router-view>
   </v-content>
   </div>
   
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
