const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.disableSuccessNotifications();

mix.js("resources/js/app.js", "public/assets/js")
    .postCss("resources/css/app.css", "public/assets/css", [
        require("postcss-import"),
        require("tailwindcss"),
    ])
    .postCss("resources/css/material-dashboard.min.css", "public/assets/css")
    .postCss("resources/css/material-dashboard-rtl.css", "public/assets/css")
    .postCss("resources/css/select2.min.css", "public/assets/css")
    .postCss("resources/css/splide.min.css", "public/assets/css");

if (mix.inProduction()) {
    mix.version();
}
