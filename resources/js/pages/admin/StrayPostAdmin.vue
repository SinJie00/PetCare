<template>
  <div class="container mt-5 mb-5">
    <h1 class="text-center mb-5">Stray Post</h1>
    <div class="modal" id="strayPostModal" refs="strayPostModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h2 class="modal-title">Edit Stray Post</h2>
            <button type="button" class="btn btn-close" data-bs-dismiss="modal" @click="resetForm"
              aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-group">
                <label for="title" class="form-label fw-bold">Title<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" v-model="strayPost.title" required
                  :class="{ 'is-invalid': v$.strayPost.title.$error }">
                <div v-if="v$.strayPost.title.$error" class="invalid-feedback">Title is required.</div>
              </div>
              <div class="form-group mt-3">
                <label for="description" class="form-label fw-bold">Description<span class="text-danger">*</span></label>
                <textarea type="text" class="form-control" id="description" min="1" v-model="strayPost.description"
                  required :class="{ 'is-invalid': v$.strayPost.description.$error }"></textarea>
                <div v-if="v$.strayPost.description.$error" class="invalid-feedback">Description is required.</div>
              </div>
              <div class="form-group mt-3">
                <label for="location" class="form-label fw-bold">Location<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="location" v-model="strayPost.location" required
                  :class="{ 'is-invalid': v$.strayPost.location.$error }" @input="onSearchInput">
                <ul v-if="autocompleteResults.length" class="list-group mt-2">
                  <li v-for="(result, index) in autocompleteResults" :key="index" class="list-group-item"
                    @click="selectAutocompleteResult(result)">{{ result.description }}</li>
                </ul>
                <div v-if="v$.strayPost.location.$error" class="invalid-feedback">Location is required.</div>
              </div>
              <div class="form-group mt-3">
                <label for="image" class="form-label fw-bold">Image<span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="image" ref="imageInput" accept="image/*"
                  v-on:change="handleImage" />
                <img v-if="imageUrl" :src="imageUrl" class="mt-2" style="max-width: 200px;" />
              </div>
              <div class="form-group mt-3 mt-4 row justify-content-end">
                <div class="col-auto">
                  <button data-bs-dismiss="modal" type="submit" class="btn btn-primary"
                    @click.prevent="editStrayPost(strayPost.id)">Update</button>
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
    <div>
      <data-table class="table table-bordered table-responsive" width="100%" :data="strayPosts" :columns="columns"
        :options="options"></data-table>
    </div>
  </div>
</template>

<script>
import { useVuelidate } from '@vuelidate/core'
import { required } from '@vuelidate/validators';
import { Loader } from '@googlemaps/js-api-loader';
export default {
  name: 'StrayPost',
  setup() {
    const v$ = useVuelidate();
    return { v$ };
  },
  validations() {
    return {
      strayPost: {
        title: { required },
        description: { required },
        location: { required },
      }
    }
  },
  data() {
    const vm = this;
    return {
      strayPosts: [],
      searchInput: '',
      autocompleteResults: [],
      //modalMode: '',
      imageFile: '',
      strayPost: {
        title: '',
        description: '',
        location: '',
        position: { lat: 0, lng: 0 },
        image: '',
      },
      columns: [
        { title: 'ID', data: 'id' },
        { title: 'Title', data: 'title' },
        {
          title: 'Image', data: 'image',
          render: function (data, type, row) {
            if (data) {
              return '<img src="' + data + '" width="200" height="150"/>';
            }
            else {
              return '';
            }
          }
        },
        { title: 'Description', data: 'description' },
        { title: 'Location', data: 'location' },
        {
          title: 'Author Name',
          render: function () {
            // Return an empty string for immediate rendering
            return '';
          }
        },
        { title: 'Author ID', data: 'users_id' },
        {
          title: 'Author Email',
          render: function () {
            return '';
          }
        },
        {
          title: 'Author Phone',
          render: function () {
            return '';
          }
        },
        {
          title: 'Actions',
          render: function (data, type, row) {
            return `
              <button class="btn btn-sm btn-success edit-btn" data-id="${row.id}">Edit</button> 
              <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">Delete</button>
            `;
          },
        }
      ],
      options: {
        drawCallback() {
          const table = this.api();
          // Fetch user details for each row
          table.rows().every(function () {
            const row = this.node();
            const rowData = this.data();
            const userId = rowData.users_id;
            axios.get(`/api/users/${userId}`)
              .then(response => {
                const userName = response.data.user.name || 'N/A';
                const email = response.data.user.email || 'N/A';
                const phone = response.data.user.phone || 'N/A';
                const nameColumn = table.cell(row, 5).node(); // Get the cell element
                const emailColumn = table.cell(row, 7).node();
                const phoneColumn = table.cell(row, 8).node();

                $(nameColumn).text(userName); // Set the user name in the cell
                $(emailColumn).text(email);
                $(phoneColumn).text(phone);
              })
              .catch(error => {
                console.log('Error fetching user details:', error);
                const nameColumn = table.cell(row, 5).node();
                const emailColumn = table.cell(row, 7).node();
                const phoneColumn = table.cell(row, 8).node();
                $(nameColumn).text('N/A'); // Set N/A if an error occurred
                $(emailColumn).text('N/A');
                $(phoneColumn).text('N/A');
              });
          });

          $('.edit-btn').on('click', event => {
            const strayPostId = $(event.currentTarget).data('id');
            getStrayPostAndShowModal(strayPostId);
          });
          async function getStrayPostAndShowModal(strayPostId) {
            const strayPostData = await vm.getStrayPost(strayPostId);
            vm.strayPost = strayPostData.strayPost;
            $('#strayPostModal').modal('show');
          }
          $('.delete-btn').on('click', event => {
            vm.deleteStrayPost($(event.currentTarget).data('id'));
          });
        }
      }
    };
  },
  computed: {
    imageUrl() {
      if (this.imageFile) {
        return URL.createObjectURL(this.imageFile);
      } else if (this.strayPost.image) {
        return this.strayPost.image;
      } else {
        return null;
      }
    },
  },
  mounted() {
    this.getStrayPosts();
    this.loadGoogleMapsAPI().then(() => {
      console.log('Google Maps API loaded successfully');
      // Call your function or perform any operations that require the API here
    }).catch((error) => {
      console.error('Error loading Google Maps API:', error);
    });
  },
  /* computed: {
    google: gmapApi
  }, */
  methods: {
    loadGoogleMapsAPI() {
      const loader = new Loader({
        apiKey: 'AIzaSyAfrTNx21m1BpOFe12uPZsCof8An3TKutk', // Replace with your own API key
        libraries: ['places'],
      });

      return loader.load();
    },

    /* openCreateModal() {
      this.modalMode = 'create';
    },
    openEditModal() {
      this.modalMode = 'edit';
    }, */
    getStrayPosts() {
      axios.get('/api/strayposts')
        .then(response => {
          this.strayPosts = response.data;
        })
        .catch(error => {
          console.error(error);
        });
    },
    resetForm() {
      this.strayPost = {
        title: '',
        description: '',
        location: '',
        image: '',
        position: { lat: '', lng: '' },
      };
      this.imageFile = null;
      this.searchInput = '';
      this.autocompleteResults = [];
      this.$refs.imageInput.value = '';
      this.v$.$reset();
    },
    editStrayPost(id) {
      this.v$.$touch();
      if (this.v$.strayPost.$error) {
        $('#strayPostModal').modal('show');
      }
      else {
        let editFormData = new FormData();
        //console.log(this.strayPost.position.lat);
        //console.log(this.strayPost.position.lng);
        editFormData.append('title', this.strayPost.title);
        editFormData.append('description', this.strayPost.description);
        editFormData.append('location', this.strayPost.location);
        console.log(this.strayPost.position);
        if (this.strayPost.position){
          console.log('has edited pos');
          editFormData.append('latitude', this.strayPost.position.lat);
          editFormData.append('longitude', this.strayPost.position.lng);
        }
        editFormData.append('_method', 'PUT');
        if (this.imageFile) {
          editFormData.append('image', this.imageFile, this.imageFile.name);
        }
        axios.post(`/api/strayposts/${id}`, editFormData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }).then(response => {
          const index = this.strayPosts.findIndex(strayPost => strayPost.id === response.data.strayPost.id);
          this.strayPosts[index] = response.data.strayPost;
          toastr.success(response.data.message);
          this.resetForm();
        })
          .catch(error => {
            console.error(error);
          });
      }
    },
    getStrayPost(id) {
      this.modalMode = 'edit';
      return new Promise((resolve, reject) => {
        axios.get(`/api/strayposts/${id}`)
          .then(response => {
            resolve(response.data);
          })
          .catch(error => {
            console.error(error);
            reject(error);
          });
      });
    },
    deleteStrayPost(id) {
      this.$swal({
        title: 'Confirmation',
        text: 'Are you sure you want to delete this stray post?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
      }).then((result) => {
        if (result.isConfirmed) {
          axios.delete(`/api/strayposts/${id}`)
            .then(response => {
              const index = this.strayPosts.findIndex(strayPost => strayPost.id === id);
              this.strayPosts.splice(index, 1);
              toastr.success(response.data.message);
            })
            .catch(error => {
              console.error(error);
            });
        }
      });
    },
    handleImage(event) {
      this.imageFile = event.target.files[0];
    },
    selectAutocompleteResult(result) {
      this.strayPost.location = result.description;
      this.autocompleteResults = [];
      this.searchLocation();
    },
    onSearchInput() {
      console.log('search input');
      if (this.strayPost.location === '') {
        this.autocompleteResults = [];
        return;
      }
      //if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
      console.log('inside');
      //VueGoogleMaps.loaded.then(() => {
      const service = new google.maps.places.AutocompleteService();
      service.getPlacePredictions({ input: this.strayPost.location }, (predictions, status) => {
        if (status === google.maps.places.PlacesServiceStatus.OK) {
          this.autocompleteResults = predictions;
        } else {
          this.autocompleteResults = [];
        }
      });
      //}
    },
    searchLocation() {
      const geocoder = new google.maps.Geocoder();
      geocoder.geocode({ address: this.strayPost.location }, (results, status) => {
        if (status === 'OK' && results.length > 0) {
          const location = results[0].geometry.location;
          this.strayPost.location = results[0].formatted_address;
          this.strayPost.position = {
            lat: location.lat(),
            lng: location.lng(),
          };
          console.log('stray post location');
          console.log(this.strayPost.location);
          console.log(this.strayPost.position);
        } else {
          console.error('Geocode was not successful for the following reason:', status);
        }
      });
    },
  }
}
</script>
       


  