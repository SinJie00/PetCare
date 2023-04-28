<template>
  <div id="app">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container">
        <router-link to="/" class="navbar-brand">PetCare</router-link>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <router-link to="/" class="nav-link">Home</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/adopt" class="nav-link">Adopt</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/volunteer" class="nav-link">Volunteer</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/donation" class="nav-link">Donation</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/article" class="nav-link">Article</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/stray" class="nav-link">Stray</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/login" class="nav-link">Login</router-link>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container mt-3">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
export default {
  name: 'App'
}
</script>

<style lang="scss">
/* Add your custom styles here */
</style>

  