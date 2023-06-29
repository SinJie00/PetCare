<template>
  <div class="container mt-5 mb-5">
    <h1 class="text-center mb-5">{{ title }}</h1>
    <div class="bg-light clearfix">
      <button class="btn btn-primary mb-3 float-right" data-bs-toggle="modal" data-bs-target="#animalModal"
        @click="openCreateModal">Add Adoption Animal</button>
    </div>
    <div class="modal" id="animalModal" refs="animalModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h2 v-if="modalMode === 'create'" class="modal-title">Create Adoption Animal</h2>
            <h2 v-else-if="modalMode === 'edit'" class="modal-title">Edit Adoption Animal</h2>
            <button type="button" class="btn btn-close" data-bs-dismiss="modal" @click="resetForm"
              aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-group">
                <label for="name" class="form-label fw-bold">Name<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" v-model="animal.name" required
                  :class="{ 'is-invalid': v$.animal.name.$error }">
                <div v-if="v$.animal.name.$error" class="invalid-feedback">Name is required.</div>
              </div>
              <div class="form-group mt-2">
                <label for="age" class="form-label fw-bold">Age<span class="text-danger">*</span></label>
                <div class="row">
                  <div class="col-md-6">
                    <input type="number" class="form-control" id="age" min="1" v-model="animal.age" required
                      :class="{ 'is-invalid': v$.animal.age.$error }">
                    <div v-if="v$.animal.age.$error" class="invalid-feedback">Age is required.</div>
                  </div>
                  <div class="col-md-6">
                    <div class="input-group">
                      <select class="form-select" v-model="ageUnit" required :class="{ 'is-invalid': v$.ageUnit.$error }">
                        <!-- <option value="" >-- Select Age Unit --</option> -->
                        <option value="Month">Month</option>
                        <option value="Year">Year</option>
                      </select>
                      <div v-if="v$.ageUnit.$error" class="invalid-feedback">Age unit is required.</div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group mt-2">
                <label for="gender" class="form-label fw-bold">Gender<span class="text-danger">*</span></label>
                <select class="form-control" id="gender" v-model="animal.gender" required
                  :class="{ 'is-invalid': v$.animal.gender.$error }">
                  <option value="">-- Select Gender --</option>
                  <option value="M">Male</option>
                  <option value="F">Female</option>
                </select>
                <div v-if="v$.animal.gender.$error" class="invalid-feedback">Gender is required.</div>
              </div>
              <div class="form-group mt-2">
                <label for="status" class="form-label fw-bold">Status<span class="text-danger">*</span></label>
                <select class="form-control" id="status" v-model="animal.status" required
                  :class="{ 'is-invalid': v$.animal.status.$error }">
                  <option value="">-- Select Status --</option>
                  <option value="Available">Available</option>
                  <option value="Adopted">Adopted</option>
                </select>
                <div v-if="v$.animal.status.$error" class="invalid-feedback">Status is required.</div>
              </div>
              <div class="form-group mt-2">
                <label for="type" class="form-label fw-bold">Type<span class="text-danger">*</span></label>
                <select class="form-control" id="type" v-model="animal.type" required
                  :class="{ 'is-invalid': v$.animal.type.$error }">
                  <option value="">-- Select Type --</option>
                  <option value="Dog">Dog</option>
                  <option value="Cat">Cat</option>
                  <option value="Others">Others</option>
                </select>
                <div v-if="v$.animal.type.$error" class="invalid-feedback">Type is required.</div>
              </div>
              <div class="form-group mt-2">
                <label for="description" class="form-label fw-bold">Description<span class="text-danger">*</span></label>
                <textarea class="form-control" id="description" v-model="animal.description" required
                  :class="{ 'is-invalid': v$.animal.description.$error }"></textarea>
                <div v-if="v$.animal.description.$error" class="invalid-feedback">Description is required.</div>
              </div>
              <div class="form-group mt-2">
                <label for="image" class="form-label fw-bold">Image<span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="image" ref="imageInput" accept="image/*"
                  v-on:change="handleImage" :class="{ 'is-invalid': v$.imageFile.$error && modalMode === 'create' }" />
                <div v-if="v$.imageFile.$error && modalMode === 'create'" class="invalid-feedback">Image is required.
                </div>
                <img v-if="imageUrl" :src="imageUrl" class="mt-2" width="150" height="150" />
              </div>
              <div class="form-group mt-4 row justify-content-end">
                <div class="col-auto mr-2">
                  <button v-if="modalMode === 'create'" data-bs-dismiss="modal" type="submit" class="btn btn-primary"
                    @click.prevent="addAdoptionAnimal">Create</button>
                  <button v-else-if="modalMode === 'edit'" data-bs-dismiss="modal" type="submit" class="btn btn-primary"
                    @click.prevent="editAdoptionAnimal(animal.id)">Update</button>
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
      <data-table class="table table-bordered table-responsive" width="100%" :data="adoptionAnimals" :columns="columns"
        :options="options"></data-table>
    </div>
  </div>
</template>

<script>
import { useVuelidate } from '@vuelidate/core';
import { required } from '@vuelidate/validators';
export default {
  name: 'AdoptionAnimal',
  setup() {
    const v$ = useVuelidate();
    return { v$ };
  },
  validations() {
    return {
      imageFile: { required },
      ageUnit: { required },
      animal: {
        name: { required },
        age: { required },
        gender: { required },
        type: { required },
        description: { required },
        status: { required },
        //image: { required },
      },
    }
  },
  data() {
    const vm = this;
    return {
      title: 'Adoption Animals',
      adoptionAnimals: [],
      modalMode: '',
      imageFile: '',
      ageUnit: 'Month',
      animal: {
        name: '',
        age: '',
        gender: '',
        type: '',
        description: '',
        status: '',
        image: '',
      },
      columns: [
        { title: 'ID', data: 'id' },
        { title: 'Name', data: 'name' },
        {
          title: 'Image', data: 'image',
          render: function (data, type, row) {
            if (data) {
              return '<img src="' + data + '" width="120" height="100"/>';
            }
            else {
              return '';
            }
          }
        },
        {
          title: 'Age', data: 'age',
          render: function (data, type, row) {
            const ageInMonth = data;
            if (ageInMonth === 1) {
              return '1 month old';
            } else if (ageInMonth > 12) {
              const ageInYear = Math.floor(ageInMonth / 12);
              if (ageInYear === 1) {
                return '1 year old';
              } else {
                return ageInYear + ' years old';
              }
            } else {
              return ageInMonth + ' months old';
            }
          }
        },
        {
          title: 'Gender', data: 'gender',
          render: function (data, type, row) {
            return data === 'F' ? 'Female' : 'Male';
          }
        },
        { title: 'Type', data: 'type' },
        { title: 'Description', data: 'description' },
        { title: 'Status', data: 'status' },
        {
          title: 'Actions',
          render: function (data, type, row) {
            // return HTML for action buttons @click="editAdoptionAnimal(${row.id})@click="deleteAdoptionAnimal(${row.id})
            return `
              <button class="btn btn-sm btn-success edit-btn" data-id="${row.id}">Edit</button> 
              <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">Delete</button>
            `;
          },
        }
      ],
      options: {
        drawCallback() {
          // Add click event listener to buttons
          $('.edit-btn').on('click', event => {
            const animalId = $(event.currentTarget).data('id');
            /* vm.getAdoptionAnimal(animalId).then(() => {
            $('#animalModal').modal('show');
          }); */
            getAnimalAndShowModal(animalId);
            /* vm.editAdoptionAnimal($(event.currentTarget).data('id')); */
          });
          async function getAnimalAndShowModal(animalId) {
            const animalData = await vm.getAdoptionAnimal(animalId);
            console.log('animal data');
            console.log(animalData.animal);
            console.log('vm animal');
            console.log(vm.animal);
            vm.animal = animalData.animal;
            console.log(vm.animal);
            $('#animalModal').modal('show');
          }
          $('.delete-btn').on('click', event => {
            vm.deleteAdoptionAnimal($(event.currentTarget).data('id'));
          });
        }
      }
    };
  },
  computed: {
    imageUrl() {
      if (this.imageFile) {
        return URL.createObjectURL(this.imageFile);
      } else if (this.animal.image) {
        console.log('yes had image');
        return this.animal.image;
      } else {
        return null;
      }
    },
  },
  mounted() {
    this.getAdoptionAnimals();
    /* if (this.animal && !this.imageFile) {
    this.$refs.imageInput.value = ''; // set the value of the file input to an empty string
  } */
  },
  methods: {
    openCreateModal() {
      /* this.showModal = true; */
      this.modalMode = 'create';
    },
    openEditModal() {
      /* this.showModal = true; */
      this.modalMode = 'edit';
    },
    getAdoptionAnimals() {
      axios.get('/api/adoptionanimals')
        .then(response => {
          this.adoptionAnimals = response.data;
        })
        .catch(error => {
          console.error(error);
        });
    },
    resetForm() {
      /* this.showCreateModal = false; */
      //const createFormModal = new bootstrap.Modal(this.$refs.createFormModal);
      /* $(this.$refs.createFormModal).on("hidden.bs.modal"); */
      /* $('#createFormModal').hide(); */
      /* this.formTitle = 'Add Animal'; */
      /* this.submitButton = 'Save'; */
      this.animal = {
        name: '',
        age: '',
        gender: '',
        type: '',
        description: '',
        status: '',
        image: ''
      };
      this.imageFile = null;
      this.$refs.imageInput.value = '';
      // Reset all form validation errors
      this.v$.$reset();

    },
    addAdoptionAnimal() {
      this.v$.$touch();
      if (this.v$.$error) {
        $('#animalModal').modal('show');
      }
      else {
        if (this.ageUnit == "Year") {
          this.animal.age *= 12;
        }
        console.log('save animal');
        console.log(this.animal);
        let formData = new FormData();
        formData.append('name', this.animal.name);
        formData.append('age', this.animal.age);
        formData.append('gender', this.animal.gender);
        formData.append('type', this.animal.type);
        formData.append('status', this.animal.status);
        formData.append('description', this.animal.description);
        formData.append('image', this.imageFile);
        console.log(this.imageFile);
        /* for (let [key, value] of formData.entries()) {
          console.log(key, value);
        } */
        axios.post('/api/adoptionanimals', formData)
          .then(response => {
            console.log('api ok');
            console.log(response.data);
            this.adoptionAnimals.push(response.data);
            this.resetForm();
            toastr.success('Adoption animal created successfully');
          })
          .catch(error => {
            console.error(error);
            toastr.error('Adoption animal created unsuccessfully');
          });
      }
    },
    editAdoptionAnimal(id) {
      this.v$.$touch();
      console.log(this.v$.animal.$error);
      if (this.v$.animal.$error) {
        $('#animalModal').modal('show');
      }
      else {
        if (this.ageUnit == "Year") {
          this.animal.age *= 12;
        }
        console.log('edit');
        console.log(id);
        console.log(this.imageFile);
        let editFormData = new FormData();
        editFormData.append('name', this.animal.name);
        editFormData.append('age', this.animal.age);
        editFormData.append('gender', this.animal.gender);
        editFormData.append('type', this.animal.type);
        editFormData.append('status', this.animal.status);
        editFormData.append('description', this.animal.description);
        editFormData.append('_method', 'PUT');
        if (this.imageFile) {
          editFormData.append('image', this.imageFile, this.imageFile.name);
          console.log(this.imageFile);
        }
        for (let [key, value] of editFormData.entries()) {
          console.log(key, value);
        }
        /* const editFormData = new FormData();
        editFormData.append('animal', this.animal); */
        //formData.append('image', this.image);
        axios.post(`/api/adoptionanimals/${id}`, editFormData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }).then(response => {
          const index = this.adoptionAnimals.findIndex(animal => animal.id === response.data.animal.id);
          /* this.vm.$set(this.adoptionAnimals, index, response.data); */
          /* Vue.set(this.adoptionAnimals, index, response.data); */
          console.log(index);
          console.log(response.data.message);
          console.log(response.data.animal);
          console.log(response.data.animal.image);
          this.adoptionAnimals[index] = response.data.animal;
          console.log('updated data');
          console.log(this.adoptionAnimals[index]);
          toastr.success(response.data.message);
          this.resetForm();
        })
          .catch(error => {
            console.error(error);
          });
      }
    },
    getAdoptionAnimal(id) {
      console.log('1');
      this.modalMode = 'edit';
      console.log(id);
      /* this.formTitle = 'Edit Animal';
      this.submitButton = 'Update'; */
      /* console.log(id); */
      return new Promise((resolve, reject) => {
        axios.get(`/api/adoptionanimals/${id}`)
          .then(response => {
            console.log(this);
            /* this.animal = response.data; */
            /* this.$set(this.animal, response.data); */
            console.log('in function');
            console.log(this.animal);
            resolve(response.data);
          })
          .catch(error => {
            console.error(error);
            reject(error);
          });
      });
    },
    deleteAdoptionAnimal(id) {
      this.$swal({
        title: 'Confirmation',
        text: 'Are you sure you want to delete this animal?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
      }).then((result) => {
        if (result.isConfirmed) {
          axios.delete(`/api/adoptionanimals/${id}`)
            .then((response) => {
              const index = this.adoptionAnimals.findIndex(animal => animal.id === id);
              toastr.success(response.data.message);
              this.adoptionAnimals.splice(index, 1);
            })
            .catch(error => {
              console.error(error);
            });
        }
      });
    },
    handleImage(event) {
      // Get the selected file
      this.imageFile = event.target.files[0];
      /* reader.readAsDataURL(this.imageFile);
  
        const reader = new FileReader();
        reader.onload = () => {
          this.imageUrl = reader.result;
        };
        reader.readAsDataURL(file); */
      /*  } else if (this.animal && this.animal.image) {
         this.imageUrl = window.env.MIX_PUBLIC_PATH + '/' + animal.image;
       } */
      // Create a FormData object to send the file to the server
      /* const formData = new FormData();
      formData.append('image', file);
      formData.append('folder', 'adoption_animals'); //

      // Send the file to the server using axios
      axios.post('/api/upload-image', formData)
        .then(response => {
          // Handle the response from the server
          console.log(response.data);
        }); */
    }
  }
}
</script>
       


  