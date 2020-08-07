# Slim 3 Test Application

- PHP 7.4
- Slim 3
- Propel 2

# Setup

1. Install via composer (`composer install`).
2. Run `./vendor/bin/propel init` from the `root` and configure your DB and configs.
3. Run your PHP server however you wish. (e.g. in the public dir, run `php -S localhost:8080`).

If you change the schema and need to regenerate the sql and models run:

- `./propel sql:build`
- `./propel sql:insert`
- `./propel model:build`

# Todo

- Comments for tickets
- Tickets should have tags for grouping and filtering
- Filtering added to endpoints
- Security around API (Bearer)
- OpenAPI docs
- Request body checks & further validation
