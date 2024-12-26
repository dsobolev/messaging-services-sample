# Messaging RabbitMQ sample

The mono-repo consists two independent sevices (**Product** and **Orders**), which communicate through RESTful API and RabbitMQ backed messaging.

Each service relies on _Nginx_ + _PHP-FPM_ (PHP 8.3) + _MariaDB_.

_RabbitMQ_ uses defauld ports/user/pwd.

## Installation

_Prerequisits_: `GNU make` utility installed. Otherwise, `docker compose up` and then all commands can be run from inside the respective containers. Commands are in `Makefile`


`make start` - installs all the neccessary images and runs containers.
`make product-install` and `make orders-install` - install composer deps and udpates DB schema.

There are three `.env` files, the main one (for `docker-compose.yml` file), and one for each of the services. DB creds and API host names/ports are configured there. If you change some of those in any of `.env` - do not forget to copy the change to project's/root `.env` file.

## API reference

File `test.http` contains API calls samples, in REST client format. Tested in Sublime Text client, should be compatible with VS Code client as well.

Please note - ports and host names are the reference of the `.env` file settings, should be adjusted manually by the moment.

### Some notes

* Extractor in Product service - might be better to name it 'formatter', since it is formatting proper json response.

* `.env` files in the root and in the respective services' folders - shouldn't be commited; and there's not a perfect case of usage since the env data is copied and should be thus synced. Some simple automatic copying could be done (with `cat` or similar) to form the root .env file from services' files.
