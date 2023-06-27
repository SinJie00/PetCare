<template>
    <div class="container mt-5">
        <div class="row justify-content-center align-items-center">
            <div class="mt-5 col-md-6">
                <div class="mt-5 card">
                    <div class="card-body my-auto">
                        <h2 class="text-center mb-5">Reset Password</h2>
                        <form @submit.prevent="resetPassword" novalidate>
                            <div class="form-group mt-4">
                                <label for="password">Password</label>
                                <input id="password" required v-model="password" type="password" class="form-control">
                                <!-- <span class="text-danger" v-if="passwordError">{{ passwordError }}</span> -->
                            </div>
                            <div class="form-group mt-4">
                                <label for="confirm_password">Confirm Password</label>
                                <input id="confirm_password" required v-model="confirm_password" type="password" class="form-control">
                                <!-- <span class="text-danger" v-if="confirmPasswordError">{{ confirmPasswordError }}</span> -->
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-lg btn-primary btn-block w-75 mx-auto">Reset Password</button>
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
            password: '',
            confirm_password: '',
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

            // Send an API request to update the user's password
            axios.post('https://petcare-ec207baddaf0.herokuapp.com/api/reset-password', {
                token: this.$route.query.token,
                email: this.$route.query.email,
                password: this.password,
                confirm_password: this.confirm_password,
            })
                .then(() => {
                    alert('Password has been reset successfully.');
                    // Redirect the user to the login page or any other appropriate page
                    this.$router.push({ name: 'Login' }); // Redirect to Login page
                })
                .catch(error => {
                    console.error(error);
                    alert('Failed to reset password. Please try again.');
                });
        },
    },
};
</script>
  