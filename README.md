## AMI
AMI is a [Laravel](https://laravel.com/) app for importing and analyzing [Advanced Metering Infrastructure from IREA](https://irea.coop/ami/).

## Setup

### Configuration
* Copy .env.example => .env and update .env with local settings
* In .env, set MAPBOX_ACCESS_TOKEN to your mapbox access token. This token should have all public scopes, MAP:READ, and TILESETS:READ
* Update app.timezone (in config/app.php). This should be set to 'America/Denver' for IREA users.
* Run migrations

### Dev
<code>composer i</code>

<code>npm i</code>

<code>npm run dev</code>

### Prod
<code>composer i --no-dev</code>

<code>npm i</code>

<code>npm run prod</code>

<code>npm i --production</code>

## Requirements
AMI requires [**PHP 8.0+**](https://www.php.net/). The rest of the dependencies are handled by composer.

## Sample Data
Some sample data can be found @ [ami-data](https://github.com/dave-wheeler/ami-data)

## License
AMI is open-sourced software licensed under the [GPL3 license](https://www.gnu.org/licenses/gpl-3.0.en.html).
