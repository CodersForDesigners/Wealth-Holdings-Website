
# About
To make adding / editing content on websites easier, an installation of WordPress has been bundled in with the boilerplate.
Brownie Fudge Sundae uses WordPress as a CMS in a very specific way. Here, we'll walk through this approach step-by-step and show you how to use it.

## FAQ
Q. Where is this CMS?
A. The WordPress installation resides in a folder aptly titled `cms`.

Q. What if don't need this?
A. No worries, this is completely opt-in. Your website will work just fine without roping this in. This is strictly an add-on module.



# Setup and Installation
In this section, we go over installing and setting up the CMS for use in your website.
The process differs depending on the environment: local or production.

## Local environment
Here, we'll go through setting up WordPress locally. We assume you're using MAMP or MAMP Pro. If not, then go figure.

### Database
1. Open MAMP.
2. Start the server(s) if they aren't already.
3. Click on "WebStart" or "Open WebStart Page".
4. Note the Username, Password and Host under the "MySQL" section.
5. Get to phpMyAdmin somehow.
6. Once there, go to the "Databases" tab. It should be the first one from the left.
7. At the top, type in the database name and hit "Create". No need to select a Collation.

That's it for the database.

### Codebase
1. Clone the Brownie Fudge Sundae Git repo onto a dedicated website folder.
2. Point MAMP to the website project folder.

Next, refer to the "Setting up WordPress" section.


## Production environment
To be elaborated on.


## Installing WordPress
Go to `http://<your_site_name>/cms` to get to the WordPress setup wizard.
Once you're on the WordPress setup wizard, go through it.
On one screen, you'll be prompted to input your database info. Type in the information you noted earlier.
Once the installation is done, you'll be taken to the login page. If for some reason, you accidently close the tab, here's the url:
`http://<your_site_name>/cms/wp-login.php`
Log in using the credentials you provided earlier.

## Configuring WordPress
There are some things that you need to do on WordPress.

Settings -> General
	Remove the `/cms` portion from the "Site Address (URL)" field

Settings -> Permalinks
	Select "Custom Structure" and input `/%postname%`.
	Notice there is **no** forward slash at the end. This is important!

Appearance -> Themes
	Active the theme "Brownie Fudge Sundae"

Tools -> Import
	Select the file `wp-content-backup.xml`.
	You'll be prompted to decide what to do regarding authors and attachments.
		Assign posts to an existing user.
		Do **not** download and import attachments.



1. Install the plugins CPT-UI, followed by ACF Pro.
2. Import the custom fields on ACF.


# Development
In this section, we'll go over common tasks that you'll find yourself needing to do over and over again.

## Adding a New Page to the Website
1. Clone the `sample.php` file in the `pages` directory. Rename it to the URL that you intend this new page to be accessible on. For example, if you're making an "About" page that needs to be accessible on `https://example.com/about`, then clone the `sample.php` file and call it `about.php`.
2. Open up `inc/default-nav-links.php` and add an entry for this page to the array. There already exists one for the sample page. Simply duplicate the line and change the values accordingly.

## Utility Vars and Functions
We've made a few handy functions and variables that you'll be using often.

### Vars
pageId
	Holds the ID of the current post or page or custom post
siteUrl
	Holds the URL to the website. For example, `https://example.com`

### Functions
getContent
	Returns a value for a given field
	If the value is not found or is empty, the fallback value that you (also) provided is returned









# To figure
Packing WP with just our theme.
	Add `cms/wp-config.php` to .gitignore

What happens if we change the site URL on the WP settings page to just be the domain sans sub-directory?
Why WordPress still has influence on URLs outside its scope?

Development
	What should and shouldn't be part of the repo
	In the post deploy script, when the db already exists, how will wp-cli respond?
Deployment
	how wp-config.php will work?
Will WP be packaged or not?

There should be an alternate setup script that simply stubs out all the required directories and files, but does not do anything content / db setup / importing.

## CMS Auto-setup
Install WordPress without any default pages or posts.
When WP-CLI install WordPress, for some reason it injects Apache rewrite rules in the project root as opposed to the WordPress folder itself.
What happens to the setup process if the database already exists?
Linking the uploads directory with the parent media folder.

## Database migration
We need to sync our layer of custom settings and content onto a fresh installation of WordPress, while retaining all the unique properties of the existing installation.




## WP-CLI setup
Add this to the config file
```php
/**
 * Disable auto-updates
 */
define( 'WP_AUTO_UPDATE_CORE', false );
```
