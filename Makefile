full-commands:
	docker exec -w /var/www/html/ecommerce php8 php bin/magento setup:upgrade
	docker exec -w /var/www/html/ecommerce php8 php bin/magento setup:di:compile
	docker exec -w /var/www/html/ecommerce php8 php bin/magento setup:static-content:deploy -f
	docker exec -w /var/www/html/ecommerce php8 php bin/magento indexer:reindex
	docker exec -w /var/www/html/ecommerce php8 php bin/magento cache:flush

cache-flush:
	docker exec -w /var/www/html/ecommerce php8 php bin/magento cache:flush

upgrade:
	docker exec -w /var/www/html/ecommerce php8 php bin/magento setup:upgrade

compile:
	docker exec -w /var/www/html/ecommerce php8 php bin/magento setup:di:compile
	docker exec -w /var/www/html/ecommerce php8 php bin/magento cache:flush

reindex:
	docker exec -w /var/www/html/ecommerce php8 php bin/magento indexer:reindex

deploy:
	docker exec -w /var/www/html/ecommerce php8 php bin/magento setup:static-content:deploy -f
	docker exec -w /var/www/html/ecommerce php8 php bin/magento cache:flush

