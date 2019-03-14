# Semart Skeleton

## Requirements

```bash
- PHP 7.2 or Later
- MySQL/MariaDB/PostgreSQL/Oracle as Database Engine
- Nginx or Apache as Web Server
- Redis Server as Session Storage
- Composer

```

## How to install

```bash
git clone git@gitlab.com:semart/skeleton.git Semart

cd Semart

composer install --prefer-dist

php vendor/bin/phpunit

php bin/console semart:install
```

## Documentation

[Doc](doc)
