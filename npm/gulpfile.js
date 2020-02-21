const gulp         = require('gulp');
const autoprefixer = require('gulp-autoprefixer');
const clean        = require('gulp-clean');
const cssmin       = require('gulp-clean-css');
const minify       = require('gulp-minify');
const rename       = require('gulp-rename');
const requireDir   = require('require-dir');
const sass         = require('gulp-sass');
const ts           = require('gulp-typescript');

requireDir('./vendor/xeaf-net/rack/npm/gulp');

gulp.task('clean-local', () => {
    return gulp.src([
        'src/**/*.css',
        'src/**/*.js'
    ]).pipe(clean());
});

gulp.task('compile-local-css', () => {
    // noinspection JSUnresolvedFunction
    return gulp.src('src/**/*.scss')
        .pipe(sass({
            outputStyle: 'nested'
        }).on('error', sass.logError))
        .pipe(autoprefixer())
        .pipe(gulp.dest('./src'));
});

gulp.task('minify-local-css', () => {
    return gulp.src(['src/**/*.css', '!src/**/*.min.css'])
        .pipe(cssmin())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('./src'));
});

gulp.task('compile-local-ts', () => {
    return gulp.src('src/**/*.ts')
        .pipe(ts({
            noImplicitAny: true
        }))
        .pipe(gulp.dest('./src'));
});

gulp.task('minify-local-ts', () => {
    return gulp.src(['src/**/*.js', '!src/**/*.min.js'])
        .pipe(minify({
            ext     : {
                min: '.min.js'
            },
            noSource: true
        }))
        .pipe(gulp.dest('./src'));
});

gulp.task('compile-all', gulp.series(
    'clean-local',
    'compile-local-css',
    'compile-local-ts',
    (done) => {
        return done();
    }));

gulp.task('clean-all', gulp.series(
    'clean-local',
    'clean-xeaf-rack',
    (done) => {
        return done();
    }));

gulp.task('build-all', gulp.series(
    'compile-all',
    'minify-local-css',
    'minify-local-ts',
    'compile-xeaf-rack',
    (done) => {
        return done();
    }));

gulp.task('live-server', (done) => {

    gulp.watch('src/**/*.scss', gulp.series('compile-local-css', (done) => {
        // browserSync.reload();
        done();
    }));

    gulp.watch('src/**/*.ts', gulp.series('compile-local-ts', (done) => {
        // browserSync.reload();
        done();
    }));

    done();
});
