<template>
  <div class="mt-4 mb-5 container">
    <h1 class="text-center mb-4">Donate Money</h1>
    <div class="row d-flex justify-content-center">
      <div class="col-lg-4 col-sm-6">
        <img src="../../../assets/img/donation_poster.jpg" alt="Donation Poster" class="img-fluid">
      </div>
      <div class="col-lg-4 col-sm-6">
        <img src="../../../assets/img/donation_qr_code.jpg" alt="Donation QR Code" class="img-fluid"
          style="object-fit: cover; height: 100%;">
      </div>
    </div>
    <div class="row mt-4 d-flex justify-content-center">
      <div class="col-lg-8 col-sm-12">
        <p class="lh-1">One Ringgit One Person can feed the furries child and give him a happy home!
          <br><br>Cash Donations are vital for Meefah Homeless Animals Shelter to continue sheltering and rehoming
          animals, as we are fully-funded by public donations.
          <br><br>We sincerely appreciate your donations to us and all your contributions, whether in terms of cash
          donations, goods-in-kind, or volunteering your time.
        </p>
      </div>
    </div>
    <div class="text-center mt-3">
      <button class="btn btn-lg btn-info btn-block" data-bs-toggle="modal" data-bs-target="#donationModal">Make
        Donation</button>
    </div>
  </div>
  <div class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog-centered" id="donationModal" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Make a Donation</h2>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-bs-dismiss="modal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="makeDonation">
            <div class="form-group">
              <label for="amount">Amount:</label>
              <input type="number" class="form-control" id="amount" v-model="donationAmount" required>
            </div>
            <div class="form-group">
              <label for="payment-method">Payment Method:</label>
              <select class="form-control" id="payment-method" v-model="selectedPaymentMethod">
                <option value="card">Credit Card</option>
                <option value="bank">Bank Transfer</option>
              </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
          <!-- <div v-if="showSummary">
            <h5>Donation Summary:</h5>
            <p>Amount: ${{ donationAmount }}</p>
            <p>Payment Method: {{ selectedPaymentMethod }}</p>
            <button class="btn btn-primary" @click="processPayment">Proceed Payment</button>
          </div> -->
        </div>
      </div>
    </div>
  </div>
   <!--  <div class="modal" tabindex="-1" role="dialog" :class="{ 'd-block': showModalFlag }">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Make a Donation</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="hideModal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="makeDonation">
              <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="number" class="form-control" id="amount" v-model="donationAmount" required>
              </div>
              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </div> -->
</template>  

<script>

export default {
  name: 'MoneyDonation',
  data() {
    return {
      donationAmount: '',
      selectedPaymentMethod: '',
    }
  },
  methods: {
    makeDonation(){
      console.log('hi');
    }
  },
}
</script>