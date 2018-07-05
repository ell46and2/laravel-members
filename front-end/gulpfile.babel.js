/*----------------------------------------------*\
    IMPORT PLUGINS
\*----------------------------------------------*/
import autoprefixer from 'gulp-autoprefixer';
import babelify from 'babelify';
import browserify from 'browserify';
import browserSync from 'browser-sync';
import buffer from 'vinyl-buffer';
import cleancss from 'gulp-clean-css';
import colors from 'ansi-colors';
import data from 'gulp-data';
import del from 'del';
import eslint from 'gulp-eslint';
import fs from 'fs';
import gulp from 'gulp';
import gulpif from 'gulp-if';
import log from 'fancy-log';
import sass from 'gulp-sass';
import moment from 'moment';
import nunjucks from 'gulp-nunjucks-render';
import plumber from 'gulp-plumber';
import prettify from 'gulp-html-prettify';
import sourcemaps from 'gulp-sourcemaps';
import svgmin from 'gulp-svgmin';
import svgsymbols from 'gulp-svg-symbols';
import uglify from 'gulp-uglify';
import vinyl from 'vinyl-source-stream';

/*----------------------------------------------*\
    MODES
    Define one or more "modes", which can be
    specified when running the Gulp command.
\*----------------------------------------------*/
const modes = {
    static: {
        css: false,
        js: false,
        images: false,
        svg: false,
        templates: ".public",
        clean: ".public",
        serve: ".public"
    }
}

/*----------------------------------------------*\
    BROWSERS
    Set which browsers should be supported by
    autoprefixer.
\*----------------------------------------------*/
const browsers = [
    "last 1 major version",
    ">= 1%",
    "Chrome >= 45",
    "Firefox >= 38",
    "Edge >= 12",
    "Explorer >= 10",
    "iOS >= 9",
    "Safari >= 9",
    "Android >= 4.4",
    "Opera >= 30"
]

/*----------------------------------------------*\
    SOURCES
    Specify asset source directories/files.
\*----------------------------------------------*/
const src = {
    css: "scss",
    js: "js",
    images: "images",
    svg: "svg",
    templates: "templates",
    data: "data.json"
}

/*###################################################################
####################################################################*\
    HERE BE MONSTERS!
    You shouldn't need to edit anything below
    this point. Proceed at your own risk!
\*###################################################################
####################################################################*/

/*----------------------------------------------*\
    GET COMMAND LINE ARGUMENTS
    Parse any arguments passed with the gulp
    command and store them in an "arg" object.
\*----------------------------------------------*/
const arg = (argList => {

    let arg = {}, a, opt, thisOpt, curOpt;

    for (a = 0; a < argList.length; a++) {

        thisOpt = argList[a].trim();
        opt = thisOpt.replace(/^\-+/, '');

        if (opt === thisOpt) {
            // argument value
            if (curOpt) arg[curOpt] = opt;
            curOpt = null;
        }
        else {
            // argument name
            curOpt = opt;
            arg[curOpt] = true;
        }

    }

    return arg;

})(process.argv);

/*----------------------------------------------*\
    SELECTED MODE
    Create a mode object for the selected mode.
\*----------------------------------------------*/
const mode = modes[arg.m || arg.mode];

/*----------------------------------------------*\
    ARE WE IN PRODUCTION?
\*----------------------------------------------*/
const isProduction = arg.production;

/*----------------------------------------------*\
    CSS
\*----------------------------------------------*/
function css(done) {
    if(mode.css) {
        gulp.src(src.css + '/**/*.scss')
            .pipe(plumber())
            .pipe(sourcemaps.init())
            .pipe(sass().on('error', sass.logError))
            .pipe(autoprefixer({
                browsers: browsers,
                cascade: false
            }))
            .pipe(cleancss())
            .pipe(sourcemaps.write('.'))
            .pipe(gulp.dest(mode.css))
            .on('end', function(){ log(colors.cyan('CSS processed')); })
            .pipe(gulpif(mode.serve != false, browserSync.stream()));
    }
    else {
        let now = '[' + moment().format('HH:mm:ss') + '] ';
        console.log(colors.red(now + 'CSS skipped'));
    }

    done();
}

/*----------------------------------------------*\
    JS
\*----------------------------------------------*/
function js(done) {
    if(mode.js) {

        return browserify(src.js + '/index.js')
            .transform('babelify', {
                sourceMaps: true
            })
            .bundle()
            .pipe(vinyl('bundle.js'))
            .pipe(buffer())
            .pipe(sourcemaps.init({loadMaps: true}))
            .pipe(gulpif(isProduction, uglify()))
            .pipe(sourcemaps.write('./'))
            .pipe(gulp.dest(mode.js))
            .on('end', function(){ log(colors.cyan('JS processed')); })
            .pipe(gulpif(mode.serve != false, browserSync.stream()));
    }
    else {
        let now = '[' + moment().format('HH:mm:ss') + '] ';
        console.log(colors.red(now + 'JS skipped'));
    }

    done();
}

/*----------------------------------------------*\
    IMAGES
\*----------------------------------------------*/
function images(done) {
    if(mode.images) {
        gulp.src(src.images + '/**/*')
            .pipe(plumber())
            .pipe(gulp.dest(mode.images))
            .on('end', function(){ log(colors.cyan('Images processed')); })
            .pipe(gulpif(mode.serve != false, browserSync.stream()));
    }
    else {
        let now = '[' + moment().format('HH:mm:ss') + '] ';
        console.log(colors.red(now + 'Images skipped'));
    }

    done();
}

/*----------------------------------------------*\
    SVG
\*----------------------------------------------*/
function svg(done) {
    if(mode.svg) {
        gulp.src(src.svg + '/*.svg')
            .pipe(plumber())
            .pipe(svgmin())
            .pipe(svgsymbols({
                templates: ['default-svg'],
                svgAttrs: {
                    class: 'icon',
                    'aria-hidden': 'true',
                    'role': 'presentation'
                }
            }))
            .pipe(gulp.dest(mode.svg))
            .on('end', function(){ log(colors.cyan('SVGs processed')); })
            .pipe(gulpif(mode.serve != false, browserSync.stream()));
    }
    else {
        let now = '[' + moment().format('HH:mm:ss') + '] ';
        console.log(colors.red(now + 'SVGs skipped'));
    }

    done();
}

/*----------------------------------------------*\
    TEMPLATES
\*----------------------------------------------*/
function templates(done) {
    if(mode.templates) {
        gulp.src(src.templates + '/*.html')
            .pipe(plumber())
            .pipe(data(function(file) {
                return JSON.parse(fs.readFileSync(src.data));
            }))
            .pipe(nunjucks({
                path: src.templates
            }))
            .pipe(prettify({indent_size: 4}))
            .pipe(gulp.dest(mode.templates))
            .on('end', function(){ log(colors.cyan('Templates processed')); })
            .pipe(gulpif(mode.serve != false, browserSync.stream()));
    }
    else {
        let now = '[' + moment().format('HH:mm:ss') + '] ';
        console.log(colors.red(now + 'Templates skipped'));
    }

    done();
}

/*----------------------------------------------*\
    CLEAN
\*----------------------------------------------*/
const clean = () => del(mode.clean, {force: true});

/*----------------------------------------------*\
    BUILD ALL ASSETS
\*----------------------------------------------*/
const buildAll = gulp.parallel(css, js, images, svg, templates);

/*----------------------------------------------*\
    SERVE
\*----------------------------------------------*/
function serve(done) {
    if(mode.serve) {
        setTimeout(function(){
            browserSync({
                server: {
                    baseDir: mode.serve
                }
            });
        }, 1000);
    }

    done();
}

/*----------------------------------------------*\
    WATCH
\*----------------------------------------------*/
function watchFiles() {
    if(mode.css) { gulp.watch(src.css + '/**/*.scss', css); }
    if(mode.js) { gulp.watch(src.js + '/**/*.js', js); }
    if(mode.images) { gulp.watch(src.images + '/**/*', images); }
    if(mode.svg) { gulp.watch(src.svg + '/**/*', svg); }
    if(mode.templates) {
        gulp.watch(src.templates + '/**/*.html', templates);
        gulp.watch(src.data, templates);
    }
}

/*----------------------------------------------*\
    TASKS
\*----------------------------------------------*/
export const watch = gulp.series(clean, buildAll, serve, watchFiles);

export const build = gulp.series(clean, buildAll, serve);

export default build;
