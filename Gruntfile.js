module.exports = function (grunt) {
  grunt.initConfig({
    // Watch task config
    watch: {
        styles: {
            files: "SCSS/*.scss",
            tasks: ['sass', 'postcss'],
        },
        svg: {
            files: "SVG/raw/*.svg",
            tasks: ['svgmin', 'svgstore'],
        },
        icons: {
            files: "images/*",
            tasks: ['imageoptim'],
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
                src : ['*.css', '*.html', '*.php', '**/*.js'],
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
        dist: {
            files: [{
                expand: true,
                cwd: 'SVG/raw',
                src: '*.svg',
                dest: 'SVG/compressed',
            }],
        },
        images: {
            files: [{
                expand: 'true',
                cwd: 'img/',
                src: '*.svg',
                dest: 'img/',
                ext: '.min.svg',
            }],
        },
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
    imageoptim: {
//        svgIcons: {
//            options: {
//                imagealpha: true,
//                jpegMini: false,
//            },
//            src: ['images/icons'],
//        }
    },
  });

    grunt.loadNpmTasks('grunt-imageoptim');
    grunt.loadNpmTasks('grunt-svgmin');
    grunt.loadNpmTasks('grunt-svgstore');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-postcss');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-browser-sync');
    grunt.registerTask('default', [
        'browserSync',
        'watch',
    ]);
};
