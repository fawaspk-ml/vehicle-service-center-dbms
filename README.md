🚗 GarageOps – Vehicle Service Center Management System

GarageOps is a web-based vehicle service center management system developed using PHP and MySQL.
The system digitizes customer, vehicle, and service records while enabling secure authentication, role-based access control, and automated invoice generation.

It replaces manual record keeping with a structured relational database system, improving efficiency, organization, and service tracking for vehicle workshops.

The interface design is inspired by the Benny’s Original Motor Works garage theme, providing a workshop-style experience while maintaining a professional backend architecture.

📌 Features
👤 User Authentication

Secure login system using PHP sessions

Password hashing for secure credential storage

Role-based access control (Admin / Staff)

🚘 Customer & Vehicle Management

Register and manage customer information

Store multiple vehicles per customer

Indian vehicle number validation

🛠 Service Management

Add and manage service types and pricing

Dynamic service selection for service records

📋 Service Record Tracking

Record service history for each vehicle

Retrieve integrated records using SQL JOIN queries

🧾 Automatic Invoice Generation

Generate service invoices dynamically from stored records

🔍 Search & Filtering

Search service records by:

Customer name

Phone number

Vehicle number

Brand

Model

Service type

🔒 Security Improvements

Prepared statements to prevent SQL injection

Input validation for all forms

Admin-only record deletion

🛠 Technologies Used
Frontend

HTML

CSS

Backend

PHP

Database

MySQL

Server Environment

XAMPP (Apache + MySQL)

Development Tools

VS Code

phpMyAdmin

🗄 Database Design

The system uses a normalized relational database (3NF) to eliminate redundancy and maintain data integrity.

Tables

customers

customer_id (Primary Key)

name

phone

address

vehicles

vehicle_id (Primary Key)

customer_id (Foreign Key)

vehicle_number

vehicle_type

brand

model

services

service_id (Primary Key)

service_name

service_cost

service_records

record_id (Primary Key)

vehicle_id (Foreign Key)

service_id (Foreign Key)

service_date

users

user_id (Primary Key)

username

password_hash

role

created_at

Relationships

One customer → many vehicles

One vehicle → many service records

One service → many service records

📂 Project Structure
garageops/
│
├── index.php
├── login.php
├── dashboard.php
├── db.php
│
├── customers/
├── vehicles/
├── services/
├── invoices/
│
├── css/
├── images/
│
├── screenshots/
│   ├── login.png
│   ├── dashboard.png
│   ├── records.png
│   └── invoice.png
│
├── create_admin.php
├── create_staff.php
│
└── database/
    └── vehicle_service_db.sql
📷 Screenshots
Login Page

Dashboard

Service Records

Invoice Generation

⚙️ How to Run the Project
1️⃣ Clone the Repository
git clone https://github.com/fawaspk-ml/vehicle-service-center-dbms.git
2️⃣ Move Project to XAMPP

Copy the project folder to:

xampp/htdocs/

Example:

xampp/htdocs/vehicle_service
3️⃣ Start the Server

Open XAMPP Control Panel and start:

Apache

MySQL

4️⃣ Import the Database

Open phpMyAdmin:

http://localhost/phpmyadmin

Create database:

vehicle_service_db

Import the provided SQL file.

5️⃣ Run the Project

Open browser:

http://localhost/vehicle_service
👤 Initial User Setup

For first-time login, run the included setup scripts to create default users.

Create Admin

Open in browser:

http://localhost/vehicle_service/create_admin.php

This will create the Admin account.

Create Staff

Open in browser:

http://localhost/vehicle_service/create_staff.php

This will create the Staff account.

🔑 Default Login Credentials
Admin

Username: admin
Password: admin123

Staff

Username: staff
Password: staff123

⚠️ After creating the users, delete create_admin.php and create_staff.php for security.

🧪 Role-Based Access
Admin

Add records

Edit records

Delete records

Generate invoices

Staff

Add records

Edit records

Generate invoices

Cannot delete records

📈 Future Improvements

Online service booking system

Mechanic management module

Payment gateway integration

Service analytics dashboard

Email or SMS service reminders

📄 License

This project is developed for educational purposes.

👤 Author

Muhammed Fawas P K
Artificial Intelligence & Machine Learning Student

GitHub:
https://github.com/fawaspk-ml
