// Include gulp
var gulp = require('gulp');

// Include plugins
var concat   = require('gulp-concat');
var uglify   = require('gulp-uglify');
var rename   = require('gulp-rename');
var compass  = require('gulp-compass');
var plumber  = require('gulp-plumber');

gulp.task('default', ['website']);

// WEBSITE
gulp.task('website', ['scripts','watch','styles']);
gulp.task('watch', function () {
    gulp.watch('resources/js/**/*.js', ['scripts']);
    gulp.watch('resources/scss/**/*.scss', ['styles']);
});

gulp.task('styles', function () {
    gulp.src([
        'resources/scss/**/*.scss'
    ]).pipe(plumber())
    .pipe(compass({
        css: 'css/',
        sass: 'resources/scss/',
        style: 'compressed',
        sourcemap: true,
        config_file: 'config/compass.rb'
    }));
});

// Concatenate JS Files
gulp.task('scripts', function() {
    return gulp.src([
        'resources/js/**/*.js'
    ]).pipe(plumber())
    .pipe(concat('app.js'))
    .pipe(rename({suffix: '.min'}))
    .pipe(uglify())
    .pipe(gulp.dest('js'));
});