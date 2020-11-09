# Frontend setup

## Create new library

1. Create folder in `libraries/[component-name]`
1. Create your files (.js, .css, .scss, .twig)
1. Add new entry to `webpack.mix.js`
1. Add library to `.libraries.yml` file

Example can be found in `libraries/global`

## Build

See `package.json` for available build scripts.

To watch for live CSS changes in a Browser add the [livereload](https://chrome.google.com/webstore/detail/livereload/jnihajbhpnppcggbcgedagnkighmdlei?hl=en) plugin and start livereload server in new terminal with

`npm run live`

To build purged and minimized assets run
`npm run prod`

## Using npm libraries

Example:

1. `npm install -D animejs` in a template root folder
1. `import anime from 'animejs/lib/anime.es.js'` in your .js file.
