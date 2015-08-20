module.exports = function (grunt) {
  grunt.initConfig({
    // Watch task config
    watch: {
        styles: {
            files: "SCSS/*.scss",
            tasks: ['sass', 'postcss'],
        },
        svgIcons: {
            files: "SVG/raw/*.svg",
            tasks: ['svgmin', 'svgstore'],
        },
        images: {
            files: ['img/*.{jpg,png,gif,svg}'],
            tasks: ['newer:imagemin:all'],
        },
        js: {
            files: ['js/**.js'],
            tasks: ['newer:uglify'],
        }
    },
    uglify: {
        options: {
        },
        build: {
            files: {
                'js/main.min.js': ['js/main.js'],
            }
        }
    },
    sass: {
        dev: {
            files: {
                "css/style.css" : "SCSS/style.scss"
            }
        }
    },
    postcss: {
        options: {
            map: {
                inline: false,
            },

            processors: [
                require('pixrem')(), // add fallbacks for rem units
                require('autoprefixer-core')({browsers: 'last 2 versions'}), // add vendor prefixes
                require('cssnano')() // minify the result
            ]
        },
        dist: {
            src: 'css/*.css',
        }
    },
    browserSync: {
        dev: {
            bsFiles: {
                src : ['**/*.css', '*.html', '*.php', '**/*.js'],
            },
            options: {
                watchTask: true,
                proxy: "incoming.ncll.dev",
            }
        }
    },
    svgstore: {
        options: {
            prefix : 'icon-', // This will prefix each ID
            svg: { // will add and overide the the default xmlns="http://www.w3.org/2000/svg" attribute to the resulting SVG
                viewBox : '0 0 100 100',
                xmlns: 'http://www.w3.org/2000/svg',
            },
        },
        default: {
            files: {
                'images/icons.svg': ['SVG/compressed/*.svg'],
            },
        },
    },
    imagemin: {
        dynamic: {
            options: {
                svgoPlugins: [
                    { removeViewBox: false },
                    { removeUselessStrokeAndFill: false },
                    { removeUselessDefs: false },
                ],
            },
            files: [{
                expand: true,
                cwd: 'img/',
                src: ['*.{jpg,png,gif,svg}'],
                dest: 'img/',
            }],
        },
    },
  });

    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-svgstore');
    grunt.loadNpmTasks('grunt-postcss');
    grunt.loadNpmTasks('grunt-browser-sync');
    grunt.loadNpmTasks('grunt-newer');
    grunt.registerTask('default', [
        'browserSync',
        'watch',
    ]);
};
