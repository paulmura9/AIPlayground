# Symfony + Nginx + MySQL Docker Boilerplate

This is a Symfony 6 boilerplate. It includes a MySQL database and an nginx server.

Recommended software: Docker Desktop, PhpStorm (used for code editing, but also for entering the Docker container)

## Prerequisites

For an out-of-the-box setup that contains all that we need for a local web application, the following features must be enabled on developer's machine:

- Git ( https://git-scm.com/download/win )
- WSL version 1.1.3.0 or later. For this, execute the following command from Command prompt or Power Shell with administrator privileges (restart your machine after running the command):
  > wsl --install
- Docker Desktop (latest version: 4.39.0)
    - for Windows: https://docs.docker.com/desktop/install/windows-install/ (running with WSL2 backend)
    - for Mac: https://docs.docker.com/desktop/install/mac-install/
    - for Linux: https://docs.docker.com/desktop/install/linux-install/


## 1. Starting the containers

To launch, install Docker Desktop, and using a CLI (PowerShell is recommended, or PhpStorm's Terminal), enter the
command `docker compose up -d` in the directory this project exists i.e.
`C:/Users/YOUR_NAME/Downloads/symfony-docker-nginx`.

## 2. Accessing the PHP container CLI

From the CLI, in the same directory you entered the `docker compose up -d` command,
enter `docker ps` to get the container IDs, which will be random, and look for the ID of the symfony-docker-nginx-php
image (i.e. ID 1eb9a5a63542). Then, enter `docker exec -it CONTAINER_ID bash`, i.e. `docker exec -it 1eb9a5a63542 bash`.
You can also enter `docker exec -it ligaac-api bash`, `ligaac-api` being the name of the PHP container.
The command you now enter will be executed on the container itself, so in Linux.

NOTE: This is if you don't use PhpStorm. If you do, use the Services tab and access the container's terminal from there.

## 3. Running the database migrations

Database migration files are scripts or files that contain instructions for altering the structure or content of a
database. From the CLI, after accessing the PHP Docker container, run `php bin/console doctrine:migrations:migrate`.
This will create the database for the project, with all the required tables. In order to run a single migration, run
`doctrine:migrations:execute --up 'DoctrineMigrations\VersionXXXX'`, where you add the timestamp of the migration you
want to run.

## 4. Filling the database tables with records
In order to have some data ready for testing purposes, some Fixture files have been provided, which create some SQL
scripts that will insert records with pseudo-random data in all the tables. To fill the tables with records, run
`php bin/console doctrine:fixtures:load` from the CLI, after accessing the PHP Docker container. In order to run a single
fixture, run `php bin/console doctrine:fixtures:load --group=X`, where you add the group the fixture is in. Currently,
three groups are set up: `products`, `carts`, `categories`.

## 5. Speeding up response times on Windows

On Windows, Symfony projects that use Docker are slow because of the var and vendor folders. The compose.yml file
excludes these folders from being synced with the container. It is recommended to un-comment these lines after running
docker compose for the first time, for improved performance (in `compose.yml`). Example
difference in performance for a response: **120ms** with the lines un-commented vs **1750ms** with the lines commented.