# Laravel Sanctum Tutorial
This is the official repository for my [Laravel Sanctum - tutorial]() which is available on YouTube. <br>
•	Author: Code With Dary <br>
•	Twitter: [@codewithdary](https://twitter.com/codewithdary) <br>
•	Instagram: [@codewithdary](https://www.instagram.com/codewithdary/) <br>

### Utilities
The following additional tools will be used during this tutorial

- [Laravel Sanctum](https://laravel.com/docs/9.x/sanctum) for an authentication system
- [Postman](https://www.postman.com/) for our Laravel API
- [Database Client](https://tableplus.com/) to have a look inside our database

## Usage <br>
Setup the repository <br>
```
git clone git@github.com:AhmedRammadan/laravel-sanctum-tutorial.git
cd laravel-sanctum-tutorial
composer install
cp .env.example .env 
php artisan key:generate
php artisan cache:clear && php artisan config:clear 
php artisan serve 
```

## Database Setup <br>
```
mysql;
create database laravel-sanctum-tutorial;
exit;
```


### Setup your database credentials in the ```.env``` file <br>
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel-sanctum-tutorial
DB_USERNAME={USERNAME}
DB_PASSWORD={PASSWORD}
```

### Sanctum
Before you can use Laravel Sanctum, you obviously need to make sure that you install it through Composer. Besides that, you should upblish the Sanctum configuration file as well.
```
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### Migrate tables
```
php artisan migrate
```
### get routes

```
php artisan route:list
```

### for data demo
```
php artisan tinker   
1 - User::factory()->times(25)->create();
2 - Task::factory()->times(250)->create();
```
### read storage file
```
php artisan storage:link
```

### for Clear data

```
---for all ----->  php artisan optimize:clear   
1 - php artisan cache:clear
1 - php artisan view:clear
1 - php artisan schedule:clear-cache
1 - php artisan route:clear 
1 - php artisan queue:clear 
1 - php artisan optimize:clear
1 - php artisan event:clear
1 - php artisan config:clear
1 - php artisan auth:clear-resets  
```
### Create model with migration,controller,resource

```
-m, --migration Create a new migration file for the model.
-c, --controller Create a new controller for the model.
-r, --resource Indicates if the generated controller should be a resource controller

php artisan make:model Notifications -mcr
```
# Credits due where credits due…
Thanks to [Laravel](https://laravel.com/) for giving me the opportunity to make this tutorial on [Laravel Sanctum](https://laravel.com/docs/9.x/sanctum). 
