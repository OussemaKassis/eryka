FROM richarvey/nginx-php-fpm:latest

# Allow composer to run as root (needed for the install below too)
ENV COMPOSER_ALLOW_SUPERUSER 1

# Install PHP dependencies at build time so they're baked into the image,
# instead of re-downloading them on every container boot (was causing 504s
# on cold start). Split into two passes so the slow download step stays
# cached by Docker as long as composer.lock doesn't change.
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .
RUN composer install --no-dev --optimize-autoloader

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

CMD ["/start.sh"]
