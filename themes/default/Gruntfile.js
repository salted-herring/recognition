module.exports = function(grunt) {

	grunt.initConfig({
		compass: {
			dist: {
				options: {
					sassDir: 'scss',
					cssDir: 'css',
					environment: 'production',
					outputStyle: 'compressed'
				}
			}
		},

		/*
imageoptim: {
			files: ['img'],
			options: {
				jpegMini: false,
				imageAlpha: true,
				quitAfter: false
			}
		},
*/

		watch: {
			css: {
				files: 'scss/*.scss',
				tasks: ['compass:dist'],
				options: {
					event: ['all'],
					livereload: true,
				},
			},
			sprites: {
				files: 'img/sprites/*.png',
				tasks: ['compass:dist'],
				options: {
					event: ['all'],
					livereload: true,
				}
			},
			js: {
				files: ['js/pagetypes/*.js'],
				task: ['jshint']
			}
		},

		lintspaces: {
            all: {
                src: [
                    '../../mainsite/code/*',
                    'templates/*',
                    'scss/*'
                ],
                options: {
                    editorconfig: '../../.editorconfig'
                }
            }
        }
	});

	grunt.loadNpmTasks('grunt-contrib-compass');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-lintspaces');

	grunt.registerTask('default', ['compass:dist']);
	grunt.registerTask('production', ['compass:dist']);//, 'imageoptim']);
}
