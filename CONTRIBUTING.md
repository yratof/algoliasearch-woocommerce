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

3. Clone or download the [Varying Vagrant Vagrants](https://github.com/Varying-Vagrant-Vagrants/VVV) project into a local directory by running `git clone git://github.com/Varying-Vagrant-Vagrants/VVV.git vagrant-local`

4. Change directory throught the command line interface to be inside the `vagrant-local` directory

5. Run your vagrant machine by running `vagrant up` through the command line
		* this will setup your vagrant machine. Please be patient as this step may take some time as vagrant will download and install all of the required files 

6. Add an `192.168.50.4 local.wordpress.dev` entry to your `hosts` file

You should now be able to reach your Wordpress installation by visiting [local.wordpress.dev](http://local.wordpress.dev)

You can login to Wordpress admin dashboard please do that through the [login](http://local.wordpress.dev/wp-login) page using the following 
**LOGIN CREDENTIALS** :
**username** : admin
**password** : password

##. Enabling Algolia for Wordpress

1. Change directory from `vagrant-local` to be inside `vagrant-local/www/wordpress-default/public_html/wp-content/plugins`

2. Clone Algolia for Wordpress inside your plugins directory by running `git clone git@github.com:algolia/algoliasearch-wordpress.git`

3. Clone Algolia for WooCommerce inside your plugins directory by running `git clone git@github.com:algolia/algoliasearch-woocommerce.git`
	
4. After installation of both plugins please visit the `Plugins` tab of your Wordpress dashboard where you should see both `Algolia Search for WooCommerce` and `Search by Algolia – Instant & Relevant results

4. Enable Algolia for Wordpress and Algolia for WooCommerce plugins

5. Follow the instructions of setting up Algolia for Wordpress and Algolia for WooCommerce

### Need Help?

* Let us have it! Don't hesitate to open a new issue on GitHub if you run into trouble or have any tips that we need to know and please be as specific as you can be when describing your issue so that we will have the needed information to reproduce and resolve the issue. **Please keep in mind that the more information about the issue you give us, the faster we will be at resolving it.**

