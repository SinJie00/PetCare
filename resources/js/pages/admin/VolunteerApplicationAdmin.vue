<template>
  <div class="container mt-5 mb-5">
    <h1 class="text-center mb-5">Volunteer Application</h1>
    <div>
      <data-table class="table table-bordered table-responsive" width="100%" :data="volunteerApplications"
        :columns="columns" :options="options"></data-table>
    </div>
  </div>
</template>
  
<script>
export default {
  data() {
    const vm = this;
    return {
      volunteerApplications: [],
      error: 'An unknown error occurred. Please try again later',
      columns: [
        { title: 'ID', data: 'id' },
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
        { title: 'Application Datetime', data: 'application_date' },
        { title: 'Application Approval Datetime', data: 'approval_date' },
        { title: 'Application Status', data: 'status' },
        {
          title: 'Actions',
          render: function (data, type, row) {
            if (row.status == "Pending") {
              return `
              <button class="btn btn-sm btn-success approve-btn" data-id="${row.id}">Approve</button> 
              <button class="btn btn-sm btn-secondary reject-btn" data-id="${row.id}">Reject</button> 
              <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">Delete</button>
            `;
            }
            else if (row.status == "Rejected") {
              return `
              <button class="btn btn-sm btn-success approve-btn" data-id="${row.id}">Approve</button> 
              <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">Delete</button>
            `;
            } else {
              return `
              <button class="btn btn-sm btn-secondary reject-btn" data-id="${row.id}">Reject</button> 
              <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">Delete</button>
            `;
            }
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

            axios.get(`https://petcare-ec207baddaf0.herokuapp.com/api/users/${userId}`)
              .then(response => {
                const userName = response.data.user.name || 'N/A';
                const email = response.data.user.email || 'N/A';
                const phone = response.data.user.phone || 'N/A';
                const address = response.data.user.address || 'N/A';
                const nameColumn = table.cell(row, 1).node(); // Get the cell element
                const emailColumn = table.cell(row, 3).node();
                const phoneColumn = table.cell(row, 4).node();
                const addressColumn = table.cell(row, 5).node();

                $(nameColumn).text(userName); // Set the user name in the cell
                $(emailColumn).text(email);
                $(phoneColumn).text(phone);
                $(addressColumn).text(address);
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
          });

          $('.approve-btn').on('click', event => {
            vm.approveVolunteerApplication($(event.currentTarget).data('id'));
          });
          $('.reject-btn').on('click', event => {
            vm.rejectVolunteerApplication($(event.currentTarget).data('id'));
          });
          $('.delete-btn').on('click', event => {
            vm.deleteVolunteerApplication($(event.currentTarget).data('id'));
          });
        }
      }
    };
  },
  mounted() {
    this.getVolunteerApplications();
  },
  methods: {
    getVolunteerApplications() {
      axios.get('https://petcare-ec207baddaf0.herokuapp.com/api/volunteerapplications')
        .then(response => {
          this.volunteerApplications = response.data;
        })
        .catch(error => {
          console.error(error);
          toastr.error(this.error);
        });
    },
    approveVolunteerApplication(id) {
      this.$swal({
        title: 'Confirmation',
        text: 'Are you sure you want to approve this application?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel',
      }).then((result) => {
        if (result.isConfirmed) {
          axios.put(`https://petcare-ec207baddaf0.herokuapp.com/api/approve-volunteerapplication/${id}`)
            .then(response => {
              const index = this.volunteerApplications.findIndex(application => application.id == response.data.application.id);
              this.volunteerApplications[index] = response.data.application;
              toastr.success(response.data.message);
            })
            .catch(error => {
              console.error(error);
              toastr.error(this.error);
            });
        }
      });
    },
    rejectVolunteerApplication(id) {
      this.$swal({
        title: 'Confirmation',
        text: 'Are you sure you want to reject this application?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel',
      }).then((result) => {
        if (result.isConfirmed) {
          axios.put(`https://petcare-ec207baddaf0.herokuapp.com/api/reject-volunteerapplication/${id}`)
            .then(response => {
              const index = this.volunteerApplications.findIndex(application => application.id == response.data.application.id);
              this.volunteerApplications[index] = response.data.application;
              toastr.success(response.data.message);
            })
            .catch(error => {
              console.error(error);
              toastr.error(this.error);
            });
        }
      });
    },
    deleteVolunteerApplication(id) {
      this.$swal({
        title: 'Confirmation',
        text: 'Are you sure you want to delete this application?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
      }).then((result) => {
        if (result.isConfirmed) {
          axios.delete(`https://petcare-ec207baddaf0.herokuapp.com/api/volunteerapplications/${id}`)
            .then(response => {
              const index = this.volunteerApplications.findIndex(application => application.id === id);
              this.volunteerApplications.splice(index, 1);
              toastr.success(response.data.message);
            })
            .catch(error => {
              console.error(error);
              toastr.error(this.error);
            });
        }
      });
    },
  },
};
</script>
  