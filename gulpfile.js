var elixir = require('laravel-elixir');
var rename = require('gulp-rename');
var gulp = require('gulp');

/**
 * Copy any needed files.
 *
 * Do a 'gulp copyfiles' after bower updates
 */
 gulp.task("copyfiles", function() {
   // Copy jQuery, Bootstrap, and FontAwesome

  gulp.src("vendor/bower_dl/jquery/dist/jquery.js")
    .pipe(gulp.dest("resources/assets/js/"));

  gulp.src("vendor/bower_dl/bootstrap/less/**")
    .pipe(gulp.dest("resources/assets/less/bootstrap"));

  gulp.src("vendor/bower_dl/bootstrap/dist/js/bootstrap.js")
    .pipe(gulp.dest("resources/assets/js/"));

  gulp.src("vendor/bower_dl/bootstrap/dist/fonts/**")
    .pipe(gulp.dest("public/assets/fonts"));

  gulp.src("vendor/bower_dl/font-awesome/less/**")
      .pipe(gulp.dest("resources/assets/less/fontawesome"));

  gulp.src("vendor/bower_dl/font-awesome/fonts/**")
      .pipe(gulp.dest("public/assets/fonts"));

  // Copy datatables
  var dtDir = 'vendor/bower_dl/datatables-plugins/integration/';

  gulp.src("vendor/bower_dl/datatables/media/js/jquery.dataTables.js")
      .pipe(gulp.dest('resources/assets/js/'));

  gulp.src(dtDir + 'bootstrap/3/dataTables.bootstrap.css')
      .pipe(rename('dataTables.bootstrap.less'))
      .pipe(gulp.dest('resources/assets/less/others/'));

  gulp.src(dtDir + 'bootstrap/3/dataTables.bootstrap.js')
      .pipe(gulp.dest('resources/assets/js/'));

  //Copy selectize
  gulp.src("vendor/bower_dl/selectize/dist/css/**")
      .pipe(gulp.dest("public/assets/selectize/css"));

  gulp.src("vendor/bower_dl/selectize/dist/js/standalone/selectize.min.js")
      .pipe(gulp.dest("public/assets/selectize/"));

  // Copy pickadate
  gulp.src("vendor/bower_dl/pickadate/lib/compressed/themes/**")
      .pipe(gulp.dest("public/assets/pickadate/themes/"));

  gulp.src("vendor/bower_dl/pickadate/lib/compressed/picker.js")
      .pipe(gulp.dest("public/assets/pickadate/"));

  gulp.src("vendor/bower_dl/pickadate/lib/compressed/picker.date.js")
      .pipe(gulp.dest("public/assets/pickadate/"));

  gulp.src("vendor/bower_dl/pickadate/lib/compressed/picker.time.js")
      .pipe(gulp.dest("public/assets/pickadate/"));
});


/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    //mix.phpUnit();
       //.sass('app.scss');

    //conbine scripts, the conbined file will be 'public/assets/js/admin.js'
    mix.scripts([
        'js/jquery.js',//these js file are actually copied to resources/assets/js folder by gulp
        'js/bootstrap.js',
        'js/jquery.dataTables.js',
        'js/dataTables.bootstrap.js'
      ],
      'public/assets/js/admin.js',
      'resources/assets'
    );

    //compile less file, the compiled file will be 'public/assets/css/admin.css'
    mix.less('admin.less', 'public/assets/css/admin.css');

});
