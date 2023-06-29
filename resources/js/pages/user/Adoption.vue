<template>
  <div class="container mt-4 mb-4">
    <h1 class="text-center mb-5">Adopt Animal</h1>
    <div class="row">
      <div class="col-md-4" v-for="animal in availableAnimals" :key="animal.id">
        <div class="card mb-4 box-shadow">
          <img class="card-img-top" :src="animal.image" alt="Animal image" width="50" height="150"
            style="object-fit:contain">
          <div class="card-body text-center">
            <h4 class="card-title">{{ animal.name }}</h4>
            <p class="card-text">{{ animal.gender == 'M' ? 'Male' : 'Female' }}
              <br>{{ animal.age + ' year old' }}
            </p>
            <!-- <p class="card-text">{{ animal.age +'year old'}}</p> -->
            <div class="d-flex justify-content-center" style="margin-top: -10px;">
              <div class="btn-group">
                <button class="btn btn-primary" style="border-radius: 10px" data-bs-toggle="modal"
                  data-bs-target="#viewAnimalModal" @click="viewAnimal(animal)">View More</button>
                <!-- <router-link :to="{ name: 'AnimalDetails', params: { id: animal.id } }"
                  class="btn btn-sm btn-outline-secondary">View</router-link> -->
              </div>
              <!-- <small class="text-muted">{{ animal.created_at }}</small> -->
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
    <!--modal for view more-->
    <div class="modal fade" id="viewAnimalModal" aria-labelledby="animalModalLabel"
       aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="animalModalLabel">{{ selectedAnimal.name }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <img class="card-img-top" :src="selectedAnimal.image" alt="Animal image" width="100" height="250" style="object-fit:contain">
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-bold">Gender:</label>
                <p>{{ selectedAnimal.gender === 'F' ? 'Female' : 'Male' }}</p>
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold">Age:</label>
                <p>{{ selectedAnimal.age + ' year(s) old'}} </p>
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold">Description:</label>
                <p>{{ selectedAnimal.description }}</p>
              </div>
              <!--
              <div v-if="selectedAnimal.status === 'Available'"> 
                <button type="button" class="btn btn-primary" @click="adoptAnimal">Adopt</button>
               </div>
              <div v-else>
                <p>This animal is not available for adoption</p>
              </div> -->
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#adoptAnimalModal" @click="adoptAnimal(selectedAnimal.id)">Adopt</button>
        </div>
      </div>
    </div>
</div>
</template>
  
<script>

export default {
  data() {
    return {
      availableAnimals: [],
      selectedAnimal: ''
    };
  },
  mounted() {
    this.fetchAvailableAnimals();
  },
  methods: {
    fetchAvailableAnimals() {
      axios.get('/api/available-animals')
        .then(response => {
          this.availableAnimals = response.data;
          console.log(this.availableAnimals);
        })
        .catch(error => {
          console.log(error);
        });
    },
    viewAnimal(animal){
      this.selectedAnimal = animal;
      console.log(this.selectedAnimal);
    },
    adoptAnimal(id){
      this.$swal({
        title: 'Confirmation',
        text: 'Are you sure you want to adopt this animal?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel'
      }).then(function (result) {
          if (result.isConfirmed) {
        console.log(id);
        console.log(this.$store.state.auth.user.id);
        axios.post('/api/adoptionapplications', {
          users_id: this.$store.state.auth.user.id,
          adoption_animals_id: id,
          /* application_date: application_date *//* new Date().toISOString().split('T')[0] */
        })
        .then(response => {
          console.log(response.data);
          toastr.success(response.data.message);
          // show success message and close modal
        })
        .catch(error => {
          console.log(error);
          // show error message
        });
      }});
        // Close the modal
        //$('#adoptAnimalModal').modal('hide');
    }
    /* getAdoptionAnimal(id) {
      axios.get(`/api/adoptionanimals/${id}`)
        .then(response => {
          this.animal = response.data;
        })
        .catch(error => {
          console.error(error);
        });
    } */
  }
};
</script>
