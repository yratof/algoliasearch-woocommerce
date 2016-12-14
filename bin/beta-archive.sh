#!/usr/bin/env bash

rm -rf ./build

mkdir ./build
mkdir ./build/algoliasearch-woocommerce

cp -R ./assets ./build/algoliasearch-woocommerce
cp -R ./includes ./build/algoliasearch-woocommerce
cp -R ./languages ./build/algoliasearch-woocommerce
cp -R ./templates ./build/algoliasearch-woocommerce
cp algolia-woocommerce.php ./build/algoliasearch-woocommerce
cp CHANGELOG.md ./build/algoliasearch-woocommerce
cp gulpfile.js ./build/algoliasearch-woocommerce
cp package.json ./build/algoliasearch-woocommerce
cp README.md ./build/algoliasearch-woocommerce

cd ./build
zip -r algoliasearch-woocommerce.zip algoliasearch-woocommerce
rm -rf ./algoliasearch-woocommerce
cd ..

