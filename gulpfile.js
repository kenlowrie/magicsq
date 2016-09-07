var gulp = require('gulp'),
	gutil = require('gulp-util'),
	gulpif = require('gulp-if'),
	postcss = require('gulp-postcss'),
	precss = require('precss'),
	debug = require('gulp-debug'),
	cssnano = require('cssnano'),
	uglify = require('gulp-uglify'),
	autoprefixer = require('autoprefixer'),
	cached = require('gulp-cached');

var env,
	srcDir,
	bldRoot,
	jsSources,
	cssSources,
	outDir;

env = process.env.NODE_ENV || 'dev';
srcDir = 'src/';
bldRoot = 'build/';
jsSources = [srcDir + 'js/*.js'];
phpSources = [srcDir + 'php/*'];
phpGulpSrc = [srcDir + 'gulpfiles/*.js'];
docsFiles = [srcDir + 'docs/*'];
imageFiles = [srcDir + '**/images/*'];
cssSources = [srcDir + '**/styles.css', srcDir + '**/stylespdf.css'];

if (env === 'dev'){
	outDir = bldRoot + 'dev/';
} else {
	outDir = bldRoot + 'rel/';
}

console.log('Building magicsq in ' + env + ' mode to ' + outDir);

gulp.task('cpdocs', function(){
   gulp.src(docsFiles)
    .pipe(cached('docscache'))
  	.pipe(gulp.dest(outDir));
});

gulp.task('cpimg', function(){
   gulp.src(imageFiles,{base: srcDir}) 
    .pipe(cached('imgcache'))
  	.pipe(gulp.dest(outDir));
});

gulp.task('cpjs', function(){
   gulp.src(jsSources,{base: srcDir}) 
    .pipe(gulpif(env === 'rel', uglify()))
    .pipe(cached('jscache'))
  	.pipe(gulp.dest(outDir));
});

gulp.task('cpphp', function(){
   gulp.src(phpSources) 
    .pipe(cached('phpcache'))
  	.pipe(gulp.dest(outDir));
});

gulp.task('cpgulpphpsrv', function(){
   gulp.src(phpGulpSrc)
   	.pipe(debug())
  	.pipe(gulp.dest(outDir));
});

var postcssTasks = [precss(),autoprefixer()];
if (env === 'rel'){
	postcssTasks = [precss(),autoprefixer(),cssnano()];
}

gulp.task( 'css', function() {
	gulp.src(cssSources)
		.pipe(postcss(postcssTasks))
		.on('error', gutil.log)
		.pipe(gulp.dest(outDir))
});

gulp.task('watch', function() {
  gulp.watch(srcDir + '**/*.css', ['css']);
  gulp.watch(srcDir + 'php/*', ['cpphp']);
  gulp.watch(srcDir + 'js/*.js', ['cpjs']);
  gulp.watch(srcDir + 'docs/*', ['cpdocs']);
  gulp.watch(srcDir + 'images/*', ['cpimg']);
  gulp.watch(srcDir + 'gulpfiles/*.js', ['cpgulpphpsrv']);
  
});

var buildtasks=['cpdocs', 'css', 'cpimg', 'cpphp', 'cpjs', 'cpgulpphpsrv'];

gulp.task('build', buildtasks); 

gulp.task('default', buildtasks.concat(['watch']));
