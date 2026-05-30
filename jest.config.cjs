module.exports = {
  transform: {
    "^.+\\.js$": "babel-jest",
    "^.+\\.mjs$": "babel-jest",
  },
  testEnvironment: "jsdom",
  testMatch: [
    "**/tests/unit/js/**/*.test.js",
    "**/tests/unit/js/**/*.test.mjs",
  ],
  moduleNameMapper: {
    '^@js/(.*)$': '<rootDir>/js/$1'
  },
  globals: {
    "ts-jest": {
      useESM: true,
    },
  },

  // ✅ ADDITIONS BELOW (non-breaking)

  collectCoverage: true,
  coverageDirectory: "coverage/js",
  coverageReporters: ["text", "lcov", "html"],

  collectCoverageFrom: [
    "js/**/*.js",
    "!js/**/*.test.js",
    "!**/node_modules/**"
  ],

  coveragePathIgnorePatterns: [
    "/node_modules/",
    "/tests/"
  ],

  coverageThreshold: {
    global: {
      branches: 70,
      functions: 75,
      lines: 80,
      statements: 80,
    },
  },
};