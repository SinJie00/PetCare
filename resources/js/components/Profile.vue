<template>
    <div>
      <h2>User Profile</h2>
      <p>Name: {{ user.name }}</p>
      <p>Email: {{ user.email }}</p>
    </div>
  </template>
  
  <script>
  export default {
    created() {
      this.$store.dispatch('getUser')
        .then(response => {
          // handle success
        })
        .catch(error => {
          // handle error
        })
    },
  
    computed: {
      user() {
        return this.$store.getters.user
      }
    }
  }
  </script>
  