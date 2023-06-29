<template>
  <div class="container">
    <div class="row justify-content-center mt-5">
      <div class="col-md-6 mt-5">
        <div class="card mt-5">
          <div class="card-body">
            <h2 class="text-center mb-4">Forgot Password</h2>
            <form @submit.prevent="submitForm" novalidate>
              <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" v-model="email" required
                  :class="{ 'is-invalid': v$.email.$error }" @blur="v$.email.$touch">
                <div v-for="(error, index) of v$.email.$errors" :key="index" class="invalid-feedback">
                  <div v-if="error.$validator == 'required'">Email is required.</div>
                  <div v-else-if="error.$validator == 'email'">A valid email is required.</div>
                </div>
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
import { useVuelidate } from '@vuelidate/core';
import { required, email } from '@vuelidate/validators';
export default {
  setup() {
    const v$ = useVuelidate();
    return { v$ };
  },
  validations() {
    return {
      email: { required, email },
    }
  },
  data() {
    return {
      email: "",
    };
  },
  methods: {
    submitForm() {
      // Make a POST request to your Laravel API endpoint to initiate the password reset process
      console.log('submit form');
      console.log(this.v$.email.$error);
      if (!this.v$.email.$error) {
        console.log('hi');
        axios.post("/api/forgot-password", {
          email: this.email,
        })
          .then(() => {
            this.$swal({
              title: 'Confirmation',
              text: 'Password reset link sent to your email.',
              icon: 'success',
            });
          })
          .catch((error) => {
            console.error(error);
            this.$swal({
              title: 'Alert Message',
              text: 'Failed to send password reset link. Please try again.',
              icon: 'error',
            });
            //alert("Failed to send password reset link.");
          });
      }
    },
  },
};
</script>
  