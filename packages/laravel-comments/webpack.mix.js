const path = require("path");
const mix = require("laravel-mix");

mix.autoload({});

// const publicPath = 'public'
const publicPath = "./../../public/vendor/comments";

mix.js("resources/assets/js/app.js", "").vue();
mix.sass("resources/assets/sass/app.scss", "");

mix.js("resources/assets/js/admin.js", "").vue();
mix.sass("resources/assets/sass/admin.scss", "");

mix.js("resources/assets/js/comments.js", "");
mix.sass("resources/assets/sass/comments.scss", "");

mix.disableNotifications();
mix.setPublicPath(publicPath);

if (mix.inProduction()) {
  mix.version();
} else {
  mix.sourceMaps();
}

mix.webpackConfig({
  externals: {
    "pusher-js": "Pusher",
  },

  resolve: {
    alias: {
      "@": path.join(__dirname, "./resources/assets/js"),
    },
  },

  output: {
    library: "Comments",
    libraryTarget: "umd",
  },
});
