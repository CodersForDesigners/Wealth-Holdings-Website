
#!/bin/bash

# Constants that will be used throughout the script
PROJECT_ROOT=$PWD
WP_VERSION="5.1"
PATH_WP_CLI="${PROJECT_ROOT}/wp-cli.phar"




# What's this script called?
# "Weird" font from http://patorjk.com/software/taag/
echo "  __                 ___                    "
echo " /|  /              /            |          "
echo "( | (___  ___      (___       ___| ___  ___ "
echo "  | |   )|___)     |    |   )|   )|   )|___)"
echo "  | |  / |__       |    |__/ |__/ |__/ |__  "
echo "                                  __/       "

# What does this script do?
echo "\nThis script will add WordPress to your project."
echo "NOTE: You can hit \`Ctrl-C\` at any point to exit and cancel out."
echo "IMPORTANT!! Please make sure that you're running this script from the root of your project folder. Else, you're computer will explosion!"

echo "\n::PREREQUISITES::\n"
echo "Before we begin,"
read -p "1. Are you using MAMP in building this project? (y/n) " usingMAMP
if [ "$usingMAMP" = y ]; then
	if [ -d "/Applications/MAMP/bin/php/" ]; then
		# Make MySQL accessible globally
		export PATH=/Applications/MAMP/Library/bin:$PATH
		# Use MAMP's version of PHP
		MAMP_PHP_VERSION=$(ls /Applications/MAMP/bin/php/ | sort -n | tail -1)
		export PATH=/Applications/MAMP/bin/php/${MAMP_PHP_VERSION}/bin:$PATH
	fi
fi

if ! command -v wp >/dev/null 2>&1; then
	echo "\nBefore we begin, make sure that you're connected to the internet."
	read -p "Connected? Okay, now press Enter to continue: "
	curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
	alias wp="php \"${PATH_WP_CLI}\""
fi

# Brief the user on what's what
echo "\n\n"
echo "Ok, so first things first. WordPress needs a database to store content in."
echo "For that, you'll need to provide some deets."
echo "NOTE: If you're using MAMP, you can find the information at http://localhost/MAMP"
echo "\n"

# Database name
while [ -z $databaseName ]; do
	read -p "1. What do you want to name the database? " databaseName
done
# Database user
read -p "2. Who's the database user? [root] " databaseUser
databaseUser=${databaseUser:-root}
# Database password
read -p "3. What's the database user's password? [root] " databasePassword
databasePassword=${databasePassword:-root}
# Database address
read -p "4. What address is the database accessible on? [localhost] " databaseURL
databaseURL=${databaseURL:-localhost}
# Database table prefix
read -p "5. What prefix should all the tables in the database have? [wp_] " tablePrefix
tablePrefix=${tablePrefix:-wp_}


# Download WordPress into the `cms` folder
# # Refer to https://wordpress.org/download/releases/ for all available versions
wp core download --version="$WP_VERSION" --path=cms
cd cms


# Set up the configuration for WordPress
if [ -f "wp-config.php" ]; then
	rm wp-config.php
fi
wp core config --dbname="$databaseName" --dbuser="$databaseUser" --dbpass="$databasePassword" --dbhost="$databaseURL" --dbprefix="$tablePrefix" --extra-php <<PHP
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
/**
 * Disable auto-updates
 */
define( 'WP_AUTO_UPDATE_CORE', false );
PHP

# Create the database
echo "\n"
echo "Okay, so now we're going create the database."
echo "Do make sure you have MySQL running. If using MAMP, hit \"Start servers\"."
read -p "Press Enter to continue: "
wp db check &>/dev/null || wp db create


echo "\n"
echo "Cool beans. Now we need some information for the WordPress installation."

# WordPress Site Title
while [ -z "$siteTitle" ]; do
	read -p "1. Site Title: " siteTitle
done
# Site URL
read -rp "2. The URL on which the website \"root\" is accessible: [http://localhost] " siteURL
siteURL=${siteURL:-localhost}
	# Remove trailing slash if present
	siteURL=${siteURL%/}

# WordPress Admin Username
while [ -z "$adminUsername" ]; do
	read -p "3. Admin Username: " adminUsername
done
# WordPress Admin Password
while [ -z "$adminPassword" ]; do
	read -p "4. Admin Password: " adminPassword
done
# WordPress Admin Email
while [ -z "$adminEmail" ]; do
	read -p "5. Admin Email Address: " adminEmail
done

wp core install --url="${siteURL}/cms" --title="$siteTitle" --admin_user="$adminUsername" --admin_password="$adminPassword" --admin_email="$adminEmail"

# #
# Configure WordPress settings
# #
# # Insert .htaccess file
	# # The .htaccess file won't be generated if the `home` url does not include the "cms" portion, so this seems redundant but it's for the case when WordPress is already installed on the database
wp option update home "${siteURL}/cms"
wp eval 'global $wp_rewrite; $home_path = get_home_path(); $htaccess_file = $home_path . ".htaccess"; $rules = explode( "\n", $wp_rewrite->mod_rewrite_rules() ); insert_with_markers( $htaccess_file, "WordPress", $rules );'

# # Update site settings
wp option update permalink_structure "/%postname%"
wp option update home "${siteURL}/cms"

# # Link the uploads folder to our media folder
cd wp-content
mkdir -p ../../media/cms
rm -rf uploads
ln -s ../../media/cms uploads
cd ..

# # Link and activate our theme
cd wp-content/themes
ln -s ../../../resources/wordpress-theme bfs
cd ../..
wp theme activate bfs

# # Install and activate plugins
wp plugin install wordpress-importer --activate
wp plugin install custom-post-type-ui --activate
echo "\n"
read -p "Please install and activate the plugin \"Advanced Custom Fields Pro\" manually and then come back here and hit Enter. "

# # Seed the datbase
echo "Removing default sample content....."
wp post delete $(wp post list --post_type=post,page --format=ids);
wp post delete $(wp post list --post_type=post,page --post_status=trash --format=ids);

echo "Seeding the database with sample content....."
read -p "Create a custom post type with the slug \"brownie\" and then come back here. "
wp import ../resources/sample-content.xml --authors=skip
wp option update siteurl "${siteURL}/cms"
wp option update home "${siteURL}/cms"



# #
# Clean up
# #
# Remove local instance of WP-CLI
if [ -f "$PATH_WP_CLI" ]; then
	rm "$PATH_WP_CLI"
fi
# Unalias `wp`
# # Not required as the alias gets cleaned up automatically when the script exits

# #
# Closing
# #
echo "\nMay the Fudge be with You."
