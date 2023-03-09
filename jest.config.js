module.exports = {
    collectCoverage: true,
    coverageDirectory: "node_modules/.coverage",
    coverageProvider: "v8",
    coverageReporters: ["json-summary", "text", "clover"],
    // Mocking the import to something else to prevent the test from failing on this import
    moduleNameMapper: {
        "web-worker:./encryption-worker": "./util",
    },
    testEnvironment: "jsdom",
};
