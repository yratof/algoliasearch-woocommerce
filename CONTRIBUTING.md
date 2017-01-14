# Contributing

When contributing to this repository, please first discuss the change you wish to make via issue,
email, or any other method with the owners of this repository before making a change. 

Please note we have a code of conduct, please follow it in all your interactions with the project.

## Setting up a development environment

For setting up a wordpress development environment we recommend a Vagrant setup.

Please follow these steps

1. Install [VirtualBox 5.1.x](https://www.virtualbox.org/wiki/Downloads)

2. Install [Vagrant 1.8.x](https://www.vagrantup.com/downloads.html)
		* `vagrant`command should now be available through your CLI (you can try running `vagrant -v` to check your installed version)

3. We recommend you to also install these Vagrant plugins which will ease your development process and manage your vagrant configuration
	1. Install the [vagrant-hostsupdater](https://github.com/cogitatio/vagrant-hostsupdater) plugin with `vagrant plugin install vagrant-hostsupdater`
        * Note: This step is not a requirement, though it does make the process of starting up a virtual machine nicer by automating the entries needed in your local machine's `hosts` file to access the provisioned VVV domains in your browser.
        * If you choose not to install this plugin, a manual entry should be added to your local `hosts` file that looks like this: `192.168.50.4  vvv.dev local.wordpress.dev src.wordpress-develop.dev build.wordpress-develop.dev`
  1. Install the [vagrant-triggers](https://github.com/emyl/vagrant-triggers) plugin with `vagrant plugin install vagrant-triggers`
      * Note: This step is not a requirement. When installed, it allows for various scripts to fire when issuing commands such as `vagrant halt` and `vagrant destroy`.
      * By default, if vagrant-triggers is installed, a `db_backup` script will run on halt, suspend, and destroy that backs up each database to a `dbname.sql` file in the `{vvv}/database/backups/` directory. These will then be imported automatically if starting from scratch. Custom scripts can be added to override this default behavior.
      * If vagrant-triggers is not installed, VVV will not provide automated database backups.
  1. Install the [vagrant-vbguest](https://github.com/dotless-de/vagrant-vbguest) plugin with `vagrant plugin install vagrant-vbguest`.
      * Note: This step is not a requirement. When installed, it keeps the [VirtualBox Guest Additions](https://www.virtualbox.org/manual/ch04.html) kernel modules of your guest 

3. Clone or download the [Varying Vagrant Vagrants](https://github.com/Varying-Vagrant-Vagrants/VVV) project into a local directory by running `git clone git://github.com/Varying-Vagrant-Vagrants/VVV.git vagrant-local`

4. Change directory throught the command line interface to be inside the `vagrant-local` directory

5. Run your vagrant machine by running `vagrant up` through the command line
		* this will setup your vagrant machine. Please be patient as this step may take some time as vagrant will download and install all of the required files 

6. In case you haven't installed the vagrant-hostsupdate plugin then please add an `192.168.50.4 local.wordpress.dev` entry to your `hosts` file in order to be able to access the development environment

You should now be able to reach your Wordpress installation by visiting [local.wordpress.dev](http://local.wordpress.dev)

You can login to Wordpress admin dashboard please do that through the [login](http://local.wordpress.dev/wp-login) page using the following 

**LOGIN CREDENTIALS :**
**username** : admin
**password** : password

## Enabling Algolia for Wordpress

1. Change directory from `vagrant-local` to be inside `vagrant-local/www/wordpress-default/public_html/wp-content/plugins`

2. Clone Algolia for Wordpress inside your plugins directory by running `git clone git@github.com:algolia/algoliasearch-wordpress.git`

3. Clone Algolia for WooCommerce inside your plugins directory by running `git clone git@github.com:algolia/algoliasearch-woocommerce.git`
	
4. After cloning or downloading both plugins please visit the `Plugins` tab of your Wordpress dashboard where you should see both `Algolia Search for WooCommerce` and `Search by Algolia – Instant & Relevant results
	* if you did not use git please make sure that each plugin directory is correctly named. E.g the Algolia WooCommerce plugin folder has to be named `algoliasearch-woocommerce` and Algolia Wordpress plugin folder has to be named `algoliasearch-wordpress` 

5. Install and enable `WooCommerce` plugin through the Wordpress Plugins dashboard

6. Enable Algolia for Wordpress and Algolia for WooCommerce plugins

7. Follow the instructions of setting up Algolia for Wordpress and Algolia for WooCommerce

### Need Help?

* Let us have it! Don't hesitate to open a new issue on GitHub if you run into trouble or have any tips that we need to know and please be as specific as you can be when describing your issue so that we will have the needed information to reproduce and resolve the issue. **Please keep in mind that the more information about the issue you give us, the faster we will be at resolving it.**

