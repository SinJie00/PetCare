<template>
  <div class="container mt-5 mb-5">
    <h1 class="text-center mb-5">StrayPost</h1>
    <div class="bg-light clearfix">
      <button v-if="isAuthenticated" class="btn btn-primary mb-3 float-right" data-bs-toggle="modal"
        data-bs-target="#strayPostModal">Add Stray
        Post</button>
      <router-link v-else class="btn btn-primary mb-3 float-right" to="/login">Add Stray Post</router-link>
    </div>
    <div class="form-group">
      <label for="location">Search Location*</label>
      <div class="input-group">
        <input type="text" class="form-control" id="location" v-model="searchInput" placeholder="Search for a location"
          @input="onSearchInput">
        <div class="input-group-append">
          <button class="btn btn-primary" @click="searchLocation">Search</button>
        </div>
      </div>
    </div>
    <GMapMap :center="strayPost.position" ref="mapRef" :zoom="16" map-type-id="terrain"
      style="width: 70vw; height: 40rem">
      <GMapMarker :key="marker.id" v-for="marker in markers" :position="marker.position" :clickable="true"
        @click="openMarker(marker.id)">
        <GMapInfoWindow :closeclick="true" @closeclick="openMarker(null)" :opened="openedMarkerID === marker.id">
          <div>
            <h3>{{ marker.title }}</h3>
            <div class="mb-3">
              <img :src="marker.image" alt="Stray Post Image" style="max-width: 200px; height: auto;">
            </div>
            <p><strong>Location:</strong> {{ marker.location }}</p>
            <p><strong>Description:</strong> {{ marker.description }}</p>
          </div>
        </GMapInfoWindow>
      </GMapMarker>
    </GMapMap>
  </div>
  <div class="modal" id="strayPostModal" ref="strayPostModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="btn btn-close" data-bs-dismiss="modal" @click="resetForm"
            aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label for="title">Title*</label>
              <input type="text" class="form-control" id="title" v-model="strayPost.title" required>
            </div>
            <div class="form-group">
              <label for="description">Description</label>
              <textarea class="form-control" id="description" v-model="strayPost.description" required></textarea>
            </div>
            <div class="form-group">
              <label for="image">Image*</label>
              <input type="file" class="form-control" id="image" ref="imageInput" accept="image/*"
                v-on:change="handleImage" />
              <img v-if="imageUrl" :src="imageUrl" class="mt-2" style="max-width: 200px;" />
            </div>
            <div class="form-group mt-2 mr-2">
              <p v-if="strayPost.location">Current Location: {{ strayPost.location }}</p>
            </div>
            <div class="form-group mt-4 row justify-content-end">
              <div class="col-auto mr-2">
                <button data-bs-dismiss="modal" type="submit" class="btn btn-primary"
                  @click.prevent="addStrayPost">Create</button>
              </div>
              <div class="col-auto">
                <button type="button" data-bs-dismiss="modal" class="btn btn-danger" @click="resetForm">Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
  
<script>
export default {
  name: 'StrayPost',
  data() {
    return {
      openedMarkerID: null,
      /* autocompleteService: null,
      autocompleteLocations: [],
      showAutocomplete: false, */
      searchInput: '',
      markers: [],
      strayPosts: [],
      strayPost: {
        title: '',
        description: '',
        image: '',
        location: '',
        position: { lat: 0, lng: 0 },
        map: null,
        marker: null
      }
    };
  },
  mounted() {
    this.$refs.mapRef.$mapPromise.then((map) => {
      this.map = map;
      console.log('map is loaded now', map);
    });
    this.getStrayPosts();
    this.getUserLocation();
    /* this.autocompleteService = new google.maps.places.AutocompleteService(); */
  },
  computed: {
    isAuthenticated() {
      return this.$store.getters['auth/isLoggedIn'];
    },
    imageUrl() {
      if (this.strayPost.image) {
        return URL.createObjectURL(this.strayPost.image);
      } else if (this.strayPost.image) {
        return this.strayPost.image;
      } else {
        return null;
      }
    },
  },
  methods: {
    openMarker(id) {
      this.openedMarkerID = id
    },
    getStrayPosts() {
      axios.get('http://localhost:81/api/strayposts')
        .then((response) => {
          const strayPosts = response.data;
          console.log(strayPosts);
          console.log('start render markers');
          // Map the strayPosts to marker objects
          this.markers = strayPosts.map((strayPost) => ({
            id: strayPost.id,
            position: {
              lat: parseFloat(strayPost.latitude),
              lng: parseFloat(strayPost.longitude),
            },
            title: strayPost.title,
            image: strayPost.image,
            location: strayPost.location,
            description: strayPost.description,
          }));
          console.log(this.markers);
        })
        .catch((error) => {
          // Handle the error
          console.log(error.response.data);
        });
    },
    getUserLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
          const lat = position.coords.latitude;
          const lng = position.coords.longitude;
          console.log(lat);
          console.log(lng);
          this.strayPost.position = { lat: lat, lng: lng };
          // Use the Geocoding API to fetch the address
          const geocoder = new google.maps.Geocoder();
          const latlng = new google.maps.LatLng(lat, lng);
          geocoder.geocode({ location: latlng }, (results, status) => {
            if (status === 'OK') {
              if (results[0]) {
                console.log(results[0].formatted_address);
                this.strayPost.location = results[0].formatted_address;
              } else {
                console.error('No results found for current location.');
              }
            } else {
              console.error('Geocoder failed due to:', status);
            }
          });
        }, (error) => {
          console.error('Error getting user location:', error);
        });
      } else {
        console.error('Geolocation is not supported by this browser.');
      }
    },
    /* searchLocation() {
      const geocoder = new google.maps.Geocoder();
      geocoder.geocode({ address: this.searchInput }, (results, status) => {
        if (status === 'OK' && results.length > 0) {
          const location = results[0].geometry.location;
          this.strayPost.location = {
            lat: location.lat(),
            lng: location.lng(),
          };
        } else {
          console.error('Geocode was not successful for the following reason:', status);
        }
      });
    }, */
    searchLocation() {
      if (this.selectedLocation === 'current') {
        this.getUserLocation();
      }
      else {
        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({ address: this.searchInput }, (results, status) => {
          if (status === 'OK' && results.length > 0) {
            const location = results[0].geometry.location;
            this.strayPost.location = results[0].formatted_address;
            this.strayPost.position = {
              lat: location.lat(),
              lng: location.lng(),
            };

            this.$refs.mapRef.$mapPromise.then((map) => {
              map.setCenter(location);
            });
          } else {
            console.error('Geocode was not successful for the following reason:', status);
          }
        });
      }
    },
    addStrayPost() {
      const formData = new FormData();
      formData.append('title', this.strayPost.title);
      formData.append('description', this.strayPost.description);
      formData.append('image', this.strayPost.image);
      formData.append('location', this.strayPost.location);
      formData.append('latitude', this.strayPost.position.lat);
      formData.append('longitude', this.strayPost.position.lng);
      formData.append('users_id', this.$store.state.auth.user.id);
      for (let [key, value] of formData.entries()) {
        console.log(key, value);
      }

      axios.post('http://localhost:81/api/strayposts', formData)
        .then(response => {
          console.log('api ok');
          console.log(response.data);
          this.strayPosts.push(response.data);
          // Clear the form input values
          this.resetForm();
          toastr.success('Stray post created successfully');
        })
        .catch((error) => {
          // Handle the error
          console.log(error.response.data);
          toastr.error('Stray post created unsuccessfully');
        });
    },
    resetForm() {
      this.strayPost = {
        title: '',
        description: '',
        image: '',
        location: '',
      };
      this.image = null;
      this.imageUrl = '';
      this.$refs.imageInput.value = '';
    },
    handleImage(event) {
      this.strayPost.image = event.target.files[0];
    }
  },
  onSearchInput() {
    if (this.searchInput === '') {
      this.autocompleteLocations = [];
      this.showAutocomplete = false;
      return;
    }

    // Fetch autocomplete predictions using the AutocompleteService
    this.autocompleteService.getPlacePredictions(
      {
        input: this.searchInput,
        componentRestrictions: { country: 'your_country_code' }, // Add your country code here
      },
      (predictions, status) => {
        if (status === google.maps.places.PlacesServiceStatus.OK) {
          this.autocompleteLocations = predictions;
          this.showAutocomplete = true;
        } else {
          console.error('Autocomplete request failed:', status);
        }
      }
    );
  },

  selectAutocompleteLocation(location) {
    // Set the selected location as the search input
    this.searchInput = location.description;
    this.showAutocomplete = false;

    // Use Geocoding to get the coordinates for the selected location
    const geocoder = new google.maps.Geocoder();
    geocoder.geocode({ placeId: location.place_id }, (results, status) => {
      if (status === google.maps.GeocoderStatus.OK && results.length > 0) {
        const selectedLocation = results[0].geometry.location;
        this.strayPost.location = {
          lat: selectedLocation.lat(),
          lng: selectedLocation.lng(),
        };
      } else {
        console.error('Geocode request failed:', status);
      }
    });
  },
}
</script>
  
<style scoped>
/* Add your styles here */
</style>
  