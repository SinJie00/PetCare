<template>
    <div class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">Login</div>
            <div class="card-body">
              <form @submit.prevent="login">
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" id="email" v-model="form.email" required>
                </div>
                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" class="form-control" id="password" v-model="form.password" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
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
          email: '',
          password: '',
          errors: []
        }
      },
      methods: {
        login() {
          axios.post('/login', {
            email: this.email,
            password: this.password
          }).then(response => {
            window.location.href = '/';
          }).catch(error => {
            this.errors = error.response.data.errors;
          });
        }
      }
    }
  </script>
<!-- <template>
    <div>
        <h1>Login</h1>
        <form @submit.prevent="login">
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" v-model="form.email" required>
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" v-model="form.password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <div v-if="errorMessage">
            {{ errorMessage }}
        </div>
        <div>
            <p>Don't have an account? <router-link to="/register">Register</router-link></p>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    email: '',
                    password: ''
                },
                errorMessage: ''
            };
        },
        methods: {
            login() {
               /*  axios.post('/api/login', this.form)
                this. */
                /* $axios.post('api/login', {
                    email: this.email,
                    password: this.password
                }) */
                axios.post('/api/login', this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$router.go('/login')
                        } else {
                            this.error = response.data.message
                        }
                    })
                    .catch(error => {
                        this.errorMessage = 'Invalid email or password.';
                    });
            }
        }
    };
</script> -->

