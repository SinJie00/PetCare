<template>
    <div class="container mt-5">
        <div class="row justify-content-center align-items-center">
            <div class="mt-5 col-md-6">
                <div class="mt-5 card">
                    <div class="card-body my-auto">
                        <h2 class="text-center mb-5">Login</h2>
                        <form @submit.prevent="handleSubmit" novalidate>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input v-model="email" type="email" class="form-control" name="email" required autofocus>
                                <!-- <input id="email" required v-model="user.email" type="email" class="form-control"> -->
                                <!-- <span class="text-danger" v-show="errors && errors.email">{{ errors.email[0] }}</span>  -->
                                <span class="text-danger" v-if="emailError">{{ emailError }}</span>
                            </div>
                            <div class="form-group mt-4">
                                <label for="password">Password</label>
                                <input id="password" required v-model="password" type="password" class="form-control">
                                <span class="text-danger" v-if="passwordError">{{ passwordError }}</span>
                                <!-- <span class="text-danger" v-show="errors && errors.password">{{ errors.password[0] }}</span> -->
                            </div>
                            <div class="text-center mt-4">
                                <button class="btn btn-lg btn-primary btn-block">Login</button>
                            </div>
                        </form>
                        <div v-if="loginFailed" class="alert alert-danger text-center mt-3">{{ loginFailed }}</div>
                        <!-- <span class="text-danger" v-if="loginFailed">{{ loginFailed }}</span> -->
                        <div class="row mt-4">
                            <small class="text-muted text-center">
                                Don't have any account yet?
                                <router-link to="/register">Register</router-link>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Login',
    data() {
        return {
            /* user: { */
                email: '',
                password: '',
                errors: { email: '', password: '' }
            /* } */
        }
    },
    computed: {
        emailError() {
        return this.errors.email ? this.errors.email[0] : '';
        },
        passwordError() {
        return this.errors.password ? this.errors.password[0] : '';
        },
        loginFailed(){
            return this.errors.loginFailed;
        }
    },
    methods: {
       /*  ...mapActions('auth', ['login']), */
        async handleSubmit() {
            // Reset the errors object
            this.errors = {};
            try {
                console.log('1');
                console.log(this.email);
                console.log(this.password);
                /* await this.login({ email: this.email, password: this.password }); */
                await this.$store.dispatch('auth/login',{ email: this.email, password: this.password });
                console.log('2');
                toastr.success('Login successfully!');
                this.$router.push({ name: 'Home' }); // Redirect to Home page
            } catch (error) {
                console.log('error occur here');
                /* toastr.error('Login failed!'); */
                if (error.response && error.response.status === 422) {
                    this.errors  = error.response.data.errors;
                    // Validation error, display the error messages for email and password
                    /* this.errors.email = error.response.data.errors.email ? error.response.data.errors.email[0] : '';
                    this.errors.password = error.response.data.errors.password ? error.response.data.errors.password[0] : ''; */
                } else if (error.response && error.response.status === 401) {
                    // Login failed error, display the error message returned by the server
                    this.errors.loginFailed = error.response.data.message;
                    /* console.log('1');
                    console.log(this.errors.loginFailed); */
                } else {
                    this.errors.loginFailed = 'An error occurred while logging in. Please try again later.';
                }
            }
        }
    },
}
</script>
