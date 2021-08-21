//$ npm install --save-dev gulp

var gulp = require('gulp');
var concat = require('gulp-concat');
var minify = require('gulp-minify');
var cleanCss = require('gulp-clean-css');
var sass = require('gulp-sass');
var rename = require('gulp-rename');
var touch = require('gulp-touch-cmd');
var imagemin = require('gulp-imagemin');

gulp.task('img-min', function(){
    return gulp.src('Content/assets/images/banners/*')
    .pipe(imagemin())
    .pipe(gulp.dest('Content/assets/images/banners/min'))
});

gulp.task('lib-pack-js', function () {    
    return gulp.src([
        'Content/assets/js/lib/jquery-3.3.1.js',
        'Content/assets/js/lib/*.js'])
        .pipe(concat('lib-bundle.js'))
        .pipe(minify({
            ext:{
                min:'.js'
            },
            noSource: true
        }))
        .pipe(gulp.dest('Content/assets/js'));
});

gulp.task('module-pack-js', function () {    
    return gulp.src(['Content/assets/js/modules/*.js'])
        .pipe(concat('modules-bundle.js'))
        /*.pipe(minify({
           ext:{
               min:'.js'
           },
           noSource: true
        }))*/
        .pipe(gulp.dest('Content/assets/js/'));
});

gulp.task('lib-pack-css', function () {    
    return gulp.src([
        'Content/assets/css/lib/*.css'])
        .pipe(concat('lib-bundle.css'))
        .pipe(sass().on('error', sass.logError))
        .pipe(cleanCss())
		.pipe(rename('lib-style.min.css'))
		.pipe(gulp.dest('Content/assets/css'))
});

gulp.task('sass', function () {    
    return gulp.src('Content/assets/sass/style.scss')
		.pipe(sass().on('error', sass.logError))
		.pipe(cleanCss())
		.pipe(rename('main-style.min.css'))
		.pipe(gulp.dest('Content/assets/css'))
		.pipe(touch());
});

gulp.task('sass2', function () {
    return gulp.src('./template/sellers-form/assets/sass/style.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(cleanCss())
        .pipe(rename('sellers.min.css'))
        .pipe(gulp.dest('./template/sellers-form/assets/css'))
        .pipe(touch());
});

gulp.task('watch', function() {
    gulp.watch('Content/assets/sass/**/*.scss', gulp.series('sass'));
    gulp.watch('template/sellers-form/assets/sass/style.scss', gulp.series('sass2'));
    gulp.watch('Content/assets/js/lib/**/*.js', gulp.series('lib-pack-js'));
    gulp.watch('Content/assets/js/modules/**/*.js', gulp.series('module-pack-js'));
    gulp.watch('Content/assets/css/lib/**/*.css', gulp.series('lib-pack-css'));
});