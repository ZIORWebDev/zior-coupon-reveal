module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        clean: {
            build: ['build'],
            release: ['release'],
            acf: ['vendor/acf/lang'],
            packgist: ['vendor/wpackagist-plugin']
        }
    });

    grunt.loadNpmTasks('grunt-contrib-clean');

    grunt.registerTask('default', ['clean']);
};