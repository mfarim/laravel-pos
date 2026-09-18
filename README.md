<h1 align="center">🛍️ Point of Sale (POS) Toko Aisyah</h1>

<p align="center">
  A full-featured web-based Point of Sale (POS) and store management system built with Laravel 5.5. Designed to manage product inventory, stock levels, suppliers, purchasing & sales transactions, goods returns, inventory balance cards, cash flow ledgers, and interactive sales analytics.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-5.5-red.svg" alt="Laravel 5.5">
  <img src="https://img.shields.io/badge/PHP-7.4-blue.svg" alt="PHP 7.4">
  <img src="https://img.shields.io/badge/Docker-Ready-2496ED.svg" alt="Docker Ready">
  <img src="https://img.shields.io/badge/Database-MySQL%20%2F%20MariaDB-orange.svg" alt="Database">
  <img src="https://img.shields.io/badge/CI-GitHub%20Actions-2088FF?logo=github-actions&logoColor=white" alt="CI">
  <img src="https://img.shields.io/badge/Status-Fully%20Seeded-success.svg" alt="Seeded">
</p>

---

## 📑 Table of Contents
1. [Key Features](#-key-features)
2. [Easiest Setup Guide (Docker - Recommended)](#-easiest-setup-guide-using-docker)
3. [Default Login Credentials](#-default-login-credentials)
4. [Manual Setup Guide (Without Docker)](#-manual-setup-guide-without-docker)
5. [Useful Commands](#-useful-commands)
6. [Frequently Asked Questions & Troubleshooting](#-frequently-asked-questions--troubleshooting-faq)
7. [Screenshots](#-screenshots)

---

## ✨ Key Features

- 🛒 **POS Cashier (Sales)**: Fast barcode/SKU lookup, instant price & discount calculation, and receipt printing.
- 📦 **Purchasing Transactions**: Log inbound inventory from suppliers, print purchase orders, and update stock levels automatically.
- 🏷️ **Product Catalog**: Manage item names, categories, units of measurement, purchase prices, selling prices, and discounts.
- 🚚 **Supplier Management**: Keep complete supplier contact and company profiles.
- 🔄 **Returns System**: Dedicated workflows for customer sales returns and supplier purchase returns.
- 📊 **Inventory Balance Cards**: Real-time tracking of stock in, stock out, and remaining quantities per SKU.
- 💵 **Cash Flow Ledger (Buku Kas)**: Automatically logs cash receipts (sales debits) and expense disbursements (purchase credits).
- 📈 **Sales Analytics & Charts**: Visual yearly sales performance and revenue charts.
- 👥 **Role-Based Access Control**: Strict role separation between **Store Owner (Pemilik)** and **Cashier (Penjaga Toko)**.

---

## 🚀 Easiest Setup Guide (Using Docker)

> **💡 Why Docker?**
> Laravel 5.5 requires PHP 7.4. If your computer runs a modern PHP version (PHP 8.x), the application cannot run directly on your host machine without encountering backwards-compatibility errors. With **Docker**, you **do not need to install PHP 7 or MySQL locally**. With a single command, the application, dependencies, database, migrations, and seed data will be configured and launched automatically!

### Prerequisites
Make sure a Docker engine is installed and running on your system:
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (Windows / macOS) or [OrbStack](https://orbstack.dev/) (macOS).

### Steps:

1. **Open your Terminal / Command Prompt** and navigate to this project folder:
   ```bash
   cd laravel-pos
   ```

2. **Start the containers with one command**:
   ```bash
   docker compose up -d
   ```
   *(Or `docker-compose up -d` if using legacy Docker Compose)*

3. **Wait for the automated initialization** (~1–2 minutes on first run).
   The container entrypoint will automatically:
   - Generate `.env` from `.env.example`
   - Connect to the MariaDB database service
   - Install Composer dependencies
   - Generate the Application Key (`APP_KEY`)
   - Run all database migrations
   - Populate initial demo data (users, categories, products, transactions)

4. **Open your web browser and navigate to**:
   👉 [**http://localhost:8000**](http://localhost:8000)

---

## 🔑 Default Login Credentials

Use either of the following accounts to sign in:

| Role | Username | Password | Core Privileges |
| :--- | :--- | :--- | :--- |
| **Store Owner** *(Pemilik)* | `admin` | `AdminAi123` | Full administrative access: Sales Reports, Cash Ledger, Inventory Cards, Supplier Directory, Staff Management, Purchases, and Sales Analytics. |
| **Store Cashier** *(Penjaga Toko)* | `penjaga` | `member123` | Operational access: Cashier Terminal (Sales), Product Master, Categories, Units, Goods Returns, and Sales Reports. |

---

## 💻 Manual Setup Guide (Without Docker)

If you prefer running the application directly on your host machine without Docker, ensure your environment meets the following requirements:
- **PHP 7.1 to 7.4** (with extensions: `pdo_mysql`, `mbstring`, `gd`, `zip`, `xml`, `bcmath`, `json`)
- **Composer 2.2 LTS**
- **MySQL or MariaDB Server**

### Steps:

1. **Copy the environment configuration**:
   ```bash
   cp .env.example .env
   ```

2. **Configure your database connection in `.env`**:
   Open `.env` and set your local database credentials:
   ```ini
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pos_aisyah
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   *(Ensure you have created the `pos_aisyah` database in MySQL/phpMyAdmin first)*.

3. **Install Composer dependencies**:
   ```bash
   composer install
   ```

4. **Generate the application key**:
   ```bash
   php artisan key:generate
   ```

5. **Run database migrations and seed demo data**:
   ```bash
   php artisan migrate --seed
   ```

6. **Start the local PHP server**:
   ```bash
   php artisan serve
   ```

7. Open your browser at [http://localhost:8000](http://localhost:8000).

---

## 🛠️ Useful Commands

### 1. View Container Logs (Docker)
Monitor background startup progress or application output:
```bash
docker compose logs -f app
```

### 2. Reset Database & Re-seed Demo Data
Wipe all database changes and restore default seed data:
- **With Docker**:
  ```bash
  docker compose exec app php artisan migrate:fresh --seed
  ```
- **Manual / Local**:
  ```bash
  php artisan migrate:fresh --seed
  ```

### 3. Stop Docker Containers
When you are done using the application:
```bash
docker compose down
```

---

## ❓ Frequently Asked Questions & Troubleshooting (FAQ)

<details>
<summary><b>1. Port 8000 is already in use by another application?</b></summary>
<br>
Open <code>docker-compose.yml</code> and locate the port mapping:
<pre><code>ports:
  - "8000:80"</code></pre>
Change the host port <code>8000</code> to another available port, such as <code>8080:80</code>. Re-run <code>docker compose up -d</code> and access the app at <code>http://localhost:8080</code>.
</details>

<details>
<summary><b>2. "docker: command not found" or Docker daemon is not running?</b></summary>
<br>
Ensure that <b>Docker Desktop</b> (or <b>OrbStack</b> on macOS) is launched and actively running in the background before running commands in the terminal.
</details>

<details>
<summary><b>3. Login failed or invalid credentials?</b></summary>
<br>
Make sure you are logging in with the <b>Username</b> (not email):
- Owner Username: <code>admin</code> (Password: <code>AdminAi123</code>)
- Cashier Username: <code>penjaga</code> (Password: <code>member123</code>)
</details>

<details>
<summary><b>4. How to customize the store name and receipt header?</b></summary>
<br>
Store information and receipt headers are stored in the <code>config</code> table (keys: <code>store_name</code> and <code>store_description</code>). You can edit them directly in the database or customize them in <code>database/seeds/ConfigSeeder.php</code>.
</details>

---

## 📸 Screenshots

### 1. Login Page
![Login Page](screenshot/halaman-login.png)

### 2. Cashier Terminal (Sales)
![Cashier Terminal](screenshot/penjaga-transaksi-penjualan.png)

### 3. Purchasing & Restock
![Purchasing](screenshot/pemilik-transaksi-pembelian.png)

### 4. Product Master Data
![Product Data](screenshot/penjaga-data-barang.png)

### 5. Sales Analytics & Revenue Chart
![Sales Chart](screenshot/penjaga-grafik-penjualan.png)

---

<p align="center">
  Crafted with ❤️ for streamlined retail store operations.
</p>
