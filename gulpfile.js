'use strict';

var plugin = require('./package.json');
var gulp = require('gulp');
var sass = require('gulp-sass');
var wpPot = require('gulp-wp-pot');
var sort = require('gulp-sort');

gulp.task('makepot', function () {
  return gulp.src(['algolia-woocommerce.php', 'includes/*.php', 'templates/*.php'])
  .pipe(sort())
  .pipe(wpPot( {
    domain: 'algolia-woocommerce',
    destFile:'algolia-woocommerce.pot',
    package: 'Algolia for WooCommerce ' + plugin.version,
    /*bugReport: 'http://example.com',
    lastTranslator: 'John Doe <mail@example.com>',
    team: 'Team Team <mail@example.com>'*/
  } ))
  .pipe(gulp.dest('languages'));
});

gulp.task('sass', function () {
  return gulp.src(['./assets/css/scss/admin.scss', './assets/css/scss/algolia-woocommerce-instantsearch.scss'])
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./assets/css'));
});

gulp.task('sass:watch', ['sass'], function () {
  gulp.watch(['./assets/css/scss/*.scss','./assets/css/scss/components/*.scss'], ['sass']);
});
