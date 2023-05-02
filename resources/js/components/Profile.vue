<template>
    <div class="container mt-5">
        <h1 class="text-center mt-3 mb-5">Profile</h1>
        <div class="row justify-content-center align-items-center">
            <div class="mt-4 col-lg-8 col-md-10 col-sm-12">
                <div class="card">
                    <div class="mt-5 mb-4 card-body">
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label text-end">Name</label>
                            <div class="col-md-8">
                                <input id="name" type="text" class="form-control" v-model="user.name" required
                                    autofocus :disabled="!showUpdate">
                                <div v-if="errors && errors.name" class="text-danger">{{ errors.name[0]}}</div>
                            </div>
                        </div>
                        <div class="form-group row mt-2">
                            <label for="gender" class="col-sm-3 col-form-label text-end">Gender</label>
                            <div class="col-md-8 d-flex align-items-center">
                                <label for="male" class="mr-3 mr-md-5" style="margin-right: 20px;">
                                    <input type="radio" id="male" value="M" v-model="user.gender" :disabled="!showUpdate">Male
                                </label>
                                <label for="female">
                                    <input type="radio" id="female" value="F" v-model="user.gender" :disabled="!showUpdate">Female
                                </label>
                            </div>
                        </div>
                        <div class="form-group row mt-2">
                            <label for="email" class="col-sm-3 col-form-label text-end">E-Mail</label>
                            <div class="col-md-8">
                                <input id="email" type="email" class="form-control" v-model="user.email" required autofocus
                                    :disabled="!showUpdate">
                                <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0]}}</div>
                            </div>
                        </div>
                        <div class="form-group row mt-3">
                            <label for="phone" class="col-sm-3 col-form-label text-end">Phone</label>
                            <div class="col-md-8">
                                <input id="phone" type="phone" class="form-control" v-model="user.phone" required autofocus
                                    placeholder="01x-xxxxxxx" :disabled="!showUpdate">
                                <div v-if="errors && errors.phone" class="text-danger">{{ errors.phone[0]}}</div>
                            </div>
                        </div>
                        <div class="form-group row mt-3">
                            <label for="address" class="col-sm-3 col-form-label text-end">Address</label>
                            <div class="col-md-8">
                                <input id="address" type="address" class="form-control" v-model="user.address" required
                                    autofocus :disabled="!showUpdate">
                                <div v-if="errors && errors.address" class="text-danger">{{errors.address[0] }}</div>
                            </div>
                        </div>
                        <div class="text-center mt-5">
                            <button v-if="!showUpdate" @click="showUpdate = true" type=submit class="btn btn-primary">Update
                                Profile</button>
                            <button v-else type="submit" class="btn btn-success" @click="saveProfile">Save</button>
                        </div>
                        <!-- <div v-if="errorFailed" class="alert alert-danger text-center mt-3">{{ errorFailed }}</div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script>
export default {
    name: 'Profile',
    data() {
        return {
            errors: {},
            /* errorFailed: null */
        }
    },
    computed: {
        user() {
            return this.$store.state.auth.user;
        },
        showUpdate() {
            return showUpdate
        }
    },
    data() {
        return {
            showUpdate: false,
        };
    },
    methods: {
        async saveProfile() {
            try {
                console.log(1);
                console.log(this.user);
                await this.$store.dispatch("auth/updateProfile", this.user);
                console.log(2);
                toastr.success('Update Profile Successfully')
                this.showUpdate = false;
            } catch (error) {
                console.log('error occur at profile.vue');
                if (error.response && error.response.status === 422) {
                    this.errors  = error.response.data.errors;
                } /* else {
                    errorFailed = 'An unknown error occurred. Please try again later.';
                } */
            }
        },
    },
    /* created() {
      this.$store.dispatch('getUser')
        .then(response => {
          // handle success
        })
        .catch(error => {
          // handle error
        })
    }, */

    /* computed: {
      user() {
        return this.$store.getters.user
      }
    } */
}
</script>
  