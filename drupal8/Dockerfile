FROM drupal:8.7.8

# COPY ./modules /var/www/html/sites/default/modules
# COPY ./themes /var/www/html/sites/default/themes

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git && \
    apt-get install nano

RUN apt-get update -y && \
    pecl install mongodb && \
    echo "extension=mongodb.so" >> /usr/local/etc/php/conf.d/mongodb.ini

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    ln -s /root/.composer/vendor/bin/drush /usr/local/bin/drushss

# # Install Drush
# RUN composer global require drush/drush && \
#     composer global update

# # Clean repository
# RUN apt-get clean && rm -rf /var/lib/apt/lists/*



# install memcached
RUN apt-get update && \
    apt-get install -y libz-dev libmemcached-dev && \
    pecl install memcached && \
    docker-php-ext-enable memcached

WORKDIR /var/www/html

# COPY services.yml sites/default/services.yml 

RUN mkdir sites/default/files && \
    chmod 777 sites/default/files

# RUN apt-get update -y && \
#     composer install && \
#     composer require mongodb/mongodb

# COPY modules sites/default/modules
# COPY themes sites/default/themes


# RUN apt-get update -y && \
#     composer install && \
#     composer require mongodb/mongodb

COPY composer.json composer.json
# COPY composer.lock composer.lock
COPY modules sites/default/modules
COPY themes sites/default/themes
# COPY vendor vendor

RUN apt-get update -y && \
    composer install

EXPOSE 80