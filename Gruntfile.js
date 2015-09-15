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
            tasks: ['svgmin'],
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
                src : ['**/*.css', '**/*.html', '*.php', '**/*.js'],
            },
            options: {
                watchTask: true,
                proxy: "incoming.ncll.dev",
            }
        }
    },
    svgmin: {
        options: {
            plugins: [
                { removeViewBox: false },
                { removeUselessStrokeAndFill: false },
            ]
        },
        creditCards: {
            files: [{
                expand: true,
                cwd: 'SVG/raw',
                src: '*.svg',
                dest: 'SVG/compressed',
            }],
        },
        others: {
            files: [{
                expand: true,
                src: 'SVG/*.svg',
                dest: 'images/',
            }],
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
    grunt.loadNpmTasks('grunt-svgmin');
    grunt.loadNpmTasks('grunt-postcss');
    grunt.loadNpmTasks('grunt-browser-sync');
    grunt.loadNpmTasks('grunt-newer');
    grunt.registerTask('default', [
        'browserSync',
        'watch',
    ]);
};
