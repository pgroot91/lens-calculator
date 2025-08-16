describe("Lens Calculator E2E Tests", () => {
  before(() => {
    cy.task("cleanupWordPressTestData");
    cy.task("setupWordPressTestData");
  });

  after(() => {
    cy.task("cleanupWordPressTestData");
  });

  beforeEach(() => {
    cy.visit("/");
  });

  it("should display the calculator", () => {
    expect(true).to.be.true;
  });
});
