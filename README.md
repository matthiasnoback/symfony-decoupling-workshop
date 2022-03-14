# Sandbox project for a Symfony workshop

You'll find all the available training programs here: <https://matthiasnoback.nl/training/>

## Requirements

- Docker Engine
- Docker Compose
- Git
- Bash

## Getting started

- Clone this repository (`git clone git@github.com:matthiasnoback/symfony-decoupling-workshop.git`) and `cd` into it.
- Run `bin/install`.
- Open <https://localhost> in a browser. You should see the homepage of a simple task management app.

## Running development tools

- Run `bin/composer` to use Composer (e.g. `bin/composer require ...`)
- Run `bin/test` to run all tests
- Run `bin/cli` to run Symfony CLI commands (e.g. `bin/cli doctrine:migrations:diff`)

## Cleaning up after the workshop

- Run `bin/cleanup` to remove all containers for this project, their images, and their volumes.
- Remove the project directory.
