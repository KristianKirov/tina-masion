const gulp = require('gulp');
const uglify = require('gulp-uglify');
const path = require('path');
const rename = require('gulp-rename');
const cleanCss = require('gulp-clean-css');

const maisonThemeBaseDir = 'wp-content/themes/maison';
const jsFilesToMinify = [
    path.join(maisonThemeBaseDir, 'assets/js/global.js'),
    path.join(maisonThemeBaseDir, 'assets/js/checkout.js'),
    path.join(maisonThemeBaseDir, 'assets/js/admin.js'),
];

const cssFilesToMinify = [
    path.join(maisonThemeBaseDir, 'assets/css/bootstrap.css'),
    path.join(maisonThemeBaseDir, 'assets/css/fontello.css'),
    path.join(maisonThemeBaseDir, 'assets/css/checkout.css'),
    path.join(maisonThemeBaseDir, 'style.css'),
];

gulp.task('minify-js', () => {
    return gulp
        .src(jsFilesToMinify)
        .pipe(uglify())
        .pipe(
            rename({
                suffix: '.min',
            })
        )
        .pipe(gulp.dest(f => f.base));
});

gulp.task('minify-css', () => {
    return gulp
        .src(cssFilesToMinify)
        .pipe(cleanCss({ rebase: false }))
        .pipe(
            rename({
                suffix: '.min',
            })
        )
        .pipe(gulp.dest(f => f.base));
});

gulp.task('build', gulp.series('minify-js', 'minify-css'));

gulp.task('watch', () => {
    return gulp.watch(jsFilesToMinify.concat(cssFilesToMinify), gulp.series('build'));
});

gulp.task('default', gulp.series('build'));
