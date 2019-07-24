#!/bin/bash
#
#	Copy items over to our "production" folder.
#	The production folder is a svn repository
#	that we can use to update our plugin in the wordprsss plugin directory
#
#	* src/
#	* assets/
#	* vendor/
#	* constants.php
#	* user_awards.php
#	* LICENSE.txt
#	* readme.txt

BASE_PROJECT_FOLDER="/Users/kwmartin/bin/projects/user_awards"
DEV_FOLDER="${BASE_PROJECT_FOLDER}/development"
DEV_ASSETS="${DEV_FOLDER}/assets"
PRODUCTION_FOLDER="${BASE_PROJECT_FOLDER}/production"
ASSETS_FOLDER="${PRODUCTION_FOLDER}/assets"
BRANCHES_FOLDER="${PRODUCTION_FOLDER}/branches"
TAGS_FOLDER="${PRODUCTION_FOLDER}/tags"
TRUNK_FOLDER="${PRODUCTION_FOLDER}/trunk"

cp "${DEV_FOLDER}/user_awards.php" "${DEV_FOLDER}/LICENSE.txt" "${DEV_FOLDER}/readme.txt" "${DEV_FOLDER}/constants.php" "${TRUNK_FOLDER}"
cp -r "${DEV_FOLDER}"/vendor "${DEV_FOLDER}"/src "${TRUNK_FOLDER}"
[ -d "${TRUNK_FOLDER}/assets" ] || mkdir "${TRUNK_FOLDER}/assets"
cp -r "${DEV_ASSETS}"/scripts "${DEV_ASSETS}"/styles "${TRUNK_FOLDER}"/assets/
cp -r "${DEV_FOLDER}"/assets/icons/ "${ASSETS_FOLDER}"
cp "${DEV_FOLDER}"/assets/*.png "${ASSETS_FOLDER}"