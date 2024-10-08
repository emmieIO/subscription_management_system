# Subscription Management API

This is a simple Laravel REST api that depicts a subscription management System

### project requirements
* PHP >= 8.2
* composer >=2.7.7

### Project Setup

* Clone project from github.
```
git clone https://github.com/emmieIO/subscription_management_system.git
```
* Change the .env.example file to .env with the command below.
```
cp .env.example .env
```
* Generate a unique application key, which is used for encryption and security
```
php artisan key:generate
```
* Install required packages.
```
composer install
```

* run command for migration & database seeding
  ```
  php artisan migrate --seed
  ```

* Finally serve project.

```
php artisan serve
```


[See Documentation](https://documenter.getpostman.com/view/10549021/2sAXxJivNf)
