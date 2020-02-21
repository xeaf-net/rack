const gulp       = require("gulp");

const clean      = require('gulp-clean');
const glob       = require('glob');
const browserify = require("browserify");
const source     = require('vinyl-source-stream');
const tsify      = require("tsify");
const minify     = require('gulp-minify');

// -- Очистка ------------------------------------------------------
gulp.task('clean', () => {
    return gulp.src(['./src/ui/public/dist/**/*.js']).pipe(clean());
});

// -- Компиляция TS в JS -------------------------------------------
gulp.task("compile", function () {
    const srcFiles = glob.sync('./src/ui/public/**/*.ts');
    return browserify({
        //basedir: '.',
        debug       : true,
        entries     : srcFiles,
        cache       : {},
        packageCache: {}
    })
        .plugin(tsify)
        .bundle()
        .pipe(source('rack.js'))
        .pipe(gulp.dest("./src/ui/public/dist"));
});

// -- Мнификация ---------------------------------------------------
gulp.task('minify', () => {
    return gulp.src([
        './src/ui/public/dist/**/*.js',
        '!./src/ui/public/dist/**/*.min.js'
    ])
        .pipe(minify({
            ext     : {
                min: '.min.js'
            },
            noSource: true
        }))
        .pipe(gulp.dest('./src/ui/public/dist'));
});

// -- Перестроение -------------------------------------------------
gulp.task('build', gulp.series(
    'clean',
    'compile',
    'minify',
    () => {
        return gulp.src([
            './src/ui/public/dist/**/*.js',
            '!./src/ui/public/dist/**/*.min.js'
        ])
            .pipe(gulp.dest('./src/ui/public/dist'));
    }));
