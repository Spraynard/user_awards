#!/bin/bash

# Command used to run a suite of Docker tests

docker-compose run --rm wordpress_phpunit phpunit --configuration phpunit.xml.dist
