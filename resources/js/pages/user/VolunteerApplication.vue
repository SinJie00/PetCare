<template>
  <div class="container mb-5">
    <h1 class="text-center mt-4">Volunteer Application</h1>
    <br />
    <h2>Be A Volunteer</h2>
    <p>The animals at Meefah Homeless Animals Shelter are always in need of your help! If you can afford one hour of your
      time, you could make a large difference in the life of an animal.

      Anything else you can think of? We are willing to work with you and any ideas you have! Come join our committees of
      dedicated volunteers who strive to make a difference in our community. You can choose to be the volunteer to be a
      part of our rescue team or shelter team which would help the shelter. We have a variety of activities or duties and
      we are constantly in need of volunteers.</p>
    <h2>Volunteer Agreement</h2>
    <h3>Position as a Volunteer</h3>
    <ol>
      <li>I agree to provide my services to SFFA strictly as a volunteer and I shall not be considered to be an employee
        of SFFA. Neither SFFA nor I intend any employment relationship to be created.</li>
      <li>I understand that all my services rendered to SFFA are with generous and charitable motives and I will perform
        all my duties, tasks or assignments on a voluntary basis without expectation of any allowance, salary, employee
        benefits or payment of any kinds for the services I provided.</li>
      <li>I fully understand that SFFA expects high standards of moral and ethical treatment of animals under its care. I
        agree to adhere strictly to these standards in my voluntary capacity at SFFA.</li>
      <li>I fully understand that either failure to comply with any and all of the obligations outline in this Volunteer
        Agreement or for any reason whatsoever, while performing my volunteer services to SFFA in a voluntary capacity,
        The SFFA, as its sole discretion, may immediately terminate my services.</li>
    </ol>
    <br />
    <h3>Exposure to Risk</h3>
    <ol>
      <li>I fully understand that handling animals and other volunteering activities on behalf of SFFA may place me in
        hazardous situation and could result in and expose me or my personal property to danger(s) or injury(s). The
        following describe some, but not all, of those risks:-
        <ol type="a">
          <li>Injury or illness due to a physical injury such as a bite, trip or fall, crushing incident, strains and
            sprains or physical exhaustion;</li>
          <li> Emotional and mental health issues such as stress, grief, mental exhaustion;</li>
          <li>Injury or illness as a result of exposure to or working with toxic or dangerous chemicals, including
            liquids, fumes and gases;</li>
          <li> Diseases such as Tetanus, zoonotic diseases such as ringworm, Q fever, Lyme disease; and</li>
          <li>Environmental issues including weather (extreme heat or cold), bush fires, exposure to allergens in the air,
            such as pollens, animal hair and etc.</li>
        </ol>
      </li>
      <li>I understand the description of these risks is not complete and I hereby knowingly and willingly accept all the
        risks identified herein and those inherent risks not specifically identified.</li>
      <li>I understand that my participation in any activities in SFFA is purely voluntary and I elect to participate in
        spite of and with full knowledge of the risks.</li>
      <li>With this understanding, I, individually, and on behalf of my heirs, successors, assigns, and personal
        representatives, hereby release, relieve, indemnify and forever discharge SFFA, its employees, agents, officers,
        trustees and representatives (in their official
        and individual capacities) from any and all liability whatsoever for any personal or bodily injury (including
        death) and loss of or damage to property that I may sustain, including but not limited to any claims, demands,
        actions, causes of action, judgments,
        damages, expenses and costs, including legal fees, which arise out of, result from, occur during or are connected
        in any manner with my volunteer's activities on behalf of SFFA.</li>
      <li> If I should suffer an injury or illness while participating in any volunteer’s activities on behalf of SFFA, or
        any activity associated thereof, I agree and authorize the representative or any person in charge of SFFA to use
        their discretion to have me treated at
        the nearest health care facility and hereby give consent, and I take full responsibility for that action and costs
        associated with such care.</li>
    </ol>
    <div class="text-center mt-5">
      <button class="btn btn-lg btn-info btn-block" data-bs-toggle="modal" data-bs-target="#volunteerModal"
        @click.prevent="checkLogin">Apply
        Volunteer</button>
    </div>
  </div>
  <div class="modal" tabindex="-1" id="volunteerModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Apply Volunteer</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-group mb-2">
            <label for="amount">Volunteer Type</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" value="shelter" name="volunteer_type" id="shelter" checked
              v-model="volunteerType">
            <label class="form-check-label" for="shelter">
              Shelter
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" value="rescue" id="rescue" name="volunteer_type"
              v-model="volunteerType">
            <label class="form-check-label" for="rescue">
              Rescue
            </label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" @click.prevent="applyVolunteer">Confirm</button>
        </div>
      </div>
    </div>
  </div>
</template>
  
<script>
export default {
  name: 'VolunteerApplication',
  data() {
    return {
      volunteerType: 'shelter'
    }
  },
  methods: {
    checkLogin() {
      if (!this.$store.getters['auth/isLoggedIn']) {
        // User is not logged in, redirect to login page
        $('#volunteerModal').modal('hide');
        this.$router.push('/login');
        return;
      }
      else {
        $('#volunteerModal').modal('show');
      }
    },
    applyVolunteer() {
      console.log(this.$store.state.auth.user.id);
      console.log(this.volunteerType);
      axios.post('https://petcare-ec207baddaf0.herokuapp.com/api/volunteerapplications', {
        users_id: this.$store.state.auth.user.id,
        volunteer_type: this.volunteerType,
      })
        .then(response => {
          console.log(response.data);
          toastr.success(response.data.message);
        })
        .catch(error => {
          console.log(error);
          if (error.response && error.response.status === 400) {
            this.$swal({
              title: 'Alert Message',
              text: error.response.data.message,
              icon: 'warning',
            });
          } else {
            toastr.error('An unknown error occurred. Please try again later');
          }
        });
      // Close the modal
      $('#volunteerModal').modal('hide');
    }
  },
}
</script>
