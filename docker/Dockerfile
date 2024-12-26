FROM php:8.3-fpm

RUN docker-php-ext-install pdo_mysql

# For RabbitMQ
RUN apt-get update && apt-get install -y \
	unzip \
	librabbitmq-dev
RUN pecl install amqp-2.1.2 && docker-php-ext-enable amqp

# composer
COPY --from=composer /usr/bin/composer /usr/bin/composer
ENV PATH="$PATH:/usr/local/bin"

CMD php-fpm