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
	.sass('resources/sass/app.scss', 'public/css');

mix
 	.js('node_modules/bootstrap/dist/js/bootstrap.min.js', 'public/js')
	.js('node_modules/materialize-css/dist/js/materialize.min.js', 'public/js')
	.sass('node_modules/materialize-css/sass/materialize.scss', 'public/css');