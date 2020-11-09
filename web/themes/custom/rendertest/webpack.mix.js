let mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

mix
  .webpackConfig({
    module: {
      rules: [
        {
          test: /\.scss/,
          enforce: 'pre',
          loader: 'import-glob-loader',
        },
      ],
    },
  })
  .sass('libraries/global/app.scss', 'dist/global/global.css')
  .options({
    processCssUrls: false,
    postCss: [
      tailwindcss('./config/tailwind.config.js'),
      require('postcss-inline-svg')({ paths: ['./assets/icons'] }),
      require('postcss-svgo')(),
      require('postcss-flexbugs-fixes')(),
    ],
  })
  .js('libraries/global/app.js', 'dist/global/global.js');

if (!mix.inProduction()) {
  mix.sourceMaps(true, 'source-map');
}
