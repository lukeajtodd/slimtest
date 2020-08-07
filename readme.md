# Slim 3 Test Application

- PHP 7.4
- Slim 3
- Propel 2

# Setup

1. Install via composer (`composer install`)
2. Run `./vendor/bin/propel init` from the `root`
3. Configure your database (I have included an example schema for the application `schema.xml.example`)
4. After the init command succeeds and you have configured your schema.xml run:

- `./propel sql:build`
- `./propel sql:insert`
- `./propel model:build`

5. Run your PHP server however you wish. (e.g. in the public dir, run `php -S localhost:8080`)
