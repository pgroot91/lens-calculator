const { defineConfig } = require("cypress");
const { exec } = require("child_process");

function runScript(script) {
  return new Promise((resolve, reject) => {
    exec(`bash ${script}`, (error, stdout, stderr) => {
      if (error) {
        console.error(`❌ Script error: ${stderr}`);
        return reject(error);
      }
      console.log(`✅ Script output: ${stdout}`);
      resolve(true);
    });
  });
}

module.exports = defineConfig({
  downloadsFolder: "tests/e2e/cypress/downloads",
  fixturesFolder: "tests/e2e/cypress/fixtures",
  screenshotsFolder: "tests/e2e/cypress/reports/screenshots",
  videosFolder: "tests/e2e/cypress/reports/videos",
  video: true,
  e2e: {
    specPattern: "tests/e2e/cypress/integration/**/*.spec.{js,jsx,ts,tsx}",
    supportFile: "tests/e2e/cypress/support/e2e.js",
    setupNodeEvents(on, config) {
      on("task", {
        setupWordPressTestData() {
          return runScript("scripts/setup-test-pages.sh");
        },
        cleanupWordPressTestData() {
          return runScript("scripts/cleanup-test-pages.sh");
        },
      });
    },
  },
});
