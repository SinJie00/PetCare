<template>
  <div class="container mt-5 mb-5">
    <h1 class="text-center mb-5">Article</h1>
    <div class="row mt-4">
      <div class="col-md-4" v-for="article in articles" :key="article.id">
        <div class="card mb-4">
          <div class="card-body">
            <h5 class="card-title">{{ article.title }}</h5>
            <p class="card-text">{{ formatDate(article.created_at) }} <br> Category: {{ article.category }}</p>
            <p class="card-text" v-html="truncateContent(article.content)"></p>
            <div class="d-grid gap-2">
              <router-link :to="`/articles/${article.id}`" class="btn btn-primary">View More</router-link>
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
      console.log(content);
      const maxLength = 1000; // Maximum length for the content preview
      if (content.length <= maxLength) {
        console.log('1');
        console.log(content);
        return content;
      } else {
        console.log(content.substring(0, maxLength) + '...');
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
  },
}
</script>
