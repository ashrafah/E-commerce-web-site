🛒 Shop Web Application (PHP Project)
📌 Project Overview
This is a group web development project developed using PHP.
The system is an online shopping platform that allows users to register, login, browse products, manage cart, and complete payments.
It also includes a powerful Admin Panel for managing the entire system.
________________________________________
🛠️ Technologies Used
•	HTML
•	CSS
•	PHP
•	Bootstrap
________________________________________
📂 Project Setup Instructions
1️. Move Project Folder
Copy the project folder:
shop
into:
C:\xampp\htdocs
________________________________________
2️. Import Database
•	Open phpMyAdmin
•	Create a database named:
shop
•	Import the SQL file:
shop.sql
________________________________________
3️. Start Server
Start XAMPP Control Panel:
•	Apache ✔
•	MySQL ✔
________________________________________
4️. Configure Database Connection
Update credentials in:
C:\xampp\htdocs\shop\db\db.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop";
________________________________________
👤 User Features
•	User Registration
•	User Login
•	Session Management (Login & Logout)
•	Product Browsing
•	Product Search
•	Categories System
•	New Arrivals Section
•	Offers Section
•	Add to Cart
•	Checkout & Payment System
________________________________________
🛠️ Admin Features
🔐 Admin Login Credentials
Email: admin@gmail.com  
Password: admin123
📊 Admin Dashboard Includes:
•	View Products
•	Add Products
•	Edit Products
•	Delete Products
•	View Categories
•	Add Categories
•	Edit Categories
•	Delete Categories
•	Manage Users
•	View Earnings / Orders
________________________________________
💳 Payment System
•	Integrated with PayHere Sandbox
•	Used for testing payment flow
⚠️ Note:
•	Project is not hosted online
•	Payment success redirect requires static return URL
•	Sandbox environment limitations apply
________________________________________
📧 Email System
•	Uses PHPMailer for sending emails
•	Used for notifications and system messages
Setup:
•	Check “PHP Mailer” folder
•	Configure SMTP settings as required
________________________________________
🔐 Security Features
•	Session-based authentication system
•	Session created on login
•	Session destroyed on logout
⚠️ Note:
•	Password hashing is NOT used (for learning purposes only)
________________________________________
🚀 How to Run Project
1.	Start XAMPP
2.	Start:
o	Apache
o	MySQL
3.	Open browser
4.	Visit:
http://localhost/shop
________________________________________
📌 Project Modules
•	Authentication System
•	Product Management
•	Category Management
•	Cart System
•	Payment System
•	Admin Dashboard
•	Email Notification System
________________________________________
⚠️ Important Notes
•	This project is developed for educational purposes
•	Not production-ready
•	Security improvements required for real deployment
________________________________________
🚀 Future Improvements
•	Password hashing (bcrypt)
•	JWT or improved session security
•	Live deployment
•	Better payment redirect handling
•	UI/UX improvements
•	API-based architecture
________________________________________
📄 License
This project is for academic/educational use only.
________________________________________
