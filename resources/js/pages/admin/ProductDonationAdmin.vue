<template>
  <div class="container mt-5 mb-5">
    <h1 class="text-center mb-5">Product In Need</h1>
    <div class="bg-light clearfix">
      <button class="btn btn-primary mb-3 float-right" data-bs-toggle="modal" data-bs-target="#productModal"
        @click="openCreateModal">Add Product In Need</button> 
    </div>
    <div class="modal" id="productModal" refs="productModal" tabindex="-1" role="dialog"> 
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h2 v-if="modalMode === 'create'" class="modal-title">Create Product In Need</h2>
            <h2 v-else-if="modalMode === 'edit'" class="modal-title">Edit Product In Need</h2>
            <button type="button" class="btn btn-close" data-bs-dismiss="modal" @click="resetForm"
              aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form> 
              <div class="form-group">
                <label for="name">Name*</label>
                <input type="text" class="form-control" id="name" v-model="product.name" required>
              </div>
              <div class="form-group">
                <label for="price">Price*</label>
                <input type="number" class="form-control" id="price" min="1" v-model="product.price" required>
              </div>
              <div class="form-group">
                <label for="required_quantity">Required Quantity*</label>
                <input type="number" class="form-control" id="required_quantity" v-model="product.required_quantity" required>
              </div>
              <div class="form-group">
                <label for="remain_quantity">Remain Quantity*</label>
                <input type="number" class="form-control" id="remain_quantity" v-model="product.remain_quantity" required>
              </div>
              <div class="form-group">
                <label for="link">Link*</label>
                <input type="text" class="form-control" id="link" v-model="product.link" required>
              </div>
              <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" v-model="product.description" required></textarea>
              </div>
              <div class="form-group">
                <label for="image">Image*</label>
                <input type="file" class="form-control" id="image" ref="imageInput" accept="image/*" v-on:change="handleImage" />
                <img v-if="imageUrl" :src="imageUrl" class="mt-2" style="max-width: 200px;" />
              </div>
              <div class="form-group mt-4 row justify-content-end">
                <div class="col-auto mr-2">
                  <button v-if="modalMode === 'create'" data-bs-dismiss="modal" type="submit" class="btn btn-primary"
                    @click.prevent="addProduct">Create</button>
                  <button v-else-if="modalMode === 'edit'" data-bs-dismiss="modal" type="submit" class="btn btn-primary"
                    @click.prevent="editProduct(product.id)">Update</button>
                </div>
                <div class="col-auto">
                  <button type="button" data-bs-dismiss="modal" class="btn btn-danger" @click="resetForm">Cancel</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div>
      <data-table class="table table-bordered table-responsive table-striped" width="100%" :data="products" :columns="columns"
        :options="options"></data-table>
    </div>
  </div>
</template>

<script>
/* import bootstrap from 'bootstrap'; */
export default {
  name: 'ProductInNeedAdmin',
  data() {
    const vm = this;
    return {
      products: [],
      modalMode: '',
      imageFile: '',
      product: {
        name: '',
        price: '',
        required_quantity: '',
        link: '',
        description: '',
        remain_quantity: '',
      },
      columns: [
        { title: 'ID', data: 'id' },
        { title: 'Name', data: 'name' },
        {
          title: 'Image', data: 'image',
          render: function (data, type, row) {
            if (data) {
              return '<img src="' + data + '" width="100" height="100"/>';
            }
            else {
              return '';
            }
          }
        },
        { title: 'Price', data: 'price' ,
          render: function (data, type, row) {
            if (data) {
              return 'RM' + data;
            }
            else {
              return '';
            }
          }},
        { title: 'Required Quantity', data: 'required_quantity'},
        { title: 'Remain Quantity', data: 'remain_quantity'},
        { title: 'Description', data: 'description' },
        { title: 'Link', data: 'link',
          render: function (data, type, row) {
              return `
              <a target="_blank" href="${ data }">${ data }</a>
              `;
          }
        },
        {
          title: 'Actions',
          render: function (data, type, row) {
            return `
              <button class="btn btn-sm btn-success edit-btn" data-id="${row.id}">Edit</button> 
              <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">Delete</button>
            `;
          },
        }
      ],
      options: {
        drawCallback() {
          // Add click event listener to buttons
          $('.edit-btn').on('click', event => {
            const productId = $(event.currentTarget).data('id');
            getProductAndShowModal(productId);
          });
          async function getProductAndShowModal(productId) {
            const productData = await vm.getProduct(productId);
            console.log('product data');
            console.log(productData.product);
            console.log('vm product');
            console.log(vm.product);
            vm.product = productData.product;
            console.log(vm.product);
            $('#productModal').modal('show');
          }
          $('.delete-btn').on('click', event => {
            vm.deleteProduct($(event.currentTarget).data('id'));
          });
        }
      }
    };
  },
  computed: {
    imageUrl() {
      if (this.imageFile) {
        return URL.createObjectURL(this.imageFile);
      } else if (this.product.image) {
        return this.product.image;
      } else {
        return null;
      }
    },
  },
  mounted() {
    this.getProducts();
  },
  methods: {
    openCreateModal() {
      this.modalMode = 'create';
    },
    openEditModal() {
      this.modalMode = 'edit';
    },
    getProducts() {
      axios.get('http://localhost:81/api/products')
        .then(response => {
          this.products = response.data;
        })
        .catch(error => {
          console.error(error);
        });
    },
    resetForm() {
      this.product = {
        name: '',
        price: '',
        required_quantity: '',
        link: '',
        description: '',
        remain_quantity: '',
        image: ''
      };
      this.imageFile = null;
      this.imageUrl = '';
      this.$refs.imageInput.value = '';
    },
    addProduct() {
      console.log('save product');
      console.log(this.product);
      let formData = new FormData();
      formData.append('name', this.product.name);
      formData.append('price', this.product.price);
      formData.append('required_quantity', this.product.required_quantity);
      formData.append('link', this.product.link);
      formData.append('remain_quantity', this.product.remain_quantity);
      formData.append('description', this.product.description);
      formData.append('image', this.imageFile);
      console.log(this.imageFile);
      axios.post('http://localhost:81/api/products', formData)
        .then(response => {
          console.log('api ok');
          console.log(response.data);
          this.products.push(response.data);
          this.resetForm();
          toastr.success('Product created successfully');
        })
        .catch(error => {
          console.error(error);
        });
    },
    editProduct(id) {
      console.log('edit');
      console.log(id);
      console.log(this.imageFile);
      let editFormData = new FormData();
      editFormData.append('name', this.product.name);
      editFormData.append('price', this.product.price);
      editFormData.append('required_quantity', this.product.required_quantity);
      editFormData.append('link', this.product.link);
      editFormData.append('remain_quantity', this.product.remain_quantity);
      editFormData.append('description', this.product.description);
      editFormData.append('_method', 'PUT');
      if (this.imageFile) {
        editFormData.append('image', this.imageFile, this.imageFile.name);
        console.log(this.imageFile);
      }
      for (let [key, value] of editFormData.entries()) {
        console.log(key, value);
      }
      axios.post(`http://localhost:81/api/products/${id}`, editFormData , {
        headers: {
        'Content-Type': 'multipart/form-data'
        }  
      }).then(response => {
          const index = this.products.findIndex(product => product.id === response.data.product.id);
          console.log(index);
          console.log(response.data.message);
          console.log(response.data.product);
          console.log(response.data.product.image);
          this.products[index] = response.data.product;
          console.log('updated data');
          console.log(this.products[index]);
          toastr.success(response.data.message);
          this.resetForm();
        })
        .catch(error => {
          console.error(error);
        });
    },
    getProduct(id) {
      this.modalMode = 'edit';
      return new Promise((resolve, reject) => {
        axios.get(`http://localhost:81/api/products/${id}`)
          .then(response => {
            console.log(this);
            /* this.product = response.data; */
            /* this.$set(this.product, response.data); */
            console.log('in function');
            console.log(this.product);
            resolve(response.data);
          })
          .catch(error => {
            console.error(error);
            reject(error);
          });
      });
    },
    deleteProduct(id) {
      if (confirm('Are you sure you want to delete this product?')) {
        axios.delete(`http://localhost:81/api/products/${id}`)
          .then(response => {
            const index = this.products.findIndex(product => product.id === id);
            this.products.splice(index, 1);
            toastr.success(response.data.message);
          })
          .catch(error => {
            console.error(error);
          });
      }
    },
    handleImage(event) {
      this.imageFile = event.target.files[0];
    }
  }
}
</script>
       


  