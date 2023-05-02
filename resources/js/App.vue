<template>
  <div id="app">
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #FFB6C1;">
      <div class="container">
        <router-link to="/" class="navbar-brand">PetCare</router-link>
        <div class="nav-el" id="name_project">
          <img src="../assets/img/logo.png" width="60" class="me-2" alt="" />
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <router-link to="/" class="nav-link">Home</router-link>
            </li>
            <li class="nav-item dropdown" v-if="isAdmin">
              <a class="nav-link dropdown-toggle" href="#" id="adoptionNavbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">Adoption</a>
              <div class="dropdown-menu" aria-labelledby="adoptionNavbarDropdown">
                <router-link to="/admin/adoptionlist" class="dropdown-item">Adoption List</router-link>
                <router-link to="/admin/adoptionapplication" class="dropdown-item">Adoption Application</router-link>
              </div>
            </li>
            <li class="nav-item" v-else>
              <router-link to="/adoption" class="nav-link">Adoption</router-link>
            </li>
            <li class="nav-item">
              <router-link v-if="isAdmin" to="/admin/volunteerapplication" class="nav-link">Volunteer</router-link>
              <router-link v-else to="/volunteerapplication" class="nav-link">Volunteer</router-link>
            </li>
            <li class="nav-item" v-if="isAdmin">
              <router-link to="/admin/donation" class="nav-link">Donation</router-link>
            </li>
            <li class="nav-item dropdown" v-else>
              <a class="nav-link dropdown-toggle" href="#" id="donationNavbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">Donation</a>
              <div class="dropdown-menu" aria-labelledby="donationNavbarDropdown">
                <router-link to="/productdonation" class="dropdown-item">Product Donation</router-link>
                <router-link to="/moneydonation" class="dropdown-item">Money Donation</router-link>
              </div>
            </li>
            <li class="nav-item">
              <router-link v-if="isAdmin" to="/admin/article" class="nav-link">Article</router-link>
              <router-link v-else to="/article" class="nav-link">Article</router-link>
            </li>
            <li class="nav-item">
              <router-link v-if="isAdmin" to="/admin/stray" class="nav-link">Stray</router-link>
              <router-link v-else to="/stray" class="nav-link">Stray</router-link>
            </li>
            <li v-if="!user" class="nav-item">
              <router-link to="/login" class="nav-link">Login</router-link>
            </li>
            <li v-if="user" class="nav-item mt-1">
              <router-link to="/profile" class="text-dark" style="margin-left: 5px; margin-right: 15px">
                <font-awesome-icon :icon="['fas', 'user-circle']" class="fa-circle" size="2x" />
              </router-link>
            </li>
            <li v-if="user" class="nav-item mt-1">
              <button class="btn btn-danger" @click.prevent="logout">Logout</button>
            </li>
          </ul>
          <!-- <div v-if="user" class="navbar-text ml-auto mr-3">{{ user.name }}</div>
          <button class="btn btn-danger" @click="logout">Logout</button> -->
        </div>
      </div>
    </nav>
    <div class="router-view-container mt-3">
      <router-view></router-view>
    </div>
    <footer class="bg-dark text-light text-center py-3">
      <div class="container text-center">
        <div class="row justify-content-center align-items-center">
          <div class="d-flex justify-content-center">
            <ul class="list-inline mb-2">
              <li class="list-inline-item me-3">
                <a href="https://www.facebook.com/people/MeeFah-Homeless-Animals-Shelter/100079830543947/" target="_blank"
                  class="text-light"><font-awesome-icon :icon="['fab', 'facebook']" /></a>
              </li>
              <li class="list-inline-item me-3">
                <a href="mailto:furryfuture22@gmail.com" target="_blank" class="text-light"><font-awesome-icon
                    :icon="['fas', 'envelope']" /></a>
              </li>
              <li class="list-inline-item me-3">
                <a href="https://www.instagram.com/meefahshelter/" target="_blank" class="text-light"><font-awesome-icon
                    :icon="['fab', 'instagram']" /></a>
              </li>
            </ul>
          </div>
          <div class="d-flex justify-content-center">
            <ul class="list-inline mb-0 text-md-end">
              <li class="list-inline-item me-3">
                <router-link to="/" class="nav-link">Home</router-link>
              </li>
              <li class="list-inline-item me-3">
                <router-link to="/adopt" class="nav-link">Adoption</router-link>
              </li>
              <li class="list-inline-item me-3">
                <router-link to="/volunteer" class="nav-link">Volunteer</router-link>
              </li>
              <li class="list-inline-item me-3">
                <router-link to="/donation" class="nav-link">Donation</router-link>
              </li>
              <li class="list-inline-item me-3"><router-link to="/stray" class="nav-link">Stray</router-link></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="text-center mt-3">
        &copy; 2023 PetCare. All Rights Reserved.
      </div>
    </footer>
  </div>
</template>

<script>
import 'bootstrap';

export default {
  name: 'App',
  /* mounted() {
    $('.navbar-collapse').collapse();
  }, */
  /* data() {
    return {
      user: null
    }
  }, */
  computed: {
    user() {
      /* console.log('login here'); */
      console.log(this.$store.state.auth.user);
      const user = this.$store.state.auth.user;
      //return Object.keys(user).length ? user : null;
      if (Object.keys(user).length === 0) {
        console.log('return null user');
        return null;
      }
      console.log('user here');
      return user;
    },
    isAdmin() {
      return this.$store.getters['auth/isAdmin'];/* this.$store.state.auth.userRole == 2; */
    }
    /* isAuthenticated() {
      return !!this.user.id;
    } */
  },
  /* created() {
    const user = JSON.parse(localStorage.getItem('user'))
    if (user) {
      this.user = user;
    }
  }, */
  methods: {
    logout() {
      console.log('logout');
      this.$store.dispatch('auth/logout');
      this.user = null;
      this.$router.push('/');
      console.log('end here');
    },
    /* toggleDropdown(event) {
    event.stopPropagation();
    this.$nextTick(() => {
      $(event.target).parent().toggleClass('show');
    });
  } */
  },
  /* mounted() {
    $('.dropdown-toggle').dropdown();
  } */
  /* watch: {
    $store: {
      handler(newVal) {
        if (newVal.getters.isLoggedIn) {
          this.user = newVal.getters.user
        } else {
          this.user = null
        }
      },
      deep: true
    }
  } */
};
</script>

<style lang="scss">
/* Add your custom styles here */
#app {
  min-height: 100%;
  display: flex;
  flex-direction: column;
}

.router-view-container {
  flex: 1;
  min-height: calc(100vh - 190px);
  /* 112px is the height of the navbar and footer */
}

.navbar {
  padding: 15px;
  font-size: 18px;
}

.fixed-bottom {
  height: 100px;
}

/* footer {
  position: absolute;
  bottom: 0;
  width: 100%;
} */</style>

  