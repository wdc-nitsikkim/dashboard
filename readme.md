<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Documentation
- [Laravel v5.5](https://laravel.com/docs/5.5)
- [Volt Bootstrap 5 Demo](https://demo.themesberg.com/volt/pages/dashboard/dashboard.html)
- [Template Docs](https://themesberg.com/docs/volt-bootstrap-5-dashboard/getting-started/quick-start/)

## Setting up local environment

- Download PHP `v.7.0.33`
  - [Windows](https://drive.google.com/file/d/1lJrBvpO-SGr1FLaiV9sFtrK3dhgpj2hr/view?usp=sharing) _(ZIP Archive)_
  - [Linux](https://www.linuxfork.com/how-to-install-php-7-0-33-on-ubuntu-18-04-20-04-lts/) _(Instructions)_
- Extract the above downloaded file to a suitable location
- Add this folder to your path
  - Remove any other versions of PHP if present (**just removing them from your path will work**)
- Open `php.ini`, which can be found at the root directory of the newly extracted PHP folder
- Replace all occurences of `D:\Program Files\php-7.0.33` with the absolute path of your PHP installation folder _(as this version of PHP doesn't support relative paths)_
- Open a terminal and run `php -v`. If it looks something like below, you can continue else follow the above step more carefully or try restarting your PC
- ![php-v](https://user-images.githubusercontent.com/43738236/128649946-22e2197a-0d82-4100-ab5d-ae1d4086858c.png)
- After this, download **Composer**
  - [Windows](https://getcomposer.org/Composer-Setup.exe)
  - [Other](https://getcomposer.org/download/)
- Configure it accordingly so that you can run it from command line
- ![composer-V](https://user-images.githubusercontent.com/43738236/128650164-f0b1e119-d639-45c0-8a61-484f0e1270d6.png)
- Clone [this repository](https://github.com/wdc-nitsikkim/admin-laravel.git) at a location of your preference.
- Open a terminal inside this folder and run `composer install` or `composer update`to download all the required dependencies
- Create a `.env` file from the provided `.env.sample` and fill in the required fields
- Run `php artisan migrate` from inside the project folder to migrate database structure
- Finally, run `php artisan serve` to start your local development server at `http://127.0.0.1:8000`

## For initial login
- Open the database and insert an entry in the `users` table
  - Generate a compatible password by visiting `http://127.0.0.1:8000/hash/{your-password}` where your password is any string
  - Copy the above generated password & paste it in the password field of the new user
- Insert a record in the `user_roles` table using the `user_id` of the above inserted row & role as `admin` for unrestricted access
- Give `c`, `r` & `u` permissions to this new role using the `user_role_permissions` table _(different row for each permission)_.
`role_id` will be the same as `id` of the role you created in `user_roles` table

That's it, now open <http://localhost:8000/login> and login using the credentials _(if the email you used belongs to the `nitsikkim.ac.in`
domain, you can also login via google)_
