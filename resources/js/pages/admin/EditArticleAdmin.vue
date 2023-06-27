<template>
    <div class="container mt-5 mb-5">
        <h1 class="text-center mb-5">Edit Article</h1>
        <form>
            <div class="form-group">
                <label for="title">Title*</label>
                <input type="text" class="form-control" id="title" v-model="article.title" required>
            </div>
            <div class="form-group mt-2">
                <label for="category">Category*</label>
                <select class="form-control" id="category" v-model="article.category" required>
                    <option value="">-- Select Category --</option>
                    <option value="Volunteer">Volunteer</option>
                    <option value="Rescue">Rescue</option>
                    <option value="Others">Others</option>
                </select>
            </div>
            <div class="form-group mt-2">
                <label for="content">Content*</label>
                <div id="app">
                    <ckeditor :editor="editor" v-model="article.content" :config="editorConfig"></ckeditor>
                </div>
            </div>
            <div class="form-group mt-4 row justify-content-center">
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary" @click.prevent="editArticle">Update</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

export default {
    name: 'app',
    data() {
        return {
            editor: ClassicEditor,
            editorData: '<p>Article Content.</p>',
            editorConfig: {
                cloudServices: {
                    tokenUrl: 'https://98042.cke-cs.com/token/dev/yqFPRGVhny20kEBvcNlfbQcV0ej5PdEm5bEt?limit=10',
                    uploadUrl: 'https://98042.cke-cs.com/easyimage/upload/'
                }
            },
            article: {
                title: '',
                category: '',
                content: '',
            },
        };
    },
    created() {
        console.log('hi');
        console.log(this.$route.params.articleId);
        this.articleId = this.$route.params.articleId;
        axios.get(`http://localhost:81/api/articles/${this.articleId}`)
            .then(response => {
                this.article = response.data.article;
                console.log(response.data);
                console.log(this.article);
            })
            .catch(error => {
                console.error(error);
            });
    },
    methods: {
        editArticle() {
            let articleData = new FormData();
            articleData.append('title', this.article.title);
            articleData.append('category', this.article.category);
            articleData.append('content', this.article.content);
            /* articleData.append('author_id', this.$store.state.auth.user.id); */
            articleData.append('_method', 'PUT');
            console.log(articleData);
            // Send a POST request to your backend API to create the article
            axios.post(`http://localhost:81/api/articles/${this.article.id}`, articleData)
                .then(response => {
                    // Reset the form after successful creation
                    this.article.title = '';
                    this.article.category = '';
                    this.editorData = '';
                    toastr.success('Article updated successfully');
                    // Navigate to the article list view
                    this.$router.push('/admin/article');
                })
                .catch(error => {
                    console.log('Error creating article:', error);
                    // Handle error scenarios, show error message, etc.
                });
        }

    }
}
</script>
