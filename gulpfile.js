'use strict';
 
var gulp = require('gulp');
var sass = require('gulp-sass');
 
gulp.task('sass', function () {
  return gulp.src(['./assets/css/scss/admin.scss', './assets/css/scss/algolia-woocommerce-instantsearch.scss'])
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./assets/css'));
});

gulp.task('sass:watch', function () {
  gulp.watch(['./assets/css/scss/*.scss','./assets/css/scss/components/*.scss'], ['sass']);
});