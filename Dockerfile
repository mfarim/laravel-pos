FROM php:7.4-apache

# Menggunakan repositori arsip resmi Debian Bullseye (bebas error 404)
RUN echo "deb http://archive.debian.org/debian bullseye main" > /etc/apt/sources.list && \
    echo 'Acquire::Check-Valid-Until "false";' > /etc/apt/apt.conf.d/99no-check-valid-until && \
    apt-get update && \
    apt-get install -y --no-install-recommends \
        git \
        unzip \
        default-mysql-client \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install ekstensi PHP menggunakan installer resmi mlocati
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_mysql gd zip bcmath opcache

# Aktifkan mod_rewrite Apache untuk routing Laravel
RUN a2enmod rewrite

# Konfigurasi Apache DocumentRoot ke folder public
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Salin Composer binary versi 2.2 LTS (kompatibel penuh dengan PHP 7.4)
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Salin source code project
COPY . /var/www/html

# Siapkan folder direktori storage dan cache
RUN mkdir -p storage/framework/cache \
             storage/framework/sessions \
             storage/framework/views \
             storage/logs \
             bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Salin dan siapkan script entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose port HTTP
EXPOSE 80

# Entrypoint otomatis (auto env, auto migrate, auto seed)
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Perintah default untuk menjalankan Apache
CMD ["apache2-foreground"]
