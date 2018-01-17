const gulp = require('gulp');
const browserSync = require('browser-sync').create();

gulp.task('browser-sync',function(){
    browserSync.init({
        proxy: "http://localhost.silex",
        files: [
            "src/**/**.php",
            "public/index.php",
            "templates/**/*.phtml"
        ]
    })
});