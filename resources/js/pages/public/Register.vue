<template>
    <div class="container mt-5">
        <div class="row justify-content-center align-items-center">
            <div class="mt-4 col-lg-6 col-md-8 col-sm-10">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center mt-3 mb-5">Register</h2>
                        <form @submit.prevent="handleRegister" novalidate>
                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label text-end">Name</label>
                                <div class="col-md-8">
                                    <input id="name" type="text" class="form-control" v-model="user.name" required autofocus autocomplete="off">
                                    <div v-if="user.errors && user.errors.name" class="text-danger">{{ user.errors.name[0] }}</div>
                                </div>
                            </div>
                            <div class="form-group row mt-2">
                                <label for="gender" class="col-sm-3 col-form-label text-end">Gender</label>
                                <div class="col-md-8 d-flex align-items-center">
                                    <label for="male" class="mr-3 mr-md-5" style="margin-right: 20px;">
                                        <input type="radio" id="male" value="M" v-model="user.gender">Male
                                    </label>
                                    <label for="female">
                                        <input type="radio" id="female" value="F" v-model="user.gender">Female
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row mt-2">
                                <label for="email" class="col-sm-3 col-form-label text-end">E-Mail</label>
                                <div class="col-md-8">
                                    <input id="email" type="email" class="form-control" v-model="user.email" required autofocus autocomplete="off">
                                    <div v-if="user.errors && user.errors.email" class="text-danger">{{ user.errors.email[0] }}</div>
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <label for="phone" class="col-sm-3 col-form-label text-end">Phone</label>
                                <div class="col-md-8">
                                    <input id="phone" type="phone" class="form-control" v-model="user.phone" required autofocus placeholder="01x-xxxxxxx">
                                    <div v-if="user.errors && user.errors.phone" class="text-danger">{{ user.errors.phone[0] }}</div>
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <label for="address" class="col-sm-3 col-form-label text-end">Address</label>
                                <div class="col-md-8">
                                    <input id="address" type="address" class="form-control" v-model="user.address" required autofocus autocomplete="off">
                                    <div v-if="user.errors && user.errors.address" class="text-danger">{{ user.errors.address[0] }}</div>
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <label for="password" class="col-sm-3 col-form-label text-end">Password</label>
                                <div class="col-md-8">
                                    <input id="password" type="password" class="form-control" v-model="user.password" required autofocus autocomplete="off">
                                    <div v-if="user.errors && user.errors.password" class="text-danger">{{ user.errors.password[0] }}</div>
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <label for="confirmpassword" class="col-sm-3 col-form-label text-end">Confirm Password</label>
                                <div class="col-md-8">
                                    <input id="confirm_password" type="password" class="form-control" v-model="user.confirm_password" required autofocus autocomplete="off">
                                    <div v-if="user.errors && user.errors.confirm_password" class="text-danger">{{user.errors.confirm_password[0] }}</div>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>
                            <div v-if="user.errors.failed" class="alert alert-danger text-center mt-3">{{ user.errors.failed }}</div>
                            <div class="row mt-4 mb-2">
                                <small class="text-muted text-center">
                                    Have an account?
                                    <router-link to="/login">Login</router-link>
                                </small>
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
            user: {
                name: '',
                email: "",
                gender: "M",
                password: "",
                confirm_password: '',
                errors: { failed: null}
            }
        }
    },

    methods: {
        async handleRegister() {
            // Reset the errors object
            this.user.errors = {};
            try {
                console.log(this.user);
                await this.$store.dispatch('auth/register',this.user);
                console.log('register ok');
                toastr.success('Registration successful!');
                this.$router.push({ name: 'Login' }); // Redirect to Login page
            } catch (error) {
                console.log('error occur here');
                if (error.response && error.response.status === 422) {
                    this.user.errors  = error.response.data.errors;
                    console.log( this.user.errors );
                } /* else if (error.response && error.response.status === 401) {
                    this.errors.failed = error.response.data.message;
                }  */else {
                    toastr.error('Registration failed!');
                    this.user.errors.failed = 'An unknown error occurred. Please try again later.';
                }
            }
        }
    },
}
</script>
