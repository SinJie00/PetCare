<template>
  <div class="container mt-5 mb-5">
    <h1 class="text-center mb-5">Article</h1>
    <div class="bg-light clearfix">
      <router-link :to="`/admin/article/create`" class="btn btn-primary">Create New Article</router-link>
    </div>
    <div class="row mt-4">
      <div class="col-md-4" v-for="article in articles" :key="article.id">
        <div class="card mb-4">
          <div class="card-body">
            <!-- <h5 class="card-title">{{ article.title }} - {{ formatDate(article.created_at) }}</h5> -->
            <h5 class="card-title">{{ article.title }}</h5>
            <p class="card-text">{{ formatDate(article.created_at) }} <br> Category: {{ article.category }} 
             <!--  Created Time: {{ formatDate(article.created_at) }} -->
            </p>
            <!-- <p class="card-text">Created Time: {{ formatDate(article.created_at) }}</p> -->
            <!-- <div class="card-text" v-html="article.content"></div> -->
            <p class="card-text" v-html="truncateContent(article.content)"></p>
            <!-- <p class="card-text" v-html="truncateContent(article.content)"></p> -->
            <div class="d-grid gap-2">
              <router-link :to="`/articles/${article.id}`" class="btn btn-primary">View More</router-link>
              <router-link :to="`/admin/article/${article.id}/edit`" class="btn btn-success">Edit</router-link>
              <button class="btn btn-danger" @click.prevent="deleteArticle(article.id)">Delete</button>
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
      articles: [], // Array to store the articles
      form: {
        id: null,
        title: '',
        content: '',
        created_at: '',
      },
    };
  },
  created() {
    // Fetch articles when the component is created
    this.getArticles();
  },
  methods: {
    formatDate(date) {
      return new Date(date).toLocaleString();
    },
    truncateContent(content) {
      console.log('here');
      /* console.log(content); */
      const maxLength = 1000; // Maximum length for the content preview
      if (content.length <= maxLength) {
        /* console.log('1');
        console.log(content); */
        return content;
      } else {
        /* console.log(content.substring(0, maxLength) + '...'); */
        return content.substring(0, maxLength) + '...';
      }
    },
    getArticles() {
      // Fetch articles from the backend API
      axios.get('/api/articles')
        .then(response => {
          this.articles = response.data;
        })
        .catch(error => {
          console.error(error);
        });
    },
    deleteArticle(articleId) {
      console.log(articleId);
    // Delete the article with the given ID from the backend API
    this.$swal({
        title: 'Confirmation',
        text: 'Are you sure you want to delete this article?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
      }).then((result) => {
        if (result.isConfirmed) {
      axios.delete(`/api/articles/${articleId}`)
        .then(response => {
          const index = this.articles.findIndex(article => article.id === articleId);
          toastr.success(response.data.message);
          this.articles.splice(index, 1);
        })
        .catch(error => {
          console.error(error);
        });
    }});
  },
  },
}
</script>
