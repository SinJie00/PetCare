<template>
  <div class="container mt-5 mb-5">
    <h1 class="text-center mb-5">StrayPost</h1>
    <div class="bg-light clearfix">
      <button class="btn btn-primary mb-3 float-right" data-bs-toggle="modal" data-bs-target="#strayPostModal">Add Stray
        Post</button>
    </div>
    <!-- <GMapMap
      :center="{lat: 51.093048, lng: 6.842120}"
      :zoom="7"
      map-type-id="terrain"
      style="width: 100vw; height: 900px"
  >
  </GMapMap> -->
    <GMapMap :center="center" ref="mapRef" :zoom="16" map-type-id="terrain" style="width: 70vw; height: 40rem">
      <GMapCluster>
        <GMapMarker :key="index" v-for="(m, index) in markers" :position="m.position" @click="openInfoWindow(marker.id)"
          :clickable="true" :draggable="true" />
      </GMapCluster>
    </GMapMap>
    <!-- @click="center=m.position" -->

  </div>
  <div class="modal" id="strayPostModal" ref="strayPostModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="btn btn-close" data-bs-dismiss="modal" @click="resetForm"
            aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form novalidate>
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
              <div class="form-group">
                <label for="location">Location*</label>
                <!-- <button class="btn btn-primary me-2" @click="useCurrentLocation">Use Current Location</button>
                <input type="text" class="form-control flex-grow-1" id="location" v-model="searchInput"
                  placeholder="Search for a location">
                <button class="btn btn-primary" @click="searchLocation">Search</button> -->
              </div>
              <p>Current Location: {{ currentAddress }}</p>
              <!-- <p v-if="strayPost.location">Latitude: {{ strayPost.location.lat }}, Longitude: {{ strayPost.location.lng }} -->
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
      center: { lat: 0, lng: 0 },
      strayPost: {
        title: '',
        description: '',
        image: '',
        location: '',
        map: null,
        marker: null
      }
    };
  },
  mounted() {
    this.getUserLocation();
    this.$refs.mapRef.$mapPromise.then((map) => {
      this.map = map;
      console.log('map is loaded now', map);
    });
  },
  /* mounted() {
    gmapApi.then(() => {
      this.map = new google.maps.Map(this.$refs.map, {
        center: { lat: 0, lng: 0 },
        zoom: 1
      });

      this.map.addListener('click', (event) => {
        this.placeMarker(event.latLng);
      });
    });
  }, */
  methods: {
    getUserLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
          const lat = position.coords.latitude;
          const lng = position.coords.longitude;
          /* this.center = {
            lat: position.coords.latitude,
            lng: position.coords.longitude,
          };
          */
          this.center = { lat: lat, lng: lng };
          // Wait for Google Maps library to be loaded
      google.maps.event.addDomListener(window, 'load', () => {
        // Use the Geocoding API to fetch the address
        const geocoder = new google.maps.Geocoder();
        const latlng = new google.maps.LatLng(lat, lng);

        geocoder.geocode({ location: latlng }, (results, status) => {
          if (status === 'OK') {
            if (results[0]) {
              this.strayPost.location = results[0].formatted_address;
              console.log(this.strayPost.location);
            } else {
              console.error('No results found for current location.');
            }
          } else {
            console.error('Geocoder failed due to:', status);
          }
        });
      });
    }, (error) => {
      console.error('Error getting user location:', error);
    });
      } else {
        console.error('Geolocation is not supported by this browser.');
      }
    },
    searchLocation() {
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
    },
    placeMarker(latLng) {
      if (this.marker) {
        this.marker.setMap(null);
      }

      this.marker = new google.maps.Marker({
        position: latLng,
        map: this.map
      });

      this.location.then((response) => {
        // Handle the response
        console.log(response.data);
        // Clear the form input values
        this.title = '';
        this.description = '';
        this.image = '';
        this.location = '';
        // Remove the marker from the map
        this.marker.setMap(null);
        // Retrieve the stray posts again to refresh the map markers
        this.getStrayPosts();
      })
        .catch((error) => {
          // Handle the error
          console.log(error.response.data);
        });
    },
    getStrayPosts() {
      axios.get('/api/stray-posts')
        .then((response) => {
          const strayPosts = response.data;

          // Clear previous markers
          this.clearMarkers();

          // Add markers for each stray post
          strayPosts.forEach((strayPost) => {
            const [lat, lng] = strayPost.coordinates;
            const marker = new google.maps.Marker({
              position: { lat: parseFloat(lat), lng: parseFloat(lng) },
              map: this.map
            });
            // Add click listener to display info window or navigate to post details
            marker.addListener('click', () => {
              // Handle click event for marker
              console.log('Marker clicked!');
              // Add your desired behavior here, such as opening an info window or navigating to post details
            });

            // Store the marker for future reference
            this.markers.push(marker);
          });
        })
        .catch((error) => {
          // Handle the error
          console.log(error.response.data);
        });
    },
    clearMarkers() {
      this.markers.forEach((marker) => {
        marker.setMap(null);
      });
      this.markers = [];
    },
    createStrayPost() {
      const formData = new FormData();
      formData.append('title', this.title);
      formData.append('description', this.description);
      formData.append('image', this.image);
      formData.append('location', this.location);

      axios.post('/api/stray-posts', formData)
        .then((response) => {
          // Handle the response
          console.log(response.data);
          // Clear the form input values
          this.title = '';
          this.description = '';
          this.image = '';
          this.location = '';
          // Remove the marker from the map
          this.marker.setMap(null);
          // Navigate to a different page (if desired)
          this.router.push('/stray-posts');
        })
        .catch((error) => {
          // Handle the error
          console.log(error.response.data);
        });
    },
  },
}
</script>
  
<style scoped>
/* Add your styles here */
</style>
  