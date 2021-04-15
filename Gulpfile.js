var gulp = require('gulp');

var $ = require('gulp-load-plugins')({
    pattern: ['gulp-*', 'main-bower-files', 'del']
});

var series = require('stream-series');

var spritesmith = require('gulp.spritesmith');
var wiredep = require('wiredep').stream;

gulp.task('styles', function () {
    var injectAppFiles = gulp.src([
        'resources/assets/sass/styles/**/*.scss', 'resources/assets/sass/components/**/*.scss' , 'resources/assets/sass/pages/**/*.scss'
    ], {read: false});
    var injectGlobalFiles = gulp.src('resources/assets/sass/global/*.scss', {read: false});
    function transformFilepath(filepath) {
        return '@import "' + filepath + '";';
    }

    var injectAppOptions = {
        transform: transformFilepath,
        starttag: '// inject:app',
        endtag: '// endinject',
        addRootSlash: false
    };

    var injectGlobalOptions = {
        transform: transformFilepath,
        starttag: '// inject:global',
        endtag: '// endinject',
        addRootSlash: false
    };
    return gulp.src('resources/assets/sass/main.scss').pipe(wiredep()).pipe($.inject(injectGlobalFiles, injectGlobalOptions)).pipe($.inject(injectAppFiles, injectAppOptions)).pipe($.sass().on('error', $.sass.logError)).pipe(gulp.dest('public/css/'));
});

gulp.task('sprite', function () {
    // Generate our spritesheet
    var spriteData = gulp.src('resources/assets/images/icons/*.png').pipe(spritesmith({
        retinaSrcFilter: 'resources/assets/images/icons/*@2x.png',
        retinaImgName: 'sprite@2x.png',
        retinaImgPath: '/images/icons/sprite@2x.png',
        imgName: 'sprite.png',
        cssName: 'icons.scss',
        imgPath: '/images/icons/sprite.png',
        padding: 5,
        cssVarMap: function (sprite) {
            sprite.name = 'g-icon_' + sprite.name;
        }
    }));

    var imgStream = spriteData.img.pipe($.buffer()).pipe(gulp.dest('public/images/icons/'));
    // Todo image min

    // Pipe CSS stream through CSS optimizer and onto disk
    var cssStream = spriteData.css.pipe(gulp.dest('resources/assets/sass/styles/'));

    // Return a merged stream to handle both `end` events
    return $.merge(imgStream, cssStream);
});

gulp.task('clean', function () {
    return $.del(['public/dist']);
});

gulp.task('vendors-css', function () {
    var files = $.mainBowerFiles();

    files.push('public/css/vendors/*.css');
    return gulp.src(files, {base: 'bower_components'}).pipe($.filter('**/*.css')).pipe($.concat('vendors.css')).pipe(gulp.dest('public/dist'));
});

gulp.task('concat', ['clean'], function () {
    return gulp.src('public/css/**/*.css').pipe($.concatCss("site.css")).pipe(gulp.dest('public/dist/'));
});

gulp.task('minify-css', ['concat'], function () {
    return gulp.src('dist/*.css').pipe($.cleanCss({compatibility: 'ie8'})).pipe($.rename({suffix: '.min'})).pipe(gulp.dest('public/dist/'));
});
gulp.task('inject', function () {
    var componentJs = gulp.src([
        'public/js/pages/**/*.js', 'public/js/validations/**/*.js', '!public/js/app/*.js'
    ], {
        read: false
    }, {relative: true});

    var mainJs = gulp.src([
        'public/js/*.js', '!public/js/env.js', '!public/js/utils.js'
    ], {
        read: false
    }, {relative: true});

    return gulp.src('resources/views/partials/global/*.blade.php').pipe($.inject(series(componentJs, mainJs), {
        ignorePath: 'public',
        addRootSlash: true
    })).pipe(gulp.dest('resources/views/partials/global/'));
})

gulp.task('vendors-js', function () {
    var files = $.mainBowerFiles();
    files.push('public/js/vendors/*.js');

    return gulp.src(files, {base: 'bower_components'}).pipe($.filter(['**/*.js', '!bower_components/jquery'])).pipe($.concat('vendors.js')).pipe(gulp.dest('public/dist'));
});

gulp.task('watch', function () {
    gulp.watch('resources/assets/sass/**/*.scss', ['styles']);
});

//gulp.task('deploy', ['vendors-css','concat','minify-css','vendors-js']);
gulp.task('build', ['sprite', 'styles', 'vendors-css', 'vendors-js', 'inject']);

gulp.task('default', ['build', 'watch']);
