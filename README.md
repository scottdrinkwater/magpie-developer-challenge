### Usage
- Bring up container `docker-compose up -d `
- Copy `.env.example` to `.env`: `cp .env.example .env`
- Run scraper `docker-compose exec app bin/console scrape:phones:json storage/output.json` 
- Run tests: `docker-compose exec app vendor/bin/phpunit`

### If I had more time

- More test coverage across services / transformer / command.
- Feature tests actually hitting the endpoint.
- Clean-up how `$colour` gets passed into transformer - anti open-closed principle 
- Missing doc blocks on some classes
- Make some of the logic for parsing data (e.g. shipping date delimiters) configuration based.
- Get a pipeline / grunt working for auto lint / testing.

### Thoughts / Questions

- Not sure if we should be treating available from / shipping from the same as shipment dates. Assumed yes.