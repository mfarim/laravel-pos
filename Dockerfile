# Base image: Debian 13 (trixie).
# PHP 7.4 sudah EOL di upstream: php.net tidak lagi merilis patch untuk 7.4, dan
# image resmi php:7.4-apache tidak di-rebuild sejak 2022, sehingga masih rentan
# terhadap PHP2Shell (CVE-2026-109618, CVE-2026-109619). Karena aplikasi ini masih
# Laravel 5.5 (belum kompatibel PHP 8.x), PHP 7.4 diambil dari packages.sury.org
# yang membawa backport keamanan, termasuk PHP2Shell dan rilis keamanan
# November 2026 (termasuk CVE-2026-115560). Lihat issue #11.
FROM debian:trixie-slim

ARG PHP_VERSION=7.4
# Revisi paket minimum yang sudah membawa backport di atas.
# Build sengaja digagalkan jika apt memasang versi yang lebih lama.
ARG PHP_MIN_PKG_VERSION=7.4.33-37

ENV DEBIAN_FRONTEND=noninteractive

# Tambahkan repositori Sury, lalu install Apache + mod_php + ekstensi PHP
RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends ca-certificates curl; \
    curl -fsSLo /tmp/debsuryorg-archive-keyring.deb https://packages.sury.org/debsuryorg-archive-keyring.deb; \
    dpkg -i /tmp/debsuryorg-archive-keyring.deb; \
    rm -f /tmp/debsuryorg-archive-keyring.deb; \
    . /etc/os-release; \
    echo "deb [signed-by=/usr/share/keyrings/debsuryorg-archive-keyring.gpg] https://packages.sury.org/php/ ${VERSION_CODENAME} main" \
        > /etc/apt/sources.list.d/php.list; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        apache2 \
        libapache2-mod-php${PHP_VERSION} \
        php${PHP_VERSION}-cli \
        php${PHP_VERSION}-bcmath \
        php${PHP_VERSION}-curl \
        php${PHP_VERSION}-gd \
        php${PHP_VERSION}-json \
        php${PHP_VERSION}-mbstring \
        php${PHP_VERSION}-mysql \
        php${PHP_VERSION}-opcache \
        php${PHP_VERSION}-xml \
        php${PHP_VERSION}-zip \
        git \
        unzip \
        default-mysql-client; \
    for pkg in libapache2-mod-php${PHP_VERSION} php${PHP_VERSION}-cli; do \
        installed="$(dpkg-query -W -f='${Version}' "$pkg")"; \
        if ! dpkg --compare-versions "$installed" ge "$PHP_MIN_PKG_VERSION"; then \
            echo "ERROR: $pkg $installed lebih lama dari $PHP_MIN_PKG_VERSION (belum membawa patch PHP2Shell)" >&2; \
            exit 1; \
        fi; \
    done; \
    apt-get purge -y --auto-remove curl; \
    rm -rf /var/lib/apt/lists/* /var/www/html/index.html

# Apache: mod_php butuh MPM prefork, aktifkan mod_rewrite untuk routing Laravel,
# dan arahkan log Apache ke stdout/stderr container
RUN set -eux; \
    a2dismod -q mpm_event mpm_worker || true; \
    a2enmod -q mpm_prefork php${PHP_VERSION} rewrite; \
    echo 'ServerName localhost' > /etc/apache2/conf-available/servername.conf; \
    a2enconf -q servername; \
    ln -sfT /dev/stderr /var/log/apache2/error.log; \
    ln -sfT /dev/stdout /var/log/apache2/access.log; \
    ln -sfT /dev/stdout /var/log/apache2/other_vhosts_access.log

# Konfigurasi Apache DocumentRoot ke folder public (+ hardening PHP2Shell)
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Script untuk menjalankan Apache di foreground (pengganti apache2-foreground
# bawaan image resmi php:*-apache)
COPY docker/apache2-foreground /usr/local/bin/apache2-foreground
RUN chmod +x /usr/local/bin/apache2-foreground

# Salin Composer binary versi 2.2 LTS (kompatibel penuh dengan PHP 7.4)
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Salin source code project
COPY . /var/www/html

# Validasi konfigurasi Apache (termasuk hardening di docker/apache.conf)
RUN apache2ctl configtest

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
