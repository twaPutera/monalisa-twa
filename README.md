<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<img alt="php" src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white"> <img alt="bootstrap" src="https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white"> <img alt="bootstrap" src="https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white"> <img alt="bootstrap" src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white">
</p>

# UP Assets Management

## System Requerements and Laravel Information

-   Laravel 8
-   PHP >= 8.0 | 7.4
-   [GIT Windows](https://git-scm.com/download/win)
-   [Composer](https://getcomposer.org/download/)
-   Apache Server, SQL Server, => [Dapat diperoleh dengan menginstall [XAMPP](https://www.apachefriends.org/download.html) atau [Laragon](https://laragon.org/download/index.html)]
-   [Npm dan NodeJs](https://nodejs.org/en/)

## Installation & updates

-   Buka folder `xampp/htdocs` atau `laragon/www` lalu clone repository ini
-   Buka folder `up-asset-management` di Visual Studio Code
-   Buat sebuah database di mysql,menggunakan phpmyadmin. Selanjutnya rename file `.env.rename` menjadi `.env` lalu sesuaikan nama database di `.env` dengan database yang telah dibuat
-   Buka terminal/cmd, arahkan ke folder root project. Jalankan perintah `composer update`. Setelah itu, jalankan perintah berikut secara bertahap

1. `php artisan migrate --seed`
2. `php artisan serve`

-   Jika tidak ada masalah, silahkan akses kehalaman `http://localhost:8000/admin/dashboard`, maka anda akan diarahkan ke halaman Login SSO

## Account Information

Admin
-   Username : admin@gmail.com
-   Password : password
## Noted

Sebelum melakukan pull atau sebelum melakukan push, mohon melakukan reformat dengan syntax `php artisan php-cs-fixer:fix`, Kemudian silahkan lakukan git add dan commit kembali, lalu dapat melakukan push

## Contributing

-   Ganeshcom Studio

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
