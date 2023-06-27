<template>
  <div class="container">
    <div class="row justify-content-center mt-5">
      <div class="col-md-6 mt-5">
        <div class="card mt-5">
          <div class="card-body">
            <h2 class="text-center mb-4">Forgot Password</h2>
            <form @submit.prevent="submitForm">
              <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" v-model="email" required>
              </div>
              <div class="text-center">
                <button type="submit" class="btn btn-primary">Reset Password</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
  
<script>
export default {
  data() {
    return {
      email: "",
    };
  },
  methods: {
    submitForm() {
      // Make a POST request to your Laravel API endpoint to initiate the password reset process
      axios.post("http://localhost:81/api/forgot-password", {
        email: this.email,
      })
        .then(() => {
          alert("Password reset link sent to your email.");
        })
        .catch((error) => {
          console.error(error);
          alert("Failed to send password reset link.");
        });
    },
  },
};
</script>
  