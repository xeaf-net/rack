const gulp         = require('gulp');
const autoprefixer = require('gulp-autoprefixer');
const clean        = require('gulp-clean');
const cssmin       = require('gulp-cssmin');
const minify       = require('gulp-minify');
const rename       = require('gulp-rename');
const sass         = require('gulp-sass');
const ts           = require('gulp-typescript');

gulp.task('clean-xeaf-rack', () => {
    return gulp.src([
        'vendor/xeaf-net/rack/ui/**/*.css',
        'vendor/xeaf-net/rack/ui/**/*.js'
    ]).pipe(clean());
});

gulp.task('compile-xeaf-rack-css', () => {
    // noinspection JSUnresolvedFunction
    return gulp.src('vendor/xeaf-net/rack/ui/**/*.scss')
        .pipe(sass({
            outputStyle: 'nested'
        }).on('error', sass.logError))
        .pipe(autoprefixer())
        .pipe(cssmin())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('./vendor/xeaf-net/rack/ui'));
});

gulp.task('compile-xeaf-rack-ts', () => {
    return gulp.src('vendor/xeaf-net/rack/ui/**/*.ts')
        .pipe(ts({
            noImplicitAny: true
        }))
        .pipe(minify({
            ext     : {
                min: '.min.js'
            },
            noSource: true
        }))
        .pipe(gulp.dest('./vendor/xeaf-net/rack/ui'));
});

gulp.task('compile-xeaf-rack', gulp.series(
    'clean-xeaf-rack',
    'compile-xeaf-rack-css',
    'compile-xeaf-rack-ts',
    (done) => {
        return done();
    }));