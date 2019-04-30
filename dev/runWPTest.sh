#!/bin/bash

verbose=""

if [ "$1" == "-v" ]; then
	verbose="--verbose"
fi

# Command used to run a suite of Docker tests
docker-compose run --rm wordpress_phpunit phpunit $verbose --configuration phpunit.xml.dist
