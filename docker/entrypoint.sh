#!/bin/bash
set -e

echo "=========================================================="
echo "      Memulai Container POS Toko Aisyah (Laravel 5.5)     "
echo "=========================================================="

cd /var/www/html

# 1. Cek dan buat file .env jika belum ada
if [ ! -f .env ]; then
    echo "[INFO] File .env belum ada, menyalin dari .env.example..."
    cp .env.example .env
    
    # Sesuaikan konfigurasi default untuk Docker
    sed -i 's/DB_HOST=.*/DB_HOST=db/' .env
    sed -i 's/DB_PORT=.*/DB_PORT=3306/' .env
    sed -i 's/DB_DATABASE=.*/DB_DATABASE=pos_aisyah/' .env
    sed -i 's/DB_USERNAME=.*/DB_USERNAME=pos_user/' .env
    sed -i 's/DB_PASSWORD=.*/DB_PASSWORD=pos_password/' .env
    sed -i 's|APP_URL=.*|APP_URL=http://localhost:8000|' .env
fi

# 2. Cek vendor / composer install jika belum terinstall
if [ ! -f vendor/autoload.php ]; then
    echo "[INFO] Folder vendor belum ada, menjalankan composer install..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# 3. Generate APP_KEY jika masih kosong
if grep -q "APP_KEY=$" .env || grep -q "APP_KEY=\"\"" .env || ! grep -q "APP_KEY=" .env; then
    echo "[INFO] Mengenerate application key..."
    php artisan key:generate --force
fi

# 4. Tunggu service database MariaDB/MySQL siap
echo "[INFO] Menunggu database MySQL/MariaDB siap terhubung..."
max_retries=30
count=0
until php -r "try { new PDO('mysql:host=' . (getenv('DB_HOST') ?: 'db') . ';port=' . (getenv('DB_PORT') ?: 3306) . ';dbname=' . (getenv('DB_DATABASE') ?: 'pos_aisyah'), (getenv('DB_USERNAME') ?: 'pos_user'), (getenv('DB_PASSWORD') ?: 'pos_password')); exit(0); } catch (Exception \$e) { exit(1); }" > /dev/null 2>&1; do
    count=$((count + 1))
    if [ $count -gt $max_retries ]; then
        echo "[ERROR] Gagal terhubung ke database setelah $max_retries percobaan."
        exit 1
    fi
    echo "  -> Mencoba menghubungkan ke database ($count/$max_retries)..."
    sleep 2
done
echo "[SUCCESS] Koneksi database berhasil!"

# 5. Jalankan migrasi dan seeder
echo "[INFO] Menjalankan migrasi database..."
php artisan migrate --force

echo "[INFO] Menjalankan seeder database (data pengguna, barang, transaksi)..."
php artisan db:seed --force

# 6. Bersihkan cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 7. Pastikan hak akses folder storage dan cache
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "=========================================================="
echo "  Aplikasi Siap! Buka browser di: http://localhost:8000   "
echo "  Login Pemilik : admin   / AdminAi123                    "
echo "  Login Kasir   : penjaga / member123                     "
echo "=========================================================="

# Jalankan perintah utama container (misalnya apache2-foreground)
exec "$@"
