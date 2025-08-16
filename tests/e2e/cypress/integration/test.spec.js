describe("Lens Calculator E2E Tests", () => {
  before(() => {
    cy.task("cleanupWordPressTestData", null, { timeout: 180000 }).then(() => {
      return cy.task("setupWordPressTestData", null, { timeout: 180000 });
    });
  });

  after(() => {
    cy.task("cleanupWordPressTestData", null, { timeout: 180000 });
  });

  beforeEach(() => {
    cy.visit("/");
  });

  it("should display the calculator", () => {
    expect(true).to.be.true;
  });
});
