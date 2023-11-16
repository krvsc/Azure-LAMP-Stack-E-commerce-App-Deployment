# Azure LAMP Stack E-commerce App Deployment

## Introduction
This is a sample e-commerce application built for learning purposes.

The deployment instructions provided here are specific to an Azure cloud server running Ubuntu Linux.

## Deploy Pre-Requisites

>**Create an Azure Virtual Machine**

1. Log in to the Azure portal.
2. Navigate to the Virtual Machines section and create a new Ubuntu VM.
3. Follow the on-screen instructions to configure the VM.

>**Connect to the Azure VM**

Connect to the Azure VM using SSH:
``` 
#bash

ssh azureuser@<your-vm-ip-address>
```
**NOTE:** You may also refer to connect blade in the sidebar of azure vm there you will find everything what you need.

>**Update the System**
```
#bash

sudo apt update
sudo apt upgrade -y
```

## Deploy and Configure Database

>**Install MariaDB**
```
#bash

sudo apt install -y mariadb-server
sudo systemctl start mariadb
sudo systemctl enable mariadb
```
>**Configure MariaDB**
```
#bash

sudo mysql
```
```
#sql

CREATE DATABASE ecomdb;
CREATE USER 'ecomuser'@'localhost' IDENTIFIED BY 'ecompassword';
GRANT ALL PRIVILEGES ON *.* TO 'ecomuser'@'localhost';
FLUSH PRIVILEGES;
```

**NOTE:** There are a two points you may want to consider to configurartion of mysql database in another way:

>>1.Security Best Practices:

It's generally a good practice to provide only the necessary privileges to the user. Instead of granting all privileges on *.*, you might want to specify the exact privileges needed for the ecomdb. 

**Example:** If the user only needs to perform SELECT, INSERT, UPDATE, and DELETE operations, you can grant those specific privileges:
```
#sql

GRANT SELECT, INSERT, UPDATE, DELETE ON ecomdb.* TO 'ecomuser'@'localhost';
```

>>2.Database Host:

If you plan to deploy the application on a multi-node setup, where the database server is on a different machine, you would need to replace 'localhost' with the actual hostname or IP address of the database server. 

In your current setup, it's configured for a single-node setup.

Example: if the database is on a separate machine with IP address 172.20.1.101, the SQL commands would be:
```
#sql

CREATE DATABASE ecomdb;
CREATE USER 'ecomuser'@'172.20.1.101' IDENTIFIED BY 'ecompassword';
GRANT ALL PRIVILEGES ON ecomdb.* TO 'ecomuser'@'172.20.1.101';
FLUSH PRIVILEGES;
```

## Load Product Inventory Information to Database
Create the db-load-script.sql file:
```
#bash

cat > db-load-script.sql 
USE ecomdb;
CREATE TABLE products (
  id mediumint(8) unsigned NOT NULL auto_increment,
  Name varchar(255) default NULL,
  Price varchar(255) default NULL,
  ImageUrl varchar(255) default NULL,
  PRIMARY KEY (id)
) AUTO_INCREMENT=1;

INSERT INTO products (Name, Price, ImageUrl) VALUES
  ("Laptop", "100", "c-1.png"),
  ("Drone", "200", "c-2.png"),
  ("VR", "300", "c-3.png"),
  ("Tablet", "50", "c-5.png"),
  ("Watch", "90", "c-6.png"),
  ("Phone Covers", "20", "c-7.png"),
  ("Phone", "80", "c-8.png"),
  ("Laptop", "150", "c-4.png");
```
Run the SQL script:
```
#bash

sudo mysql < db-load-script.sql
```
**NOTE**: After these steps you can check whether data is stored in db or not by running the following commands:
```
#bash
sudo mysql

#sql
show databases;
use ecomdb;
select * from products;
```
If you are able to see table of your products then well done!🎉 You have done it 👏 otherwise go back and load inventory.🤗

## Deploy and Configure Web 🙌
>**Install Required Packages**
```
#bash
sudo apt install -y apache2 php libapache2-mod-php php-mysql
```
>**Configure Apache**

Change DirectoryIndex index.html to DirectoryIndex index.php:
```
#bash

sudo sed -i 's/index.html/index.php/g' /etc/apache2/apache2.conf
```
>**Start Apache**
```
#bash

sudo systemctl start apache2
sudo systemctl enable apache2
```
>**Download Code**
```
#bash
sudo apt install -y git
sudo git clone https://github.com/krvsc/Linux2Devops.git /var/www/html/
```