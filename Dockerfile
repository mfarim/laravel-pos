FROM php:7.4-apache

# Update package repository dan install dependencies sistem
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql gd zip bcmath opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

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
