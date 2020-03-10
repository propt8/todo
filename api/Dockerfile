FROM webdevops/php-nginx:7.4
ARG APP_ENV=production
ENV APP_ENV "$APP_ENV"
ENV fpm.pool.clear_env no
ENV fpm.pool.pm=ondemand
ENV fpm.pool.pm.max_children=50
ENV fpm.pool.pm.process_idle_timeout=10s
ENV fpm.pool.pm.max_requests=500
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_NO_INTERACTION 1
COPY ./docker/nginx/vhost/*.conf /opt/docker/etc/nginx/vhost.common.d/
COPY ./docker/scripts/*.sh /opt/docker/provision/entrypoint.d/
WORKDIR /tmp
RUN wget -O composer-setup.php --progress=bar:force https://getcomposer.org/installer
RUN php composer-setup.php --install-dir=/usr/bin --version=1.6.3
RUN rm -f composer-setup.php
COPY --chown=1000:1000 ./src /app
WORKDIR /app
RUN chmod 777 -R storage
RUN chown -R www-data:www-data storage
RUN cp .env.$APP_ENV .env
RUN if [ "$APP_ENV" = "development" ]; then composer install; else composer install --no-dev --optimize-autoloader; fi
