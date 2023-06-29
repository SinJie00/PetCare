<template>
    <div class="container mt-5">
        <div class="row justify-content-center align-items-center">
            <div class="mt-5 col-md-6">
                <div class="mt-5 card">
                    <div class="card-body my-auto">
                        <h2 class="text-center mb-5">Reset Password</h2>
                        <form @submit.prevent="resetPassword" novalidate>
                            <div class="form-group mt-4">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" required v-model="password" type="password" class="form-control"
                                    :class="{ 'is-invalid': errors && errors.password }">
                                    <!-- @blur="v$.password.$touch">
                                <div v-for="(error, index) of v$.password.$errors" :key="index" class="invalid-feedback">
                                    <div v-if="error.$validator == 'required'">Password is required.</div>
                                    <div v-else-if="error.$validator == 'minLength'">The password must be at least 8 characters.</div>
                                    <div v-else-if="error.$validator == 'regex'">The password must contain at least one uppercase letter, one number, and one special character.</div>
                                </div> -->
                                <!-- <span class="text-danger" v-if="passwordError">{{ passwordError }}</span> -->
                                <div v-if="errors && errors.password" class="invalid-feedback">{{ errors.password[0] }}</div>
                            </div>
                            <div class="form-group mt-4">
                                <label for="confirm_password">Confirm Password</label>
                                <input id="confirm_password" required v-model="confirm_password" type="password"
                                    class="form-control" :class="{ 'is-invalid': errors && errors.confirm_password }">
                                    <!-- @blur="v$.confirm_password.$touch">
                                <div v-for="(error, index) of v$.confirm_password.$errors" :key="index" class="invalid-feedback">
                                    <div v-if="error.$validator == 'required'">Confirmation Password is required.</div>
                                    <div v-else-if="error.$validator == 'sameas'">The confirmation password does not match.</div>
                                </div> -->
                                <div v-if="errors && errors.confirm_password" class="invalid-feedback">{{ errors.confirm_password[0] }}</div>
                                <!-- <span class="text-danger" v-if="confirmPasswordError">{{ confirmPasswordError }}</span> -->
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-lg btn-primary btn-block w-75 mx-auto">Reset
                                    Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script>
/* import { useVuelidate } from '@vuelidate/core';
import { required, minLength, regex, sameAs } from '@vuelidate/validators'; */
export default {
    /* setup() {
        const v$ = useVuelidate();
        return { v$ };
    },
    validations() {
        return {
            password: {
                required,
                minLength: minLength(8),
                regex: regex(/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/)
            },
            confirm_password: {
                required,
                sameAsPassword: sameAs('password')
            }
        }
    }, */
    data() {
        return {
            password: '',
            confirm_password: '',
            errors: {}
        };
    },
    /* mounted() {
    // Extract the token and email parameters from the URL
    this.token = this.$route.query.token;
    this.email = this.$route.query.email;
    }, */
    methods: {
        resetPassword() {
            // Perform validation on the new password and password confirmation
            /* if (this.password !== this.confirm_password) {
                alert('Password and confirm password do not match.');
                return;
            } */
            this.errors = {};
            // Send an API request to update the user's password
            axios.post('/api/reset-password', {
                token: this.$route.query.token,
                email: this.$route.query.email,
                password: this.password,
                confirm_password: this.confirm_password,
            }).then(() => {
                    //alert('Password has been reset successfully.');
                    this.$swal({
                        title: 'Confirmation',
                        text: 'Password has been reset successfully.',
                        icon: 'success',
                    });
                    // Redirect the user to the login page or any other appropriate page
                    this.$router.push({ name: 'Login' }); // Redirect to Login page
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        this.errors  = error.response.data.errors;
                        console.log(this.errors);
                    } 
                    else{
                    this.$swal({
                        title: 'Alert Message',
                        text: 'Failed to reset password. Please try again later.',
                        icon: 'error',
                    });
                    }
                    //alert('Failed to reset password. Please try again.');
                });
        },
    },
};
</script>
  