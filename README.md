<p align="center"></p>

# JetVuetify

## Introduction

This package is designed using [Vuetify](https://vuetifyjs.com) to use the power of material desings, in the starter kit for Laravel [Jetstream](https://jetstream.laravel.com) using [Inertia](https://jetstream.laravel.com/1.x/stacks/inertia.html) scaffolding.


## Installing JetVuetify

You may use Composer to install this package in your fresh Jetstream installation:

```shell
composer require arewebs/jet-vuetify
```
The package will automatically register a service provider.


After installing the package, you can run the jet-vuetify:install Artisan command. In addition, you can use the --teams parameter to activate the team management ui.


### Installation
```shell
php artisan jet-vuetify:install
```

### Installation with teams
```shell
php artisan jet-vuetify:install --teams
```

### Finalizing The Installation

After installing JetVuetify, you should install and build your NPM dependencies:

```shell
npm install

npm run dev
```


## License

JetVuetify is open-sourced software licensed under the [MIT license](LICENSE.md).