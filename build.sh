#!/bin/bash
docker-compose up -d --build
sudo chown $USER:$USER -R project
docker exec -w /var/www/html/ecommerce php8 composer update
docker exec -w /var/www/html/ecommerce php8 php bin/magento setup:install --base-url="http://ecommerce.local" --db-host="db" --db-name="ecommerce" --db-user="root" --db-password="ecomadmin" --admin-firstname="admin" --admin-lastname="admin" --admin-email="user@example.com" --admin-user="admin" --admin-password="admin123" --language="en_US" --currency="USD" --timezone="America/Chicago" --use-rewrites="1" --backend-frontname="admin" --search-engine=elasticsearch7 --elasticsearch-host="elasticsearch" --elasticsearch-port=9200
docker exec -w /var/www/html/ecommerce php8 php bin/magento module:disable Magento_TwoFactorAuth