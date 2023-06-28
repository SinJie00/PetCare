<template>
  <!--    <div>
      <h1>hello</h1>
       <button class="btn btn-danger" @click="showDeleteConfirmation(selectedItem)">Delete item</button>
    <confirmation-dialog :visible="true" title="Delete item?" message="Are you sure you want to delete this item?" @confirm="deleteItem" @cancel="hideConfirmation" /> 
    </div> -->
  <div class="container mt-5 mb-5">
    <h1 class="text-center mb-5">Adoption Application</h1>
    <div>
      <data-table class="table table-bordered table-responsive" width="100%" :data="adoptionApplications"
        :columns="columns" :options="options"></data-table>
    </div>
  </div>
</template>
  
<script>
export default {
  data() {
    /* showConfirmation: true,
    selectedItem: null,
    items: [], */
    const vm = this;
    return {
      /* showModal: false, */
      adoptionApplications: [],
      error: 'An unknown error occurred. Please try again later',
      columns: [
        { title: 'ID', data: 'id' },
        /*{ title: 'Animal ID', data: 'adoption_animals_id' },*/
        {
          title: 'Animal Name',
          render: function () {
            // Return an empty string for immediate rendering
            return '';
          }
        },
        { title: 'Animal ID', data: 'adoption_animals_id' },
        {
          title: 'Animal Image',
          render: function () {
            return '';
          }
        },
        {
          title: 'Animal Type',
          render: function () {
            return '';
          }
        },
        {
          title: 'Animal Age',
          render: function () {
            return '';
          }
        },
        {
          title: 'Applicant Name',
          render: function () {
            // Return an empty string for immediate rendering
            return '';
          }
        },
        { title: 'Applicant ID', data: 'users_id' },
        {
          title: 'Applicant Email',
          render: function () {
            return '';
          }
        },
        {
          title: 'Applicant Phone',
          render: function () {
            return '';
          }
        },
        {
          title: 'Applicant Address',
          render: function () {
            return '';
          }
        },
        /* {
          title: 'Applicant Name',
          data: 'users_name',
        }, */
        { title: 'Application Datetime', data: 'application_date' },
        { title: 'Application Status', data: 'status' },
        {
          title: 'Actions',
          render: function (data, type, row) {
            // return HTML for action buttons @click="editAdoptionAnimal(${row.id})@click="deleteAdoptionAnimal(${row.id})
            if(row.status=="Pending"){
            return `
              <button class="btn btn-sm btn-success approve-btn" data-id="${row.id}">Approve</button> 
              <button class="btn btn-sm btn-secondary reject-btn" data-id="${row.id}">Reject</button> 
              <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">Delete</button>
            `;
            }
            else if(row.status == "Rejected"){
              return `
              <button class="btn btn-sm btn-success approve-btn" data-id="${row.id}">Approve</button> 
              <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">Delete</button>
            `;
            }else{
              return `
              <button class="btn btn-sm btn-secondary reject-btn" data-id="${row.id}">Reject</button> 
              <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">Delete</button>
            `;
            }
          }
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
            const animalId = rowData.adoption_animals_id;

            axios.get(`https://petcare-ec207baddaf0.herokuapp.com/animals/${animalId}`)
              .then(response => {
                const animalName = response.data.animal.name || 'N/A';
                const animalImage = response.data.animal.image || 'N/A';
                const animalType = response.data.animal.type || 'N/A';
                const animalAge = response.data.animal.age || 'N/A';
                const animalNameColumn = table.cell(row, 1).node(); // Get the cell element
                const animalImageColumn = table.cell(row, 3).node();
                const animalPhoneColumn = table.cell(row, 4).node();
                const animalAgeColumn = table.cell(row, 5).node();

                $(animalNameColumn).text(animalName); // Set the user name in the cell
                $(animalImageColumn).html(`<img src="${animalImage}" alt="Animal Image" width=100 height=100>`);
                $(animalPhoneColumn).text(animalType);
                $(animalAgeColumn).text(animalAge);
              })
              .catch(error => {
                console.log('Error fetching user details:', error);
                const nameColumn = table.cell(row, 1).node();
                const emailColumn = table.cell(row, 3).node();
                const phoneColumn = table.cell(row, 4).node();
                const addressColumn = table.cell(row, 5).node();
                $(nameColumn).text('N/A'); // Set N/A if an error occurred
                $(emailColumn).text('N/A');
                $(phoneColumn).text('N/A');
                $(addressColumn).text('N/A');
              });

            axios.get(`https://petcare-ec207baddaf0.herokuapp.com/api/users/${userId}`)
              .then(response => {
                const userName = response.data.user.name || 'N/A';
                const email = response.data.user.email || 'N/A';
                const phone = response.data.user.phone || 'N/A';
                const address = response.data.user.address || 'N/A';
                const nameColumn = table.cell(row, 6).node(); // Get the cell element
                const emailColumn = table.cell(row, 8).node();
                const phoneColumn = table.cell(row, 9).node();
                const addressColumn = table.cell(row, 10).node();

                $(nameColumn).text(userName); // Set the user name in the cell
                $(emailColumn).text(email);
                $(phoneColumn).text(phone);
                $(addressColumn).text(address);
              })
              .catch(error => {
                console.log('Error fetching user details:', error);
                const nameColumn = table.cell(row, 6).node();
                const emailColumn = table.cell(row, 8).node();
                const phoneColumn = table.cell(row, 9).node();
                const addressColumn = table.cell(row, 10).node();
                $(nameColumn).text('N/A'); // Set N/A if an error occurred
                $(emailColumn).text('N/A');
                $(phoneColumn).text('N/A');
                $(addressColumn).text('N/A');
              });
          });

          $('.approve-btn').on('click', event => {
            vm.approveAdoptionApplication($(event.currentTarget).data('id'));
          });
          $('.reject-btn').on('click', event => {
            vm.rejectAdoptionApplication($(event.currentTarget).data('id'));
          });
          $('.delete-btn').on('click', event => {
            vm.deleteAdoptionApplication($(event.currentTarget).data('id'));
          });
        }
      }
    };
  },
  mounted() {
    this.getAdoptionApplications();
  },
  methods: {
    getAdoptionApplications() {
      axios.get('https://petcare-ec207baddaf0.herokuapp.com/api/adoptionapplications')
        .then(response => {
          this.adoptionApplications = response.data;
        })
        .catch(error => {
          console.error(error);
        });
    },
    approveAdoptionApplication(id) {
      this.$swal({
        title: 'Confirmation',
        text: 'Are you sure you want to approve this application?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel',
      }).then((result) => {
        if (result.isConfirmed) {      
          axios.put(`https://petcare-ec207baddaf0.herokuapp.com/api/approve-adoptionapplication/${id}`)
        .then(response => {
          const index = this.adoptionApplications.findIndex(application => application.id == response.data.application.id);
          this.adoptionApplications[index] = response.data.application;
          toastr.success(response.data.message);
        })
        .catch(error => {
          console.error(error);
          toastr.error(this.error);
        });
      }});
    },
    rejectAdoptionApplication(id) {
      this.$swal({
        title: 'Confirmation',
        text: 'Are you sure you want to reject this application?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel',
      }).then((result) => {
        if (result.isConfirmed) {
      axios.put(`https://petcare-ec207baddaf0.herokuapp.com/api/reject-adoptionapplication/${id}`)
        .then(response => {
          const index = this.adoptionApplications.findIndex(application => application.id == response.data.application.id);
          this.adoptionApplications[index] = response.data.application;
          toastr.success(response.data.message);
        })
        .catch(error => {
          console.error(error);
          toastr.error(this.error);
        });
      }});
    },
    deleteAdoptionApplication(id) {
      this.$swal({
        title: 'Confirmation',
        text: 'Are you sure you want to delete this application?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
      }).then((result) => {
        if (result.isConfirmed) {      
        axios.delete(`https://petcare-ec207baddaf0.herokuapp.com/api/adoptionapplications/${id}`)
          .then(response => {
            const index = this.adoptionApplications.findIndex(application => application.id === id);
            this.adoptionApplications.splice(index, 1);
            toastr.success(response.data.message);
          })
          .catch(error => {
            console.error(error);
            toastr.error(this.error);
          });
      }});
    },
  },
};
</script>
  