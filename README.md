<p align="center"><a href="https://laravel.com" target="_blank">HEALTHTECH</p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Telehealth

Introducing our cutting-edge HealthTech platform, revolutionizing the way you access medical care. Our online and offline doctor booking system seamlessly connects patients with a network of experienced healthcare professionals. With just a few clicks, you can schedule appointments, receive instant confirmations, and even choose between in-person visits or virtual consultations.
Our user-friendly interface prioritizes your convenience while maintaining the highest standards of security and privacy. Say goodbye to long waiting times and hello to a new era of efficient, patient-centric healthcare booking. Your well-being is our priority, and with our HealthTech system, managing your health has never been easier.


## How to run

1. First we need to clone the project. Run the below command and run in your cmd.<br>
   $ git clone https://github.com/InderPaul777/laravelapis.git
2. Next we need to install the dependency packages by run below command.<br>
   $ composer update
3. Now we need to create a .env file at root folder and update the databse credentials, email smtp details etc.<br>
4. Next we need to run the mgrations.<br>
   $ php artisan migrate
5. Next we need to run the seeder.<br>
   $ php artisan db:seed
6. Finally we will run the project.<br>
   $ php artisan serve
