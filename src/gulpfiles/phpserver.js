var gulp = require('gulp'),
	browserSync = require('browser-sync'),
	php = require('gulp-connect-php');

gulp.task('php', function() {
    php.server({}, function () {
        browserSync({ proxy: '127.0.0.1:8000'});
    });
    
    gulp.watch(['*','css/*', 'js/*']).on('change', function () {
        browserSync.reload();
    });
});

gulp.task('default', ['php']);
