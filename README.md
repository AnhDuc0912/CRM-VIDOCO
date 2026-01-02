## Setup

1.Docker

- docker-compose up --build -d
- docker exec -it vidoco-crm_app bash
- composer install
- php artisan migrate
- php artisan dev:install

    2.Version

- PHP 8.2
- Laravel 12.x

\*\* Run seed permission:

- php artisan db:seed --class=RolePermissionSeeder
