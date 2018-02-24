var gulp = require('gulp'),
    cleanCSS = require('gulp-clean-css'),
    rename = require('gulp-rename'),
    uglify = require('gulp-uglify'),
    sass = require('gulp-sass');


var css_files = [
    './style.css',
    './rtl.css',
    './css/*.css',
    '!./css/*.min.css',
    './includes/styles/**/*.css',
    '!./includes/styles/**/*.min.css'
];

var sass_files = [
    './includes/styles/**/*.scss'
];

var js_files = [
    './js/*.js',
    '!js/**/*.min.js'
];

var version = '4.0.0';

gulp.task('styles', ['sass'], function () {
    return gulp.src(css_files)
        .pipe(cleanCSS({
            specialComments: 1,
            level: 2,
            rebase: false
        }))
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(function (file) {
            return file.base;
        }));
});

gulp.task('main-style', function () {
    return gulp.src(['./style.css'])
        .pipe(cleanCSS({
            specialComments: 1,
            level: 2,
            rebase: false
        }))
        .pipe(rename({suffix: '-' + version + '.min'}))
        .pipe(gulp.dest(function (file) {
            return file.base;
        }));
});

gulp.task('scripts', function () {
    return gulp.src(js_files)
        .pipe(uglify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(function (file) {
            return file.base;
        }));
});

gulp.task('sass', function () {
    return gulp.src(sass_files)
        .pipe(sass.sync().on('error', sass.logError))
        .pipe(gulp.dest(function (file) {
            return file.base;
        }));
});

gulp.task('watch-sass', function () {
    gulp.watch(sass_files, ['sass']);
});

gulp.task('ws', function () {
    gulp.watch(sass_files, ['sass']);
});

gulp.task('watch', function () {
    gulp.watch(sass_files, ['sass']);
    gulp.watch(css_files, ['styles']);
    gulp.watch(js_files, ['scripts']);
});

gulp.task('default', ['styles', 'main-style', 'scripts']);
