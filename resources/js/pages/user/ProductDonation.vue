<template>
  <div class="container mt-5 mb-4">
    <h1 class="text-center mb-5">Product Donation</h1>
    <div class="row">
      <div class="col-md-4" v-for="product in products" :key="product.id">
        <div class="card mb-4 box-shadow">
          <img class="card-img-top mt-4" :src="product.image" alt="Product image" width="50" height="150"
            style="object-fit:contain">
          <div class="card-body text-center">
            <h4 class="card-title">{{ product.name }}</h4>
            <p class="card-text">{{ 'Price: RM' + product.price }}
              <br> {{ 'Required Quantity: '+ product.required_quantity }}
            </p>
            <div class="d-flex justify-content-center" style="margin-top: 10px;">
              <div class="btn-group">
                <a class="btn btn-success" target="_blank" style="border-radius: 10px" :href="product.link">Donate</a>
              </div>
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
      products: [],
    };
  },
  mounted() {
    this.getProducts();
  },
  methods: {
    getProducts() {
      axios.get('/api/products')
        .then(response => {
          this.products = response.data;
          console.log(this.products);
        })
        .catch(error => {
          console.log(error);
        });
    },  
  }
};
</script>
