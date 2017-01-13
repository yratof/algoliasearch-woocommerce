---
title: Customize the way the Algolia plugin for WooCommerce behaves
description: Customize the look & feel or the features of the plugin.
layout: page.html
---
## Introduction

To be able to customize the way the plugin behaves, you need to make use of WordPress `filters` and `actions`.

It can sometimes be time consuming to find the filter to use, and do the actual implementation.

In the next section we provide you with a small plugin that will help you with properly extending the plugin.

## The boilerplate plugin

If you want to customize the look and feel or the way the plugin behaves, you should download and install the following plugin: https://github.com/algolia/algoliasearch-woocommerce-custom

Once installed, you should uncomment the code snippets you want to use in the `algoliasearch-woocommerce-custom.php` file.

<div class="alert alert-info">By making all the changes in a dedicated plugin, you ensure that you don't loose your changes when updating your theme or the Algolia plugin for WooCommerce.</div>



