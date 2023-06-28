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
                <label for="title">Title*</label>
                <input type="text" class="form-control" id="title" v-model="strayPost.title" required>
              </div>
              <div class="form-group">
                <label for="description">Description*</label>
                <input type="text" class="form-control" id="description" min="1" v-model="strayPost.description" required>
              </div>
              <div class="form-group">
                <label for="location">Location*</label>
                <textarea class="form-control" id="location" v-model="strayPost.location" required></textarea>
              </div>
              <div class="form-group">
                <label for="image">Image*</label>
                <input type="file" class="form-control" id="image" ref="imageInput" accept="image/*"
                  v-on:change="handleImage" />
                <img v-if="imageUrl" :src="imageUrl" class="mt-2" style="max-width: 200px;" />
              </div>
              <div class="form-group mt-4 row justify-content-end">
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
export default {
  name: 'StrayPost',
  data() {
    const vm = this;
    return {
      strayPosts: [],
      //modalMode: '',
      imageFile: '',
      strayPost: {
        title: '',
        description: '',
        location: '',
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

          axios.get(`https://petcare-ec207baddaf0.herokuapp.com/api/users/${userId}`)
              .then(response => {
                const userName = response.data.user.name || 'N/A';
                const email = response.data.user.email || 'N/A';
                const phone = response.data.user.phone || 'N/A';
                const address = response.data.user.address || 'N/A';
                const nameColumn = table.cell(row, 5).node(); // Get the cell element
                const emailColumn = table.cell(row, 7).node();
                const phoneColumn = table.cell(row, 8).node();
                const addressColumn = table.cell(row, 9).node();

                $(nameColumn).text(userName); // Set the user name in the cell
                $(emailColumn).text(email);
                $(phoneColumn).text(phone);
                $(addressColumn).text(address);
              })
              .catch(error => {
                console.log('Error fetching user details:', error);
                const nameColumn = table.cell(row, 5).node();
                const emailColumn = table.cell(row, 7).node();
                const phoneColumn = table.cell(row, 8).node();
                const addressColumn = table.cell(row, 9).node();
                $(nameColumn).text('N/A'); // Set N/A if an error occurred
                $(emailColumn).text('N/A');
                $(phoneColumn).text('N/A');
                $(addressColumn).text('N/A');
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
  },
  methods: {
    /* openCreateModal() {
      this.modalMode = 'create';
    },
    openEditModal() {
      this.modalMode = 'edit';
    }, */
    getStrayPosts() {
      axios.get('https://petcare-ec207baddaf0.herokuapp.com/api/strayposts')
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
        image: ''
      };
      this.imageFile = null;
      this.$refs.imageInput.value = '';
    },
    editStrayPost(id) {
      let editFormData = new FormData();
      editFormData.append('title', this.strayPost.title);
      editFormData.append('description', this.strayPost.description);
      editFormData.append('location', this.strayPost.location);
      editFormData.append('_method', 'PUT');
      if (this.imageFile) {
        editFormData.append('image', this.imageFile, this.imageFile.name);
      }
      axios.post(`https://petcare-ec207baddaf0.herokuapp.com/api/strayposts/${id}`, editFormData , {
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
    },
    getStrayPost(id) {
      this.modalMode = 'edit';
      return new Promise((resolve, reject) => {
        axios.get(`https://petcare-ec207baddaf0.herokuapp.com/api/strayposts/${id}`)
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
      if (confirm('Are you sure you want to delete this stray post?')) {
        axios.delete(`https://petcare-ec207baddaf0.herokuapp.com/api/strayposts/${id}`)
          .then(response => {
            const index = this.strayPosts.findIndex(strayPost => strayPost.id === id);
            this.strayPosts.splice(index, 1);
            toastr.success(response.data.message);
          })
          .catch(error => {
            console.error(error);
          });
      }
    },
    handleImage(event) {
      this.imageFile = event.target.files[0];
    }
  }
}
</script>
       


  