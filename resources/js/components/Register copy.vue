<template>
    <div class="container mt-5">
        <div class="row justify-content-center align-items-center">
            <div class="mt-3 col-lg-6 col-md-8 col-sm-10">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center mt-3 mb-5">Register</h2>
                        <form @submit.prevent="register" novalidate>
                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label text-end">Name</label>
                                <div class="col-md-8">
                                    <input id="name" type="text" class="form-control" v-model="name" required autofocus
                                        autocomplete="off">
                                    <div v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</div>
                                </div>
                            </div>
                            <div class="form-group row mt-2">
                                <label for="gender" class="col-sm-3 col-form-label text-end">Gender</label>
                                <div class="col-md-8 d-flex align-items-center">
                                    <label for="male" class="mr-3 mr-md-5" style="margin-right: 20px;">
                                        <input type="radio" id="male" value="M" v-model="gender">Male
                                    </label>
                                    <label for="female">
                                        <input type="radio" id="female" value="F" v-model="gender">Female
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row mt-2">
                                <label for="email" class="col-sm-3 col-form-label text-end">E-Mail</label>
                                <div class="col-md-8">
                                    <input id="email" type="email" class="form-control" v-model="email" required autofocus
                                        autocomplete="off">
                                    <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <label for="phone" class="col-sm-3 col-form-label text-end">Phone</label>
                                <div class="col-md-8">
                                    <input id="phone" type="phone" class="form-control" v-model="phone" required autofocus
                                        autocomplete="off">
                                    <div v-if="errors && errors.phone" class="text-danger">{{ errors.phone[0] }}</div>
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <label for="address" class="col-sm-3 col-form-label text-end">Address</label>
                                <div class="col-md-8">
                                    <input id="address" type="address" class="form-control" v-model="address" required
                                        autofocus autocomplete="off">
                                    <div v-if="errors && errors.address" class="text-danger">{{ errors.address[0] }}</div>
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <label for="password" class="col-sm-3 col-form-label text-end">Password</label>
                                <div class="col-md-8">
                                    <input id="password" type="password" class="form-control" v-model="password" required
                                        autofocus autocomplete="off">
                                    <div v-if="errors && errors.password" class="text-danger">{{ errors.password[0] }}</div>
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <label for="confirmpassword" class="col-sm-3 col-form-label text-end">Confirm
                                    Password</label>
                                <div class="col-md-8">
                                    <input id="confirm_password" type="password" class="form-control"
                                        v-model="confirm_password" required autofocus autocomplete="off">
                                    <div v-if="errors && errors.confirm_password" class="text-danger">{{
                                        errors.confirm_password[0] }}</div>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                            <div class="row mt-4">
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
import { mapActions } from 'vuex'

export default {
    name: 'Register',
    data() {
        return {
            name: '',
            email: '',
            phone: '',
            gender: "M",
            address: '',
            password: '',
            confirm_password: '',
            errors: null
        }
    },

    methods: {
        ...mapActions('auth', ['register']),
        async register() {
            try {
                const user = {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation
                }
                const response = await this.register(user)
                console.log('hi register here');
                console.log(response);
                this.$router.push({ name: 'Login' })
            } catch (error) {
                console.log('hi error here');
                console.log(error);
                this.errors = error.response.data.errors
            }
        }
        /* register(e) {
            e.preventDefault()
            if (this.password.length > 0) {
                this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    this.$axios.post('api/register', {
                        name: this.name,
                        email: this.email,
                        password: this.password
                    })
                        .then(response => {
                            if (response.data.success) {
                                window.location.href = "/login"
                            } else {
                                this.error = response.data.message
                            }
                        })
                        .catch(function (error) {
                            console.error(error);
                        });
                })
            }
        } */
    },

    /* beforeRouteEnter(to, from, next) {
        if (window.Laravel.isLoggedin) {
            return next('dashboard');
        }
        next();
    } */
}
</script>
