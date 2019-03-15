# Semart Skeleton

## Requirements

```bash
- PHP 7.2 or Later
- MySQL/MariaDB/PostgreSQL/Oracle as Database Engine
- Redis Server as Session Storage
- Composer

```

## How to install

```bash
git clone git@gitlab.com:kejawenlab/skeleton.git Semart

cd Semart

composer install --prefer-dist

php bin/console semart:install
```

## KejawenLab Love Docker

```bash
git clone git@gitlab.com:kejawenlab/skeleton.git Semart

cd Semart 

docker-compose up -d

docker-compose exec app bash

php bin/console semart:install
```

## Documentation

[Doc](doc)

## Running Tests

```bash
php vendor/bin/phpunit
```

## Screenshots

* Login

![Login](doc/imgs/login.png "Login")

* Menu List

![Menu List](doc/imgs/menu_list.png "Menu List")

* Roles

![Roles](doc/imgs/roles.png "Roles")

* Setting List

![Setting List](doc/imgs/setting_list.png "Setting List")

* User Form

![User Form](doc/imgs/user_form.png "User Form")

* User List

![User List](doc/imgs/user_list.png "User List")
