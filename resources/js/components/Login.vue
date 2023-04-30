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
                                <input id="email" required v-model="email" type="email" class="form-control"
                                    :class="{ 'is-invalid': !email }">
                                <div class="invalid-feedback">Please enter your email</div>
                            </div>
                            <div class="form-group mt-4">
                                <label for="password">Password</label>
                                <input id="password" required v-model="password" type="password" class="form-control"
                                    :class="{ 'is-invalid': !password }">
                                <div class="invalid-feedback">Please enter your password</div>
                            </div>
                            <div class="text-center mt-4">
                                <button :disabled="!isFormValid || processing"
                                    class="btn btn-lg btn-primary btn-block">Login</button>
                            </div>
                        </form>
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
    data() {
        return {
            email: "",
            password: "",
            error: null
        }
    },

    methods: {
        handleSubmit(e) {
            e.preventDefault()
            if (this.password.length > 0) {
                this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    this.$axios.post('api/login', {
                        email: this.email,
                        password: this.password
                    })
                        .then(response => {
                            if (response.data.success) {
                                this.$router.go('/')
                            } else {
                                this.error = response.data.message
                            }
                        })
                        .catch(function (error) {
                            console.error(error);
                        });
                })
            }
        }
    },

    /* beforeRouteEnter(to, from, next) {
        if (window.Laravel.isLoggedin) {
            return next('');
        }
        next();
    } */
}
</script>