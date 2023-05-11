FROM php:8.2-cli

RUN true \
    # Install main extension
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql \
    # Install composer
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    # Cleanup
    && docker-php-source delete \
    && apt-get autoremove --purge -y && apt-get autoclean -y && apt-get clean -y \
    && rm -rf /usr/src

ADD https://github.com/ufoscout/docker-compose-wait/releases/download/2.9.0/wait /usr/local/bin/docker-compose-wait
RUN chmod +x /usr/local/bin/docker-compose-wait

ADD docker/php/conf.d/. "$PHP_INI_DIR/conf.d/"

ENTRYPOINT ["./entrypoint.sh"]