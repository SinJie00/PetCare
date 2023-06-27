<template>
    <div class="container mt-3 mb-5">
        <h1 class="text-center mb-5">{{ article.title }}</h1>
        <div>
            <p>{{ formatDate(article.created_at) }} <br> Category: {{ article.category }}</p>
            <div v-html="article.content"></div>
        </div>
    </div>
</template>
  
<script>
export default {
    data() {
        return {
            article: {
                title: '',
                content: '',
                created_at: '',
            } // Object to store the article data
        };
    },
    created() {
        // Fetch the article when the component is created
        this.getArticle();
    },
    methods: {
        formatDate(date) {
            // Implement your own logic to format the date here
            // Example: return new Date(date).toLocaleString();
            return new Date(date).toLocaleString();
        },
        getArticle() {
            // Fetch the article from the backend API based on the route parameter
            console.log('hi');
            const articleId = this.$route.params.articleId;
            axios.get(`https://petcare-ec207baddaf0.herokuapp.com/api/articles/${articleId}`)
                .then(response => {
                    console.log(response.data);
                    this.article = response.data.article;
                    console.log(this.article);
                })
                .catch(error => {
                    console.error(error);
                });
        },
    }
}
</script>
  
<style></style>
  