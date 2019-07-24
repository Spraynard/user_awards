#!/bin/bash

# Scaffolding Script used to scaffold our docker workflow. This pretty much gives us a complete set up
# which will allow us to start running tests with WordPress loaded into memory.

# This information could possibly be moved to a config file maybe?
DB_NAME=somewordpress
DB_USER=wordpress
DB_PASS=somewordpresspassword
DB_HOST=db
PLUGIN_NAME=user_awards

ABS_PATH="$(cd $(dirname $0) && pwd)"

echo "Starting Docker Containers"
# Spin up our docker servers
docker-compose up -d

# echo "Creating folders"
docker-compose exec wordpress mkdir /var/www/html/wp-content/uploads
docker-compose exec wordpress chmod 755 /var/www/html/wp-content/uploads
docker-compose exec wordpress chown -R www-data:www-data /var/www

# Host port obtained by pattern matching the port that comes from docker output
HOST_PORT=$(docker-compose port wordpress 80 | awk -F : '{printf $2}')

echo "Waiting until our servers are available and all spun up"
# We want to ensure that we're getting a response from the wordpress server before we continue on with the setup
until $(curl -L http://localhost:$HOST_PORT -so - 2>&1 | grep -q "WordPress"); do
	echo -n '.'
	sleep 5
done
echo ''

echo "Server Running at http://localhost:$HOST_PORT"
echo "---------------------------------------------"
echo "Installing Core WordPress Files on our docker container"
# Install wordpress files on our server through CLI

docker-compose run --user="33:33" --rm cli wp core install --url=localhost:8080 \
--title=Example --admin_user=test_admin --admin_email=test@admin.com --admin_password=testerino

# Update our current file system with the wp scaffold items if the information is not available
if [ ! -f $(dirname "$ABS_PATH")/bin/install-wp-tests.sh ]; then
	echo "Scaffolding our plugin tests since no files are apparent in your project folder"
	docker-compose run --rm cli wp scaffold plugin-tests $PLUGIN_NAME >/dev/null
fi

echo "Running \"install-wp-tests.sh\""

# Install Wp Tests
docker-compose run --rm wordpress_phpunit /app/bin/install-wp-tests.sh somewordpress wordpress somewordpresspassword db >/dev/null

echo "Activating plugin"
docker-compose run --user="33:33" --rm cli wp plugin activate user_awards >/dev/null

# Should only really be one file in there, but I don't know the name of item
echo "Checking to see if there is any wordpress export data"
for filename in $(dirname "$ABS_PATH")/backups/wp/*.xml; do
	echo "Backup seen. We're about to import the backup data"
	docker-compose run --user="33:33" --rm cli wp plugin install wordpress-importer --activate
	docker-compose run --user="33:33" --rm cli wp import /backups/wp/"${filename##*/}" --authors="skip"
	break
done

# Copy our test page over for testing purposes, over to the twentynineteen theme folder on our docker image.
docker cp $(dirname "$ABS_PATH")/web_tests/page-example-test.php "$(docker-compose ps -q wordpress)":/var/www/html/wp-content/themes/twentynineteen

echo "All done, should be able to run commands like"
echo "docker-compose run --rm wordpress_phpunit phpunit --configuration phpunit.xml.dist"
echo "to test your files"