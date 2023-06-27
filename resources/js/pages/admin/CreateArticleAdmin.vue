<template>
    <div class="container mt-5 mb-5">
        <h1 class="text-center mb-5">Create Article</h1>
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
                    <ckeditor :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor>
                </div>
            </div>
            <div class="form-group mt-4 row justify-content-center">
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary" @click.prevent="addArticle">Create</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
/* import EasyImage from '@ckeditor/ckeditor5-easy-image/src/easyimage';
 */
/* class MyUploadAdapter {
    constructor(loader) {
        this.loader = loader;
    }

    upload() {
        return new Promise((resolve, reject) => {
            const data = new FormData();
            data.append('file', this.loader.file);

            // Send a POST request to your backend API to handle the file upload
            axios.post('https://petcare-ec207baddaf0.herokuapp.com/api/upload-ckeditor-image', data)
                .then(response => {
                    resolve({ default: response.data.url });
                })
                .catch(error => {
                    reject(error);
                });
        });
    }
} */

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
                /* image: {
                    // Configure the custom upload adapter
                    upload: {
                        types: ['png', 'jpg', 'jpeg', 'gif'],
                        adapter: MyUploadAdapter,
                        url: 'https://petcare-ec207baddaf0.herokuapp.com/api/upload-ckeditor-image', // Replace with your backend route for file upload
                    },
                }, */
            },
            article: {
                title: '',
                category: '',
                content: '',
            },
        };
    },
    methods: {
        addArticle() {
            /* const articleData = {
                title: this.article.title,
                category: this.article.category,
                content: this.editorData
            }; */
            let articleData= new FormData();
            articleData.append('title', this.article.title);
            articleData.append('category', this.article.category);
            articleData.append('content', this.editorData);
            articleData.append('author_id', this.$store.state.auth.user.id);
            console.log(articleData);
            // Send a POST request to your backend API to create the article
            axios.post('https://petcare-ec207baddaf0.herokuapp.com/api/articles', articleData)
                .then(response => {
                    // Reset the form after successful creation
                    this.article.title = '';
                    this.article.category = '';
                    this.editorData = '';
                    toastr.success('Article created successfully');
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
