const mix = require('laravel-mix');

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

mix
    .js('resources/js/deezer.js', 'public/js')
	.js('resources/js/spotify.js', 'public/js')
	.sass('resources/sass/app.scss', 'public/css');

mix
    .js('node_modules/vue-calendar-heatmap/dist/vue-calendar-heatmap.browser.js', 'public/js')
 	.js('node_modules/bootstrap/dist/js/bootstrap.min.js', 'public/js')
	.js('node_modules/admin-lte/dist/js/adminlte.min.js', 'public/js');

    

mix.copyDirectory('node_modules/admin-lte/plugins/fontawesome-free/webfonts', 'public/webfonts');

mix.styles([
    'node_modules/admin-lte/dist/css/adminlte.min.css',
    'node_modules/admin-lte/plugins/fontawesome-free/css/all.min.css',
    'node_modules/admin-lte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css',
    'node_modules/admin-lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css',
    'node_modules/admin-lte/plugins/jqvmap/jqvmap.min.css',
    'node_modules/admin-lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css',
    'node_modules/admin-lte/plugins/daterangepicker/daterangepicker.css',
    'node_modules/admin-lte/plugins/summernote/summernote-bs4.css',
    'node_modules/vue-calendar-heatmap/dist/vue-calendar-heatmap.css'
], 'public/css/adminlte.css');
