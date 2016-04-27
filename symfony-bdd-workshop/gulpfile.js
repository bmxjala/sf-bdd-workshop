/* globals require */

'use strict';

var gulp = require('gulp'),
    $    = require('gulp-load-plugins')(),
    path = require('path'),

    runSequence = require('run-sequence'),
    del = require('del'),

// dist flag
    buildDist = false;

var sassPaths = [
    'assets/bower_components/foundation-sites/scss',
    'assets/bower_components/font-awesome',
    'assets/bower_components/motion-ui/src',
    'assets/bower_components/foundation-datepicker/css'
];

var baseDir = path.normalize(path.join(__dirname, '/web'));

var paths = {
    assests: path.normalize(path.join(__dirname, '/assets')),
    skin: path.join(baseDir, '/css'),
    js: path.join(baseDir, '/js')
};

//the title and icon that will be used for the Gulp notifications
var notifyInfo = {
    title: 'LendoSTPGdansk/sms-service'
};

//error notification settings for plumber
var plumberErrorHandler = {
    errorHandler: $.notify.onError({
        title: notifyInfo.title,
        message: "Error: <%= error.message %>"
    })
};

//styles
gulp.task('styles', function () {
    return gulp.src([paths.assests + '/styles/*.scss'])
        .pipe($.plumber(plumberErrorHandler))
        .pipe($.sourcemaps.init())
        .pipe($.sass({
            style: buildDist ? 'compressed' : 'nested',
            sourceComments: !buildDist,
            errLogToConsole: true,
            sourceMap: true,
            includePaths: sassPaths
        }))
        .on('error', function (error) {
            console.log(error);
            this.emit('end');
        })
        .pipe($.autoprefixer('last 2 version', 'safari 6', 'ie 10', 'ios 6', 'android 4'))
        .pipe($.sourcemaps.write('.', {includeContent: false}))
        .pipe($.if(buildDist, $.minifyCss()))
        .pipe(gulp.dest(paths.skin));
});

//scripts
gulp.task('scripts', function () {
    var scripts = [
        path.normalize(path.join(__dirname, '/assets/js/app.js')),
        path.join(paths.js, '/!(main)*.js')
    ];
    return gulp.src(scripts, {dot: true})
        .pipe($.concat('main.js', {newLine: '\n'}))
        .pipe(gulp.dest(paths.js))
        .pipe($.rename('main.min.js'))
        .pipe($.uglify())
        .pipe(gulp.dest(paths.js))
        .pipe($.filesize())
        .on('error', $.util.log)
});

//clean
gulp.task('clean', del([path.join(paths.assests, '/.sass-cache')], {dot: true}));

//copy assets
gulp.task('copy', function () {
    gulp.src('assets/bower_components/foundation-sites/dist/*.min.js', {dot: true}).pipe(gulp.dest(path.join(paths.js, '/vendor')));
    gulp.src('assets/bower_components/jquery/dist/*.min.js', {dot: true}).pipe(gulp.dest(path.join(paths.js, '/vendor')));
    gulp.src('assets/bower_components/waypoints/lib/jquery.waypoints.min.js', {dot: true}).pipe(gulp.dest(path.join(paths.js, '/vendor')));
    gulp.src('assets/bower_components/counter-up/jquery.counterup.min.js', {dot: true}).pipe(gulp.dest(path.join(paths.js, '/vendor')));
    gulp.src(path.join(paths.assests, '/favicon/**'), {dot: true}).pipe(gulp.dest(path.join(paths.skin, '/../favicon')));
    gulp.src('assets/bower_components/font-awesome/fonts/*', {dot: true}).pipe(gulp.dest(path.join(paths.skin, '/../fonts')));
    gulp.src('assets/bower_components/foundation-datepicker/js/*', {dot: true}).pipe(gulp.dest(path.join(paths.js, '/vendor/datepicker')));
    //gulp.src(path.join(paths.assests, '/fonts/**'), {dot: true}).pipe(gulp.dest(path.join(paths.skin, '/../fonts')));
    //gulp.src([path.join(paths.assests, '/img/**'), '!' + path.join(paths.assests, '/img/sprites/*')], {dot: true}).pipe(gulp.dest(path.join(paths.skin, '/../img')));
});

//dist
gulp.task('dist', function () {
    buildDist = true;
    return runSequence('clean', 'styles', 'scripts', 'copy');
});

//watch
gulp.task('watch', function () {
    $.livereload.listen();
    gulp.watch(paths.assests + '/styles/*.scss', ['styles']);
});

gulp.task('default', ['styles', 'watch']);
