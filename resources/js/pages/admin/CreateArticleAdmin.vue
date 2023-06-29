<template>
    <div class="container mt-5 mb-5">
        <h1 class="text-center mb-5">Create Article</h1>
        <form>
            <div class="form-group">
                <label for="title" class="form-label fw-bold">Title<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" v-model="article.title" required>
                <div v-if="v$.article.title.$error" class="text-danger">Title is required.</div>
            </div>
            <div class="form-group mt-4">
                <label for="category" class="form-label fw-bold">Category<span class="text-danger">*</span></label>
                <select class="form-control" id="category" v-model="article.category" required>
                    <option value="">-- Select Category --</option>
                    <option value="Volunteer">Volunteer</option>
                    <option value="Rescue">Rescue</option>
                    <option value="Others">Others</option>
                </select>
                <div v-if="v$.article.category.$error" class="text-danger">Category is required.</div>
            </div>
            <div class="form-group mt-4">
                <label for="content" class="form-label fw-bold">Content<span class="text-danger">*</span></label>
                <div id="app">
                    <ckeditor :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor>
                </div>
                <div v-if="v$.editorData.$error" class="text-danger">Content is required.</div>
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
import { useVuelidate } from '@vuelidate/core';
import { required } from '@vuelidate/validators';
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
            axios.post('/api/upload-ckeditor-image', data)
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
    setup() {
    const v$ = useVuelidate();
    return { v$ };
  },
  validations() {
    return {
      editorData: { required },
      article: {
                title: { required },
                category: { required },
            },
    }
    }, 
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
                        url: '/api/upload-ckeditor-image', // Replace with your backend route for file upload
                    },
                }, */
            },
            article: {
                title: '',
                category: '',
            },
        };
    },
    methods: {
        addArticle() {
            this.v$.$touch();
            if (!this.v$.$error){
            let articleData= new FormData();
            articleData.append('title', this.article.title);
            articleData.append('category', this.article.category);
            articleData.append('content', this.editorData);
            articleData.append('author_id', this.$store.state.auth.user.id);
            console.log(articleData);
            // Send a POST request to your backend API to create the article
            axios.post('/api/articles', articleData)
                .then(response => {
                    // Reset the form after successful creation
                    this.article.title = '';
                    this.article.category = '';
                    this.content = '';
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
}
</script>
