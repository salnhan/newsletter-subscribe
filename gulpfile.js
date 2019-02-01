const gulp = require('gulp');
const sass = require('gulp-sass');
const cleanCSS = require('gulp-clean-css');

// defines path of scss files and path of css files
var sassFilesPath = './Resources/Private/Stylesheets/*.scss';
var cssFilesPath = './Resources/Public/Stylesheets/';

// task to compile sass files to css
// usage: call gulp styles
gulp.task('styles', async function () {
    gulp
        .src(sassFilesPath)
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest(cssFilesPath))
});

// task to watch adjustment of sass files and auto compile to css
// usage: call gulp watch
gulp.task('watch', async function () {
    gulp.watch(sassFilesPath,['styles']);
});