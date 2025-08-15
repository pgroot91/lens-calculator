const { defineConfig } = require("cypress");

module.exports = defineConfig({
  downloadsFolder: "tests/e2e/cypress/downloads",
  fixturesFolder: "tests/e2e/cypress/fixtures",
  screenshotsFolder: "tests/e2e/cypress/reports/screenshots",
  videosFolder: "tests/e2e/cypress/reports/videos",
  video: true,
  e2e: {
    specPattern: "tests/e2e/cypress/integration/**/*.spec.{js,jsx,ts,tsx}",
    supportFile: "tests/e2e/cypress/support/e2e.js",
    setupNodeEvents(on, config) {},
  },
});
