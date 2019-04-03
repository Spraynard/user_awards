#!/bin/bash

# Scaffolding Script used to scaffold our docker workflow. This pretty much gives us a complete set up
# which will allow us to start running tests with WordPress loaded into memory.

# This information could possibly be moved to a config file maybe?
DB_NAME=somewordpress
DB_USER=wordpress
DB_PASS=somewordpresspassword
DB_HOST=db
PLUGIN_NAME=wp_awards

echo "Starting Docker Containers"
# Spin up our docker servers
docker-compose up -d

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
docker-compose run --rm cli wp core install --url=example \
--title=test-example --admin_user=kellan --admin_email=kellan.martin@gmail.com >/dev/null

# Update our current file system with the wp scaffold items if the information is not available
if [ ! -f ./bin/install-wp-tests.sh ]; then
	echo "Scaffolding our plugin tests since no files are apparent in your project folder"
	docker-compose run --rm cli wp scaffold plugin-tests $PLUGIN_NAME >/dev/null
fi

echo "Running \"install-wp-tests.sh\""

# Install Wp Tests
docker-compose run --rm wordpress_phpunit /app/bin/install-wp-tests.sh somewordpress wordpress somewordpresspassword db >/dev/null

echo "All done, should be able to run commands like"
echo "docker-compose run --rm wordpress_phpunit phpunit --configuration phpunit.xml.dist"
echo "to test your files"


