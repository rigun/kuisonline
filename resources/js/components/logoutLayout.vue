<template>

</template>

<script>
    import store from '../store'
    export default {
        mounted () {
            localStorage.removeItem('token')
            localStorage.removeItem('role')
            localStorage.removeItem('status')
            localStorage.removeItem('id')
            localStorage.removeItem('username')
            store.commit('logoutUser')
            this.$router.push({ name: 'LoginLayout' })
        }
    }
</script>