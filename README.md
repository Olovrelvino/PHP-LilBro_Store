# Lil Bro Store

Lil Bro Store is a simple e-commerce website for beverage products, built using **native PHP**, **MySQL**, **HTML/CSS**, and **Apache**. This project can be run locally using XAMPP, Laragon, or another local PHP development environment.

> **Note:** This README is written for local/demo use. For a real alcohol-commerce deployment, make sure the application follows applicable age-verification, licensing, delivery, payment, and other legal requirements.

## Tech Stack

- PHP (Native PHP, no framework)
- MySQL
- HTML5
- CSS3
- Apache Web Server
- phpMyAdmin (for database management)

## Project Structure

The repository currently contains files/folders similar to the following:

```text
Lil-Bro-Store/
├── css/
├── images/
├── uploads/
├── addProduct.php
├── admin.php
├── cart.php
├── connect.php
├── function.php
├── history.php
├── home.php
├── login.php
├── managePayment.php
├── orderDetails.php
├── register.php
├── shop.php
└── userOrders.php
```

## Requirements

Before running the project, install one of the following local server environments:

### Option 1 — XAMPP

Download and install XAMPP with:

- Apache
- MySQL
- PHP
- phpMyAdmin

### Option 2 — Laragon

Laragon can also be used because it provides Apache/Nginx, PHP, and MySQL in one local environment.

## How to Run Locally

### 1. Download or Clone the Repository

Clone the repository:

```bash
git clone <YOUR-GITHUB-REPOSITORY-URL>
```

Or download the repository as a ZIP file from GitHub and extract it.

### 2. Move the Project to the Local Web Server Folder

For **XAMPP**, move the project folder into:

```text
C:\xampp\htdocs\
```

For example:

```text
C:\xampp\htdocs\Lil-Bro-Store\
```

For **Laragon**, place the project inside:

```text
C:\laragon\www\
```

For example:

```text
C:\laragon\www\Lil-Bro-Store\
```

### 3. Start Apache and MySQL

Open the XAMPP Control Panel and click:

- **Start** → Apache
- **Start** → MySQL

Make sure both services are running before opening the website.

### 4. Create the MySQL Database

Open phpMyAdmin:

```text
http://localhost/phpmyadmin
```

Create a new database. For example:

```text
lil_bro_store
```

> Use the database name expected by your `connect.php` configuration if it is different.

### 5. Import the Database

If the repository contains an SQL database file, import it into the database you created.

In phpMyAdmin:

1. Open the `lil_bro_store` database.
2. Select the **Import** tab.
3. Choose the project's `.sql` file.
4. Click **Import** / **Go**.
5. Wait until phpMyAdmin confirms that the import was successful.

If the repository does not contain an SQL file, you will need to create the required tables manually based on the queries used by the PHP files.

### 6. Configure the Database Connection

Open:

```text
connect.php
```

Check the database connection settings. A typical native PHP MySQL connection looks like this:

```php
<?php
$conn = mysqli_connect("localhost", "root", "", "lil_bro_store");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
```

Update the following values if necessary:

- `localhost` → your MySQL host
- `root` → your MySQL username
- `""` → your MySQL password
- `lil_bro_store` → your database name

Do not commit real production database passwords to GitHub.

### 7. Check the Uploads Folder

The project contains an `uploads/` directory. This folder is commonly used to store uploaded product images or other files.

Make sure the directory exists and is writable by the local web server if the project uploads files during testing.

### 8. Open the Website

If your project folder is named `Lil-Bro-Store`, open:

```text
http://localhost/Lil-Bro-Store/
```

You can also open a specific page directly, for example:

```text
http://localhost/Lil-Bro-Store/home.php
```

Depending on how the project is structured, `home.php` may be the main customer page.

## Customer Flow

After the website is running locally, visitors can test the main shopping flow:

1. Open the **Home** page.
2. Register a new account through `register.php`.
3. Log in through `login.php`.
4. Browse products through `shop.php`.
5. Add products to the cart through `cart.php`.
6. Continue the order/checkout process.
7. View order history through `history.php` or `userOrders.php`.
8. Open order information through `orderDetails.php`.

> For an alcohol-related store, keep age verification and applicable local legal requirements in mind when turning the demo into a real service.

## Admin Flow

The repository also contains administrative pages, including:

- `admin.php` → admin dashboard
- `addProduct.php` → add products
- `managePayment.php` → manage payment/order status
- `orderDetails.php` → inspect order details

Use an administrator account or the authentication mechanism implemented in the project to access these pages.

## Common Problems

### `localhost` cannot be reached

Make sure Apache is running in XAMPP/Laragon.

### Database connection error

Check `connect.php` and verify:

- MySQL is running.
- The database name is correct.
- The username and password are correct.
- The required tables have been imported.

### `404 Not Found`

Make sure the project folder is inside the correct web-server directory:

```text
XAMPP  → C:\xampp\htdocs\
Laragon → C:\laragon\www\
```

Then check the URL and folder name.

### Images do not appear

Check that:

- `images/` and `uploads/` exist.
- The image paths in the PHP/HTML code are correct.
- Uploaded files are stored in the expected directory.

### CSS does not load

Check that the CSS files are inside the `css/` directory and that the paths used in the PHP pages match the actual folder structure.

## Recommended Local Testing Checklist

Before sharing the project with another developer, test these flows:

- [ ] Website opens from `localhost`.
- [ ] Database connection works.
- [ ] User registration works.
- [ ] User login/logout works.
- [ ] Products are displayed.
- [ ] Product images are displayed.
- [ ] Products can be added to the cart.
- [ ] Cart quantities and totals are correct.
- [ ] Orders can be created.
- [ ] Order history can be viewed.
- [ ] Admin pages work with the intended admin account.
- [ ] Product upload works.
- [ ] Payment/order management works.

## Notes for GitHub Visitors

To make this repository easier for other developers to run, it is recommended to include:

```text
├── README.md
├── database/
│   └── lil_bro_store.sql
├── css/
├── images/
├── uploads/
├── *.php
└── .gitignore
```

The most important addition is a database dump such as `lil_bro_store.sql`, because visitors need the database structure and seed data before the PHP application can work correctly.

## Disclaimer

Lil Bro Store is presented as a local/demo web application. Product availability, age restrictions, payments, delivery, and the sale of alcoholic beverages must be handled in accordance with the laws and regulations applicable to the deployment location.
