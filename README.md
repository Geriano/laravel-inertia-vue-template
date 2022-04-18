# About This Template
This project is an initial template for building applications.
Built using the php programming language, using laravel, inertiajs, and vuejs.

<p align=center>
<img src="https://img.shields.io/github/license/Geriano/laravel-inertia-vue-template.svg" />
<img src="https://img.shields.io/github/downloads/Geriano/laravel-inertia-vue-template/total.svg" />
<img src="https://img.shields.io/github/forks/Geriano/laravel-inertia-vue-template.svg" />
<img src="https://img.shields.io/github/stars/Geriano/laravel-inertia-vue-template.svg" />
<img src="https://img.shields.io/github/watchers/Geriano/laravel-inertia-vue-template.svg" />
<img src="https://img.shields.io/github/issues/Geriano/laravel-inertia-vue-template.svg" />
<img src="https://img.shields.io/github/issues-closed/Geriano/laravel-inertia-vue-template.svg" />
<img src="https://img.shields.io/github/issues-pr/Geriano/laravel-inertia-vue-template.svg" />
<img src="https://img.shields.io/github/issues-pr-closed/Geriano/laravel-inertia-vue-template.svg" />
</p>

# Demo
You can lookup demo in [here](http://laravel-inertia-vue.herokuapp.com) with username `su` and password `password`

# Dependencies
- [Laravel](https://laravel.com) ^9.0
- [Laravel Jetstream](https://jetstream.laravel.com) ^2.6
- [Inertiajs](https://inertiajs.com)
- [Vue](https://vuejs.org) ^3.0
- [Tailwindcss](https://tailwindcss.com) ^3.0
- [Sweetalert2](https://sweetalert2.github.io) ^11.4
- [Vueform Multiselect](https://vueform.com) ^2.3
- [Fontawesome Free Version](https://fontawesome.com)

# Features
## User crud
![user](https://user-images.githubusercontent.com/59258929/162608772-b063f4c0-1279-4d71-9dac-e1d1b74565bc.jpeg)

## Permission crud
![permission](https://user-images.githubusercontent.com/59258929/162608787-c22b62af-08a4-40f6-ba98-7addcc1f25dc.jpeg)

## Role crud
![role](https://user-images.githubusercontent.com/59258929/162608796-0db26ff6-9ab2-4a02-a5a3-ee4bafc6a969.jpeg)

## Menu builder
![menu-top](https://user-images.githubusercontent.com/59258929/162608828-f204b4a8-e6ab-43bb-97d6-e42651eb1c11.jpeg)
![menu-builder](https://user-images.githubusercontent.com/59258929/162608832-6b8c53d6-6025-48fb-a0e1-e535bee1fcf1.jpeg)

## How to setup this project
- You can clone this project then go to your project directory
- Open your shell and installing dependencies
```shell
composer install
npm install && npm run dev
```
- Copy .env.example file to .env
- Configure your application, database and other in .env file
- Run the migration and seeder
```shell
php artisan migrate:fresh --seed
```
- After all is complete now you can run serve command to look your template
```shell
php artisan serve
```
- Now your template is ready to use

# Note
If you find some bug please create the issue or contact me on gmail: gerznewbie@gmail.com
