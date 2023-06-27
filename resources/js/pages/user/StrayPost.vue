<template>
  <div class="container mt-5 mb-5">
    <h1 class="text-center mb-5">Stray Post</h1>
    <div class="bg-light clearfix">
      <button v-if="isAuthenticated" class="btn btn-primary mb-3 float-right" data-bs-toggle="modal"
        data-bs-target="#strayPostModal" @click="openModal">Add Stray Post</button>
      <router-link v-else class="btn btn-primary mb-3 float-right" to="/login">Add Stray Post</router-link>
    </div>
    <!-- <div class="form-group">
      <label for="location">Search Location*</label>
      <div class="input-group">
        <input type="text" class="form-control" id="location" v-model="searchInput" placeholder="Search for a location"
          @input="onSearchInput">
        <div class="input-group-append">
          <button class="btn btn-primary" @click="searchLocation">Search</button>
        </div>
      </div>
    </div> -->
    <div class="form-group">
      <div class="input-group">
        <input type="text" class="form-control" id="location" v-model="searchInput" placeholder="Search for a location"
          @input="onSearchInput" @click="clearSearchInput">
        <!-- <div class="input-group-append">
          <button class="mr-2 btn btn-primary" @click="searchLocation">Search</button>
        </div> -->
      </div>
      <ul v-if="autocompleteResults.length" class="list-group mt-2">
        <li v-for="(result, index) in autocompleteResults" :key="index" class="list-group-item"
          @click="selectAutocompleteResult(result)">{{ result.description }}</li>
      </ul>
      <button class="btn btn-primary mt-2 mb-2" @click="useCurrentLocation">Use Current Location</button>
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
          <h2 class="modal-title">Create Stray Post</h2>
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
              <p v-if="computedLocation">Location: {{ computedLocation }}</p>
            </div>
            <input type="hidden" name="lat" :value="strayPost.position.lat" />
            <input type="hidden" name="lng" :value="strayPost.position.lng" />
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
      autocompleteResults: [],
      markers: [],
      strayPosts: [],
      imageFile: '',
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
  /* watch: {
  'strayPost.location': function(newLocation) {
    this.strayPost.location = newLocation;
    console.log('strayPost.location changed:', newLocation);
  }
  }, */
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
      if (this.imageFile) {
        console.log('image url');
        console.log(this.imageFile);
        return URL.createObjectURL(this.imageFile);
      } else if (this.strayPost.image) {
        return this.strayPost.image;
      } else {
        return null;
      }
    },
    computedLocation() {
      console.log('computed');
      console.log(this.strayPost.location);
      return this.strayPost.location;
    }
  },
  methods: {
    openMarker(id) {
      this.openedMarkerID = id
    },
    clearSearchInput() {
      this.searchInput = '';
    },
    openModal() {
      if (this.strayPost.location === '') {
        // Display an error message or handle the validation error as desired
        alert('Location is required');
        $('#strayPostModal').modal('hide');
        return;
      }
      else{
        $('#strayPostModal').modal('show');
      }
    },
    getStrayPosts() {
      axios.get('https://petcare-ec207baddaf0.herokuapp.com/api/strayposts')
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
    useCurrentLocation() {
      this.getUserLocation();
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
        console.log('select current');
        this.getUserLocation();
      }
      else {
        console.log('new location');
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
            console.log('stray post location');
            console.log(this.strayPost.location);
          } else {
            console.error('Geocode was not successful for the following reason:', status);
          }
        });
      }
    },
    selectAutocompleteResult(result) {
      this.searchInput = result.description;
      this.autocompleteResults = [];
      this.searchLocation();
    },
    onSearchInput() {
      console.log('search input');
      if (this.searchInput === '') {
        this.autocompleteResults = [];
        return;
      }
      const service = new google.maps.places.AutocompleteService();
      service.getPlacePredictions({ input: this.searchInput }, (predictions, status) => {
        if (status === google.maps.places.PlacesServiceStatus.OK) {
          this.autocompleteResults = predictions;
        } else {
          this.autocompleteResults = [];
        }
      });
    },
    addStrayPost() {
      console.log('calling this method');
      const formData = new FormData();
      formData.append('title', this.strayPost.title);
      formData.append('description', this.strayPost.description);
      formData.append('image', this.imageFile);
      formData.append('location', this.strayPost.location);
      formData.append('latitude', this.strayPost.position.lat);
      formData.append('longitude', this.strayPost.position.lng);
      formData.append('users_id', this.$store.state.auth.user.id);
      console.log('show adding');
      for (let [key, value] of formData.entries()) {
        console.log(key, value);
      }

      axios.post('https://petcare-ec207baddaf0.herokuapp.com/api/strayposts', formData)
        .then(response => {
          console.log('api ok');
          console.log(response.data);
          this.strayPosts.push(response.data);
          // Clear the form input values
          this.resetForm();
          toastr.success('Stray post created successfully');
          location.reload();
        })
        .catch((error) => {
          // Handle the error
          console.log(error.response.data);
          toastr.error('Stray post created unsuccessfully');
        });
    },
    resetForm() {
      console.log('reset');
        this.strayPost = {
        title: '',
        description: '',
        image: '',
        /* location: '', */
      };
      this.imageFile = null;
      this.$refs.imageInput.value = '';
      this.searchInput = '';
      this.autocompleteResults = [];

    },
    handleImage(event) {
      console.log('handle image');
      this.imageFile = event.target.files[0];
      console.log(this.imageFile);
    }
  },
  /* onSearchInput() {
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
  }, */
}
</script>
  
<style scoped>
/* Add your styles here */
</style>
  