# Adapt template for Drupal projects

Based on [drupal composer project](https://github.com/drupal-composer/drupal-project).

Use this project to quickly bootstrap a new Drupal development environment.

## Create New Project

```bash
composer create-project adapt/rendertest some-dir --repository='{"type": "vcs","url": "https://github.com/adaptdk/drupal8-starter"}' --stability=dev --remove-vcs
cd some-dir
composer local-settings
composer namespace some-project-name
```

## Custom Theme

This project generates custom theme scaffolding for you. The technologies used are:

- [Laravel Mix](https://github.com/JeffreyWay/laravel-mix)
- [TailwindCSS](https://tailwindcss.com/)
- [Sass](https://sass-lang.com/)
- [Husky](https://github.com/typicode/husky)
- [Lint Staged](https://github.com/okonet/lint-staged)
- [Prettier](https://prettier.io/)

Note: Laravel Mix has a bug which adds `vue-template-compiler` to package.json regardless if you're using Vue or not. It doesn't hurt anything.

Don't like the front-end tools we're using? Change them after you generate your project! This is just a starting point for the 80% use case.

## Incorporate Upstream Updates From Drupal Compose Project

```bash
cd path-to/drupal8-starter
git remote add upstream git@github.com:drupal-composer/drupal-project.git
git fetch upstream
git merge upstream/8.x 8.x
```

## Contributing

Did you do something useful that could improve this project? Submit a PR!
