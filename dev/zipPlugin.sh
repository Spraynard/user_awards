#!/bin/bash

# This file is used to zip up our plugin in a package type setting.
# We zip up the following items into dist/
#
#	* src/
#	* assets/
#	* vendor/
#	* constants.php
#	* user_awards.php
#	* LICENSE.txt
#	* readme.txt

# Build dist if not available

if [ ! -d dist ]
then
	echo "dist/ not available. Creating this..."
	mkdir dist
fi


# -r --- Recursive zipping?
# -j --- Junk paths. Don't store our host directory structure.
zip -r --exclude=*.DS_Store* dist/user_awards.zip src/ assets/ vendor/ constants.php user_awards.php LICENSE.txt readme.txt
