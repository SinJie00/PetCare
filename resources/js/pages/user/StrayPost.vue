<template>
  <div class="container mt-5 mb-5">
    <h1 class="text-center mb-5">Stray Post</h1>
    <div class="bg-light clearfix">
      <button v-if="isAuthenticated" class="btn btn-primary mb-3 float-right" data-bs-toggle="modal"
        data-bs-target="#strayPostModal" @click="openModal">Add Stray Post</button>
      <router-link v-else class="btn btn-primary mb-3 float-right" to="/login">Add Stray Post</router-link>
    </div>
    <div class="form-group">
      <div class="input-group">
        <!-- <label class="form-label fw-bold">Search location</label> -->
        <input type="text" class="form-control" id="location" v-model="searchInput" placeholder="Search for a location"
          @input="onSearchInput" @click="clearSearchInput">
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
              <label for="title" class="form-label fw-bold">Title<span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="title" v-model="strayPost.title" required :class="{ 'is-invalid': v$.strayPost.title.$error }">
              <div v-if="v$.strayPost.title.$error" class="invalid-feedback">Title is required.</div>
            </div>
            <div class="form-group mt-3">
              <label for="description" class="form-label fw-bold">Description<span class="text-danger">*</span></label>
              <textarea class="form-control" id="description" v-model="strayPost.description" required :class="{ 'is-invalid': v$.strayPost.description.$error }"></textarea>
              <div v-if="v$.strayPost.description.$error" class="invalid-feedback">Description is required.</div>
            </div>
            <div class="form-group mt-3">
              <label for="image" class="form-label fw-bold">Image<span class="text-danger">*</span></label>
              <input type="file" class="form-control" id="image" ref="imageInput" accept="image/*"
                v-on:change="handleImage" :class="{ 'is-invalid': v$.imageFile.$error }" @input="$formulate.reset('strayPost.image')"/>
                <div v-if="v$.imageFile.$error" class="invalid-feedback">Image is required.</div>
              <!-- <div v-if="v$.strayPost.image.$dirty && $v.strayPost.image.$error" class="text-danger">Image is
                required.</div> -->
              <!-- <div v-if="v$.strayPost.image.$pending && $v.strayPost.image.$error" class="text-danger">Invalid image
                file.</div> -->
              <img v-if="imageUrl" :src="imageUrl" class="mt-2" style="max-width: 200px;" />
            </div>
            <div class="form-group mt-3 mr-2">
              <p v-if="computedLocation"><b>Location:</b> {{ computedLocation }}</p>
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
import { useVuelidate } from '@vuelidate/core'
import { required } from '@vuelidate/validators';
import { Loader } from '@googlemaps/js-api-loader';
export default {
  name: 'StrayPost',
  //setup: () => ({ v$: useVuelidate() }),
  setup() {
  const v$ = useVuelidate();
  return { v$ };
  },
  data() {
    return {
      openedMarkerID: null,
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
  validations() {
    return {
      imageFile: {required},
      strayPost: {
        title: { required },
        description: { required },
        //image: { required },
      }
    }
  },
  mounted() {
    this.$refs.mapRef.$mapPromise.then((map) => {
      this.map = map;
      console.log('map is loaded now', map);
    });
    /* this.loadGoogleMapsAPI().then(() => {
      console.log('Google Maps API loaded successfully');
      // Call your function or perform any operations that require the API here
    }).catch((error) => {
      console.error('Error loading Google Maps API:', error);
    }); */
    this.getStrayPosts();
    this.getUserLocation();
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
    /* loadGoogleMapsAPI() {
      const loader = new Loader({
        apiKey: 'AIzaSyAfrTNx21m1BpOFe12uPZsCof8An3TKutk', // Replace with your own API key
        libraries: ['places'],
      });
      return loader.load();
    }, */
    openMarker(id) {
      this.openedMarkerID = id
    },
    clearSearchInput() {
      this.searchInput = '';
    },
    openModal() {
      if (this.strayPost.location === '') {
        // Display a warning message for required location
        this.$swal({
          title: 'Alert Message',
          text: 'Location is required. Please use your current location or input a location.',
          icon: 'warning',
        });
        $('#strayPostModal').modal('hide');
        return;
      }
      else {
        $('#strayPostModal').modal('show');
      }
    },
    getStrayPosts() {
      axios.get('/api/strayposts')
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
      //event.preventDefault();
      this.v$.$touch(); 
      console.log(this.v$.strayPost.title.$error);
      console.log(this.v$.$validate());
      console.log(this.v$.$error);
      console.log(this.v$.strayPost.image.$dirty);
      console.log(this.v$.strayPost.image.$error);
      if(this.v$.$error)
        $('#strayPostModal').modal('show');
      //const isFormCorrect = this.v$.$validate()
      // you can show some extra alert to the user or just leave the each field to show it's `$errors`.
      else {
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

        axios.post('/api/strayposts', formData)
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
      }
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
      this.v$.$reset();
    },
    handleImage(event) {
      console.log('handle image');
      this.imageFile = event.target.files[0];
      console.log(this.imageFile);
    }
  },
}
</script>
  
  