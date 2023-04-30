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
            <li class="nav-item">
              <router-link to="/adopt" class="nav-link">Adoption</router-link>
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
    <div class="container mt-3" style="overflow-y: auto;">
      <router-view></router-view>
    </div>
    <footer class="bg-dark text-light text-center py-3">
      <div class="container text-center">
        <div class="row justify-content-center align-items-center">
          <div class="d-flex justify-content-center">
            <ul class="list-inline mb-2">
              <li class="list-inline-item me-3">
                <a href="https://www.facebook.com/people/MeeFah-Homeless-Animals-Shelter/100079830543947/" target="_blank" class="text-light"><font-awesome-icon :icon="['fab', 'facebook']" /></a>
              </li>
              <li class="list-inline-item me-3">
                <a href="mailto:furryfuture22@gmail.com" target="_blank" class="text-light"><font-awesome-icon :icon="['fas', 'envelope']" /></a>
              </li>
              <li class="list-inline-item me-3">
                <a href="https://www.instagram.com/meefahshelter/" target="_blank" class="text-light"><font-awesome-icon :icon="['fab', 'instagram']" /></a>
              </li>
              <!-- <a href="#" class="me-3"><i class="fab fa-facebook fa-lg"></i></a>
            <a href="#" class="me-3"><i class="far fa-envelope fa-lg"></i></a>
            <a href="#" class="me-3"><i class="fab fa-instagram fa-lg"></i></a> -->
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
    <!-- <footer class="bg-dark text-light text-center py-3">
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-4 mb-3 mb-md-0">
          <ul class="list-unstyled d-flex justify-content-center">
            <li class="mx-3">
              <a href="#">
                <i class="fab fa-facebook-f fa-2x"></i>
              </a>
            </li>
            <li class="mx-3">
              <a href="#">
                <i class="far fa-envelope fa-2x"></i>
              </a>
            </li>
          </ul>
        </div>
        <div class="col-12 col-md-4 mb-3 mb-md-0">
          <ul class="list-unstyled">
            <li><router-link to="/" class="nav-link">Home</router-link></li>
            <li><router-link to="/adopt" class="nav-link">Adopt</router-link></li>
            <li><router-link to="/volunteer" class="nav-link">Volunteer</router-link></li>
            <li><router-link to="/donation" class="nav-link">Donation</router-link></li>
            <li><router-link to="/stray" class="nav-link">Stray</router-link></li>
          </ul>
        </div>
        <div class="col-12 col-md-4">
          <h5 class="mb-3">Subscribe to our newsletter</h5>
        </div>
      </div>
    </div>
  </footer> -->
  </div>
</template>

<script>
import $ from 'jquery';
import 'bootstrap';

export default {
  name: 'App',
  mounted() {
    $('.navbar-collapse').collapse();
  },
};
</script>

<style lang="scss">
/* Add your custom styles here */
#app {
  min-height: 100%;
  display: flex;
  flex-direction: column;
}

.navbar {
  padding: 15px;
  font-size: 18px;
}

footer {
  margin-top: auto;
}

/* footer {
  position: absolute;
  bottom: 0;
  width: 100%;
} */
</style>

  