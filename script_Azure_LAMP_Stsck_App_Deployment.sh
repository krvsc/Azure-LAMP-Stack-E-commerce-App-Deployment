#!/bin/bash
#
# Automate Azure-LAMP-Stack-E-commerce Deployment
# Author: Vijay Kumar Singh

#######################################
# Print a message in a given color.
# Arguments:
#   Color. eg: green, red
#######################################
function print_color(){
  NC='\033[0m' # No Color

  case $1 in
    "green") COLOR='\033[0;32m' ;;
    "red") COLOR='\033[0;31m' ;;
    "*") COLOR='\033[0m' ;;
  esac

  echo -e "${COLOR} $2 ${NC}"
}

#######################################
# Check the status of a given service. If not active exit script
# Arguments:
#   Service Name. eg: firewalld, mariadb
#######################################
function check_service_status(){
  service_is_active=$(sudo systemctl is-active $1)

  if [ $service_is_active = "active" ]
  then
    echo "$1 is active and running"
  else
    echo "$1 is not active/running"
    exit 1
  fi
}


#######################################
# Check if a given item is present in an output
# Arguments:
#   1 - Output
#   2 - Item
#######################################
function check_item(){
  if [[ $1 = *$2* ]]
  then
    print_color "green" "Item $2 is present on the web page"
  else
    print_color "red" "Item $2 is not present on the web page"
  fi
}


echo "---------------- Setup Database Server ------------------"

# Install and configure Maria-DB
print_color "green" "Installing MariaDB Server.."
sudo apt update
sudo apt install -y mariadb-server

print_color "green" "Starting MariaDB Server.."
sudo systemctl start mariadb 
sudo systemctl enable mariadb

# Check FirewallD Service is running
check_service_status mariadb


# Configuring Database
print_color "green" "Setting up database.."
cat > setup-db.sql <<-EOF
  CREATE DATABASE ecomdb;
  CREATE USER 'ecomuser'@'20.244.117.210' IDENTIFIED BY 'ecompassword';
  GRANT ALL PRIVILEGES ON *.* TO 'ecomuser'@'localhost';
  FLUSH PRIVILEGES;
EOF

sudo mysql < setup-db.sql

# Loading inventory into Database
print_color "green" "Loading inventory data into database"
cat > db-load-script.sql <<-EOF
USE ecomdb;
CREATE TABLE products (id mediumint(8) unsigned NOT NULL auto_increment,Name varchar(255) default NULL,Price varchar(255) default NULL, ImageUrl varchar(255) default NULL,PRIMARY KEY (id)) AUTO_INCREMENT=1;

INSERT INTO products (Name,Price,ImageUrl) VALUES ("Laptop","100","c-1.png"),("Drone","200","c-2.png"),("VR","300","c-3.png"),("Tablet","50","c-5.png"),("Watch","90","c-6.png"),("Phone Covers","20","c-7.png"),("Phone","80","c-8.png"),("Laptop","150","c-4.png");

EOF

sudo mysql < db-load-script.sql

mysql_db_results=$(sudo mysql -e "use ecomdb; select * from products;")

if [[ $mysql_db_results == *Laptop* ]]
then
  print_color "green" "Inventory data loaded into MySQl"
else
  print_color "green" "Inventory data not loaded into MySQl"
  exit 1
fi


print_color "green" "---------------- Setup Database Server - Finished ------------------"

print_color "green" "---------------- Setup Web Server ------------------"

# Install web server packages
print_color "green" "Installing Web Server Packages .."
sudo apt install -y httpd php php-mysql



# Update index.php
sudo sed -i 's/index.html/index.php/g' /etc/httpd/conf/httpd.conf

# Start httpd service
print_color "green" "Start httpd service.."
sudo service httpd start
sudo systemctl enable httpd

# Download code
print_color "green" "Install GIT.."
sudo apt install -y git
sudo git clone https://github.com/krvsc/Azure-LAMP-Stack-E-commerce-App-Deployment.git /var/www/html/

print_color "green" "Updating index.php.."
sudo sed -i 's/172.20.1.101/20.244.117.210/g' /var/www/html/index.php

print_color "green" "---------------- Setup Web Server - Finished ------------------"

# Test Script
web_page=$(curl http://20.244.117.210)

for item in Laptop Drone VR Watch Phone
do
  check_item "$web_page" $item
done
