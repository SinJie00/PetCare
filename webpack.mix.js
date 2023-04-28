/* const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css', {
      implementation: require('sass'),
      fiber: require('fibers'),
   })
   .options({
      processCssUrls: false,
   }); */

const mix = require('laravel-mix');
/* const { VueLoaderPlugin } = require('vue-loader'); */

mix.js('resources/js/app.js', 'public/js').vue()
      .sass('resources/sass/app.scss', 'public/css')
      .options({
         processCssUrls: false,
         postCss: [
            require('autoprefixer')({
            })
         ],
         // Remove any references to Fibers
         sassOptions: {
            includePaths: [
                  './node_modules',
                  './resources/sass'
            ]
         }
      })
      /* .webpackConfig({
         plugins: [
             new VueLoaderPlugin()
         ]
      }); */
      /* .webpackConfig({
         resolve: {
            extensions: ['.js', '.vue', '.json'],
            alias: {
                  '@': path.resolve('resources/js'),
                  'vue$': 'vue/dist/vue.runtime.esm.js',
                  // Add the following line for BootstrapVue
                  'jquery': 'jquery/src/jquery'
            }
         }
      }); */
   
      


/* const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .webpackConfig({
      resolve: {
        alias: {
          vue$: 'vue/dist/vue.runtime.esm.js',
        },
      },
    }); */
