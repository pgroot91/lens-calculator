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
};
