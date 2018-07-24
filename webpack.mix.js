let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js(['node_modules/jquery/dist/jquery.min.js', 'public/js/jquery.min.js',
            'node_modules/bootstrap/dist/js/bootstrap.js','resources/assets/js/jquery.min.js', 'resources/assets/js/app.js'], 'public/js/app.js', 'public/js/jquery-ui.min.js')
   .sass('resources/assets/sass/app.scss', 'public/css/app.css');

mix.autoload({
  jquery: ['$', 'jQuery', 'window.jQuery'],
  tether: ['Tether', 'window.Tether']
});
