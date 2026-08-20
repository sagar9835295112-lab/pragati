# Online admission setup

The admission form uses PHP and MySQL. Opening the HTML file directly will display the form, but saving submissions requires a PHP server.

## XAMPP setup

1. Install XAMPP with Apache, PHP, and MySQL.
2. Copy this project folder into `C:\xampp\htdocs\pragatishil-classes`.
3. Start Apache and MySQL from the XAMPP Control Panel.
4. Open `http://localhost/phpmyadmin`.
5. Select **Import**, choose `database.sql`, and run it.
6. Open `http://localhost/pragatishil-classes/h2.html`.
7. Submit the form from **Enquire now** or **Apply online**.

The default connection in `submit_admission.php` expects MySQL user `root` with an empty password, which is the default XAMPP setup. Change the four connection values at the top of that file if your MySQL credentials differ.

## Stored data

Applications are saved in the `pragatishil_classes.admissions` table. You can view them in phpMyAdmin by opening the database and selecting the `admissions` table.
