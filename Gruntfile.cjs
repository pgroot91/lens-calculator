/*
 * grunt-cli
 * http://gruntjs.com/
 *
 * Copyright (c) 2016 Tyler Kellen, contributors
 * Licensed under the MIT license.
 * https://github.com/gruntjs/grunt-init/blob/master/LICENSE-MIT
 */

"use strict";

module.exports = function (grunt) {
  // Project configuration.
  grunt.initConfig({
    makepot: {
      target: {
        options: {
          cwd: "./", // Directory of files to internationalize.
          domainPath: "languages", // Where to save the POT file.
          exclude: [
            "node_modules",
            "vendor",
            "tests",
            "docs",
            "readme",
            "dist",
            "css",
            "js",
            "scripts"
          ], // List of files or directories to ignore.
          i18nToolsPath: "", // Path to the i18n tools directory.
          mainFile: "lens-calculator.php", // Main project file.
          potComments: "", // The copyright at the beginning of the POT file.
          potFilename: "lens-calculator.pot", // Name of the POT file.
          potHeaders: {}, // Headers to add to the generate POT file.
          processPot: function (pot, options) {
            pot.headers["report-msgid-bugs-to"] =
              "https://wordpress.org/support/plugin/lens-calculator";
            pot.headers["language-team"] =
              "Patrick Groot <info@patrickgroot.com>";
            pot.headers["last-translator"] =
              "Patrick Groot <info@patrickgroot.com>";
            return pot;
          }, // A callback function for manipulating the POT file.
          type: "wp-plugin", // Type of project (wp-plugin or wp-theme).
          updateTimestamp: false, // Whether the POT-Creation-Date should be updated without other changes.
        },
      },
    },
  });

  // These plugins provide necessary tasks.
  grunt.loadNpmTasks("grunt-wp-i18n");
};
