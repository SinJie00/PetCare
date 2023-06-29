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
  <div class="modal" id="donationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Make a Donation</h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="resetForm"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="makeDonation">
            <div class="form-group">
              <label for="amount" class="form-label fw-bold">Amount</label>
              <input type="number" class="form-control" id="amount" v-model="donationAmount" @input="checkTwoDecimal"
                :class="{ 'is-invalid': !isValidAmount }" min="1" step="any" required>
              <div class="invalid-feedback">
                Please enter a valid amount equal or more than RM1.
              </div>
            </div>
            <div class="form-group mt-2">
              <label for="payment-method" class="form-label fw-bold">Payment Method</label>
              <select class="form-control" id="payment-method" v-model="selectedPaymentMethod"
                :class="{ 'is-invalid': !isValidPaymentMethod }" required>
                <option value="">--Select Payment Method--</option>
                <option value="Credit Card">Credit Card</option>
                <option value="Bank Transfer">Bank Transfer</option>
              </select>
              <div class="invalid-feedback">
                Please select a payment method.
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="resetForm">Cancel</button>
          <button type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#summaryModal"
            @click="makeDonation" :disabled="!isFormValid">Make Payment</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal" id="summaryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Transaction Summary</h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="resetForm"></button>
        </div>
        <div class="modal-body">
          <p>Amount: RM{{ donationAmount }}</p>
          <p>Payment Method: {{ selectedPaymentMethod }}</p>
          <p>Payment Purpose: Donation to Meefah Homeless Animals Shelter</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
            data-bs-target="#donationModal">Back</button>
          <button class="btn btn-primary" @click="processPayment" data-bs-toggle="modal"
            data-bs-target="#paymentModal">Proceed Payment</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Payment Transaction</h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="resetForm"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-success text-center mt-3">Payment has been made successfully</div>
          <p>Payment ID: #5723</p>
          <p>From: {{ this.$store.state.auth.user.name }}</p>
          <p>To: Meefah Homeless Animals Shelter</p>
          <p>Amount: RM{{ donationAmount }}</p>
          <p>Payment Method: {{ selectedPaymentMethod }}</p>
          <p>Payment Reference: Donation to Meefah Homeless Animals Shelter</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-toggle="modal">Close</button>
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
  computed: {
    isValidAmount() {
      return this.donationAmount >= 1;
    },
    isValidPaymentMethod() {
      return !!this.selectedPaymentMethod;
    },
    isFormValid() {
      return this.isValidAmount && this.isValidPaymentMethod;
    }
  },
  methods: {
    checkTwoDecimal() {
      const amountString = this.donationAmount.toString();
      const pointPos = amountString.indexOf('.');
      if (pointPos !== -1) {
          var maxlengthAmount = pointPos + 3;
          this.donationAmount = parseFloat(amountString.slice(0, maxlengthAmount));
      }
    },
    makeDonation() {
      if (this.isFormValid) {
        $('#summaryModal').show(); // Open the second modal using its reference
      }
    },
    resetForm() {
      this.donationAmount = '';
      this.selectedPaymentMethod = '';
      this.amountTouched = false;
      this.paymentMethodTouched = false;
      this.submitAttempted = false;
    },
  },
}
</script>