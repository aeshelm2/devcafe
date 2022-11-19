build-project:
	docker-compose up -d --build
	docker exec -w /var/www/html/ php8 composer config http-basic.repo.magento.com 40f2785da4317e669aa324d0a2179286 2871b6327e6ad508952a4765a4a6aa96
	docker exec -w /var/www/html/ php8 composer create-project --repository-url=https://repo.magento.com/ magento/project-community-edition ecommerce
	docker exec -w /var/www/html/ecommerce php8 php bin/magento setup:install --base-url="http://ecommerce.local" --db-host="db" --db-name="ecommerce" --db-user="root" --db-password="ecomadmin" --admin-firstname="admin" --admin-lastname="admin" --admin-email="user@example.com" --admin-user="admin" --admin-password="admin123" --language="en_US" --currency="USD" --timezone="America/Chicago" --use-rewrites="1" --backend-frontname="admin" --search-engine=elasticsearch7 --elasticsearch-host="elasticsearch" --elasticsearch-port=9200
	