const gulp = require('gulp');
const uglify = require('gulp-uglify');
const path = require('path');
const rename = require('gulp-rename');
const cleanCss = require('gulp-clean-css');

const maisonThemeBaseDir = 'wp-content/themes/maison';
const jsFilesToMinify = [
    path.join(maisonThemeBaseDir, 'assets/js/global.js'),
    path.join(maisonThemeBaseDir, 'assets/js/checkout.js')
];

const cssFilesToMinify = [
    path.join(maisonThemeBaseDir, 'assets/css/bootstrap.css'),
    path.join(maisonThemeBaseDir, 'assets/css/fontello.css'),
    path.join(maisonThemeBaseDir, 'assets/css/checkout.css'),
    path.join(maisonThemeBaseDir, 'style.css')
];

gulp.task('minify-js', () => { 
    gulp.src(jsFilesToMinify)
        .pipe(uglify())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest(f => f.base))
});

gulp.task('minify-css', () => { 
    gulp.src(cssFilesToMinify)
        .pipe(cleanCss({ rebase: false }))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest(f => f.base))
});

gulp.task('build', ['minify-js', 'minify-css']);

gulp.task('watch', () => {
    gulp.watch(jsFilesToMinify.concat(cssFilesToMinify), ['build']);
});

gulp.task('default', ['build'])