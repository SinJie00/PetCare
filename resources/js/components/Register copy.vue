<!-- <template>
    <div>
      <h1>Register</h1>
      <b-form @submit.prevent="register">
        <b-form-group id="name-group" label="Name:" label-for="name">
          <b-form-input id="name" v-model="name" type="text" required></b-form-input>
        </b-form-group>
        <b-form-group id="email-group" label="Email address:" label-for="email">
          <b-form-input id="email" v-model="email" type="email" required></b-form-input>
        </b-form-group>
        <b-form-group id="password-group" label="Password:" label-for="password">
          <b-form-input id="password" v-model="password" type="password" required></b-form-input>
        </b-form-group>
        <b-button type="submit" variant="primary">Register</b-button>
      </b-form>
      <div v-if="errors.length" class="alert alert-danger mt-3">
        <ul>
          <li v-for="error in errors">{{ error }}</li>
        </ul>
      </div>
    </div>
  </template> -->
  <template>
    <div>
        <h1>Register</h1>
        <form @submit.prevent="register">
            <div>
                <label for="name">Name</label>
                <input type="text" id="name" name="name" v-model="form.name" required>
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" v-model="form.email" required>
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" v-model="form.password" required>
            </div>
            <div>
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" v-model="form.password_confirmation" required>
            </div>
            <button type="submit">Register</button>
        </form>
        <div v-if="errorMessage">
            {{ errorMessage }}
        </div>
        <div>
            <p>Already have an account? <router-link to="/login">Login</router-link></p>
        </div>
    </div>
</template>
  
  <script>
    export default {
      data() {
        return {
          name: '',
          email: '',
          password: '',
          errors: []
        }
      },
      methods: {
        register() {
          axios.post('/api/register', {
            name: this.name,
            email: this.email,
            password: this.password
          }).then(response => {
            window.location.href = '/login';
          }).catch(error => {
            this.errors = error.response.data.errors;
          });
        }
      }
    }
  </script>
<!-- <template>
    <div>
        <h1>Register</h1>
        <form @submit.prevent="register">
            <div>
                <label for="name">Name</label>
                <input type="text" id="name" name="name" v-model="form.name" required>
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" v-model="form.email" required>
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" v-model="form.password" required>
            </div>
            <div>
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" v-model="form.password_confirmation" required>
            </div>
            <button type="submit">Register</button>
        </form>
        <div v-if="errorMessage">
            {{ errorMessage }}
        </div>
        <div>
            <p>Already have an account? <router-link to="/login">Login</router-link></p>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                },
                errorMessage: ''
            };
        },
        methods: {
            register() {
                axios.post('/api/register', this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$router.go('/login')
                        } else {
                            this.error = response.data.message
                        }
                    })
                    .catch(error => {
                        this.errorMessage = 'There was an error creating your account. Please try again.';
                    });
            }
        }
    };
</script> -->
