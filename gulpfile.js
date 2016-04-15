var autoprefixer = require('gulp-autoprefixer');
var babelify = require('babelify');
var browserify = require('browserify');
var buffer = require('vinyl-buffer');
var es = require('event-stream');
var flatten = require('gulp-flatten');
var fs = require('fs');
var glob = require('glob');
var gulp = require('gulp');
var gutil = require('gulp-util');
var scss = require('gulp-sass');
var plumber = require('gulp-plumber');
var source = require('vinyl-source-stream');
var vueify = require('vueify');
var livereload = require('gulp-livereload');

var paths = {
    src: {
        dir: 'resources/',
        js: 'resources/assets/js/*.*',
        scss: 'resources/assets/sass/**/*.*'
    },
    watch: {
        js: 'resources/assets/js/**/*.*',
        blade: 'resources/**/*.blade.php',
        scss: 'resources/assets/sass/**/*.*'
    },
    dest: {
        dir: 'public/',
        js: 'public/js',
        css: 'public/css/'
    }
};

var announce = (message) => { gutil.log(gutil.colors.green(message)); };
var error = (e) => {
    if (e.formatted) {
        gutil.log(gutil.colors.yellow(`${e.name} on in task ${e.plugin}.`));
        gutil.log(gutil.colors.yellow(`Line ${e.line}, Column ${e.column} in ${e.relativePath}.`));
        console.log(gutil.colors.red(e.formatted));
    } else {
        // This is probably a babel error, which is different from a normal gulp error.
        // gutil.log(gutil.colors.red(`Line ${e.loc.line}, Column ${e.loc.column} in ${e.filename}.`));
        // console.log(Object.keys(e.codeFrame));npm i
        console.log(e);
    }
};

gulp.task('start', function () {
    announce('Gulpin\' Membership Avenue');
});

gulp.task('bundle', function (done) {
    announce('Bundling that sweet sweet ES6.');
    glob(paths.src.js, function (err, files) {
        if (err) plumber(err);
        var tasks = files.map(function (entry) {
            announce(`Bundling ${entry}...`);
            return browserify({ entries: entry })
                .transform(babelify, {presets: ["es2015"]})
                .transform(vueify)
                .bundle()
                .on('error', function (err) {
                    error(err);
                    done();
                })
                .pipe(source(entry))
                .pipe(flatten())
                .pipe(gulp.dest(paths.dest.js))
                .pipe(livereload());
        });
        es.merge(tasks).on('end', done);
    });
});

gulp.task('scss', function () {
    announce('SCSS is gunna get all CSSified.');
    return gulp.src(paths.src.scss)
        .pipe(plumber())
        .pipe(scss())
        .pipe(autoprefixer({
            browsers: ['last 3 versions']
        }))
        .on('error', error)
        .pipe(gulp.dest(paths.dest.css))
        .pipe(livereload());
});

gulp.task('reload', function () {
    gulp.src(paths.watch.blade)
        .pipe(livereload());
});

gulp.task('watch', ['production'], function () {
    livereload.listen();
    gulp.watch(paths.watch.js, ['bundle']);
    gulp.watch(paths.watch.scss, ['scss']);
    gulp.watch(paths.watch.blade, ['reload']);
});

gulp.task('deploy', ['scss', 'bundle']);


gulp.task('default', ['watch'], function () {
    announce('I\'m watching you... 👓');
});

gulp.task('production', ['start', 'scss', 'bundle'], function (done) {
    announce('Ready for production!');
    done();
});