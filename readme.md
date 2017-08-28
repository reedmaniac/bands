
<p align="center"><img src="https://www.shareicon.net/data/128x128/2016/06/03/774951_music_512x512.png"></p>

# Bands

Quick Coding Challenge.  Includes a RESTful API and Vue.js frontend. No Unit Tests though because I was only supposed to spend 5 hours on this and spent most of that teaching myself Vue.js. Also, Game of Thrones is going to be on soon.

## Installations

### Server Requirements
 - PHP 5.6 or later with safe mode disabled
 - MySQL 5.5.0 or later, with the InnoDB storage engine installed. MariaDB works too.
 - A web server (Apache, Nginx)
 - OpenSSL PHP Extension
 - PDO PHP Extension
 - Mbstring PHP Extension
 - Tokenizer PHP Extension

### Steps

 - Insure you have a server meeting the above requirements. [Laravel Homestead](https://laravel.com/docs/5.4/homestead) is a superb development environment for Bands.
 - Create a database for your new site in MySQL/MariaDB.
 - Clone this GitHub repo onto your server.
 - Run the following [Composer](https://getcomposer.org) command in your local repo's directory: `composer create-project --prefer-dist`
 - Set up your .env file with your DB configuration settings and APP_URL
 - Configure your webserver to make the `./public` directory your web root.
 - Run migrations and seeder via the CLI: `php artisan migrate:refresh --seed`
 - Direct your browser to your site's URL. Example: https://bands.app

