# Midair

Midair is a PHP/MySQL-based personal content aggregation platform, handing the storage and display of an expandable list of content from online sources.

## Features

Midair currently supports importing content from the following providers:

- Wordpress
- Medium
- Tumblr
- Goodreads
- Letterboxd
- Bluesky
- Strava
- Spotify playlist additions

## Development

### Prerequisites

- PHP 8.2
- MySQL 8.0
- Composer
- Git
- Docker

### Installation

1. Clone the repository
2. Run `composer install`
3. Run `docker compose up`

Any SQL dump file can be imported into the database at startup by placing it inside a `mysql` directory in the root of the project.

## Adding a new provider module

Provider modules are located in the `midair` directory. Each module should have its own directory (named for the content type), containing the following files:

- `Config/Routes.php`: Routing configuration for the module
- `Database/Migrations/YYYY-MM-DD-HHMMSS_CreateTable.php`: Migration file for the module database table, field names based on what data you expect to be able to import from the provider
- `Models/ModuleName.php`: Model class for the module
- `Controllers/Import.php`: Controller class for the module import
- `Controllers/Display.php`: Controller class for the module output
- `Views/item.php`: View file for the module items when rendered within a list
- `Views/single.php`: View file for the module items when rendered as a single item

Environment keys for any new module should be added to the `.env` file in the root of the project. When ready to test, add the module to the `app/Config/Autoload.php` file and restart Docker to run the new migration. New categories for writing or consuming feeds also need to be added to those queries in the `Main.php` controller. And the cron job to call the import scripts should also be updated to include the new module.
