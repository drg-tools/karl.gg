const mix = require("laravel-mix");

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

mix.js("resources/js/app.js", "public/js")
    .vue()
    .extract()
    .copy("resources/js/assets", "public/assets")
    .copy("resources/fonts", "public/fonts")
    .sass("resources/scss/app-v2.scss", "public/css")
    .options({
        postCss: [
            require("postcss-import")(),
            require("tailwindcss")(),
            require("postcss-nested")(),
            require("autoprefixer")(),
        ],
    })
    .sourceMaps();

if (mix.inProduction()) {
    mix.version();
}
