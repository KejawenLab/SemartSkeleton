# Semart Skeleton Installation

## Requirements

- PHP 7.2 or Later

- MySQL/MariaDB/PostgreSQL/Oracle as Database Engine

- Redis Server as Session Storage

- Composer

## Use Docker

- Install [Docker](https://docs.docker.com/v17.09/engine/installation) and [Docker Compose](https://docs.docker.com/compose/install/)

- Clone repository `git clone git@gitlab.com:kejawenlab/skeleton.git Semart`

- Change to project root `cd Semart`

- Build Image `docker-compose build`

- Running Container `docker-compose up -d`

- Accessing App Container `docker-compose exec app bash`

- Install Semart Skeleton `php bin/console semart:install`

- Open Browser `http://localhost:8080`

## Without Docker

- Install Requirements

- Clone repository `git clone git@gitlab.com:kejawenlab/skeleton.git Semart`

- Change to project root `cd Semart`

- Install Dependencies `composer update --prefer-dist -vvv`

- Install Semart Skeleton `php bin/console semart:install`

- Run Web Server `php bin/console server:start`

- Open Browser `http://localhost:8000`
