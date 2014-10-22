module.exports = function( grunt ) {
	'use strict';

	// Load all grunt tasks
    require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

	var jsFileList = [
		'js/navigation.js',
		'js/skip-link-focus-fix.js',
		'bower_components/bootstrap-sass-official/assets/javascripts/bootstrap.js',
		'bower_components/masonry/dist/masonry.pkgd.js',
		'bower_components/turnjs4/lib/scissor.js',
		'bower_components/turnjs4/lib/turn.js',
		'bower_components/imagesloaded/imagesloaded.pkgd.js',
		'bower_components/bxslider-4/jquery.bxslider.js',
		'js/theme.js'

	];    

	// Project configuration
	grunt.initConfig( {
		pkg:    grunt.file.readJSON( 'package.json' ),
		jshint: {
			all: [
				'Gruntfile.js',
				'js/**/*.js',
				'!js/**/*.min.js',
				'!js/bower_components/**/*.js',
				'!js/scripts.js'
			],
			options: {
				curly:   true,
				eqeqeq:  true,
				immed:   true,
				latedef: true,
				newcap:  true,
				noarg:   true,
				sub:     true,
				undef:   true,
				boss:    true,
				eqnull:  true,
				globals: {
					exports: true,
					module:  false,
					"jQuery": true,
					"$": true,
					"console": true,
					"wp": true,
					"require": true,
					"document": true,
					"window": true,
					"location": true,
					"navigator": true,
					"screen": true
				}
			}		
		},
		cssmin: {
			minify: {
				expand: true,
				src: ['style.css'],
				ext: '.css'
			}
		},
		cssjanus: {
			dev: {
				options: {
					swapLtrRtlInUrl: false
				},
				src: ['style.css'],
				dest: 'rtl.css'
			}
		},
		concat: {
			options: {
				separator: ';'
			},
			dist: {
				src: [jsFileList],
				dest: 'js/scripts.js'
			}
		},
		uglify: {
			dist: {
				files: {
					'js/scripts.min.js': [jsFileList]
				}
			}
		},		
		watch:  {
			compass: {
				files: ['sass/**/*.scss'],
				tasks: ['compass:dev', 'cssjanus:dev'],
				options: {
					debounceDelay: 500,
					livereload: true
				}
			},
			scripts: {
				files: ['js/**/*.js'],
				tasks: ['jshint', 'uglify'],
				options: {
					debounceDelay: 500
				}
			}
		},
		compass: {
			dev: {
				options: {
					sassDir: 'sass',
					cssDir: '.',
					outputStyle: 'compressed',
					require: 'sass-css-importer'
				}
			}
		},
		imagemin: {
			build: {
				files: [{
					expand: true,                // Enable dynamic expansion
					cwd: './images/',            // Src matches are relative to this path
					src: ['**/*.{png,jpg,gif}'], // Actual patterns to match
					dest: './images/'
				}],
				options: {
					optimizationLevel: 7
				}
			}
		}
	});
	
	// Default task.
	grunt.registerTask( 'default', [ 'compass', 'cssmin', 'cssjanus:dev', 'jshint', 'concat' ] );
	// Build task
	grunt.registerTask( 'build',   [ 'cssmin', 'jshint', 'uglify', 'imagemin:build' ] );

	grunt.util.linefeed = '\n';
};