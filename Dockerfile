FROM php

RUN docker-php-ext-install pdo pdo_mysql sockets
RUN curl -sS https://getcomposer.org/installer​ | php -- \
     --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install bcmath
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .
 RUN apt-get update
 RUN apt-get install autoconf automake libtool m4 make zip unzip -y
 RUN composer update --ignore-platform-reqs
CMD php artisan serve --host 0.0.0.0 --port 80 ;     php artisan queue:work

EXPOSE 80
# RUN php artisan migrate