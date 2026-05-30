import { initCustomDropdowns } from "@js/dropdown";

describe("initCustomDropdowns", () => {
  beforeEach(() => {
    document.body.innerHTML = `
      <form>
        <div class="custom-select-wrapper">
          <label for="test-select">Label</label>
          <select id="test-select">
            <option value="" selected>Choose</option>
            <option value="1">Option 1</option>
            <option value="2">Option 2</option>
          </select>
          <div class="custom-select">
            <div class="custom-select-trigger"></div>
            <div class="custom-options"></div>
          </div>
        </div>
      </form>
    `;
  });

  test("populates custom options from select", () => {
    initCustomDropdowns();

    const options = document.querySelectorAll(".custom-option");
    expect(options.length).toBe(3);
  });

  test("sets initial trigger text", () => {
    initCustomDropdowns();

    const trigger = document.querySelector(".custom-select-trigger");
    expect(trigger.textContent).toBe("Choose");
  });

  test("toggles dropdown on trigger click", () => {
    initCustomDropdowns();

    const trigger = document.querySelector(".custom-select-trigger");
    const dropdown = document.querySelector(".custom-select");

    trigger.click();
    expect(dropdown.classList.contains("open")).toBe(true);

    trigger.click();
    expect(dropdown.classList.contains("open")).toBe(false);
  });

  test("selecting option updates value", () => {
    initCustomDropdowns();

    const option = document.querySelector('[data-value="2"]');
    option.click();

    const select = document.querySelector("select");
    expect(select.value).toBe("2");
  });

  test("click outside closes dropdown", () => {
    initCustomDropdowns();

    const trigger = document.querySelector(".custom-select-trigger");
    const dropdown = document.querySelector(".custom-select");

    trigger.click();
    expect(dropdown.classList.contains("open")).toBe(true);

    document.body.click();
    expect(dropdown.classList.contains("open")).toBe(false);
  });

  test("label click opens dropdown", () => {
    initCustomDropdowns();

    const label = document.querySelector("label");
    const dropdown = document.querySelector(".custom-select");

    label.click();
    expect(dropdown.classList.contains("open")).toBe(true);
  });

  test("form reset resets trigger", async () => {
    initCustomDropdowns();

    const select = document.querySelector("select");
    const form = document.querySelector("form");

    // change value first
    select.value = "2";

    form.reset();

    await new Promise((r) => setTimeout(r, 0));

    const trigger = document.querySelector(".custom-select-trigger");

    expect(trigger.textContent).toBe("Choose");
  });

  test("works without label element", () => {
    document.body.innerHTML = `
    <div class="custom-select-wrapper">
      <select id="test-select">
        <option value="" selected>Choose</option>
        <option value="1">Option 1</option>
      </select>
      <div class="custom-select">
        <div class="custom-select-trigger"></div>
        <div class="custom-options"></div>
      </div>
    </div>
  `;

    expect(() => initCustomDropdowns()).not.toThrow();
  });

  test("works without form", () => {
    document.body.innerHTML = `
    <div class="custom-select-wrapper">
      <select id="test-select">
        <option value="" selected>Choose</option>
        <option value="1">Option 1</option>
      </select>
      <div class="custom-select">
        <div class="custom-select-trigger"></div>
        <div class="custom-options"></div>
      </div>
    </div>
  `;

    expect(() => initCustomDropdowns()).not.toThrow();
  });

  test("default option sets grey color", () => {
    document.body.innerHTML = `
    <div class="custom-select-wrapper">
      <select id="test-select">
        <option value="" selected>Choose</option>
        <option value="1">Option 1</option>
      </select>
      <div class="custom-select">
        <div class="custom-select-trigger"></div>
        <div class="custom-options"></div>
      </div>
    </div>
  `;

    initCustomDropdowns();

    const trigger = document.querySelector(".custom-select-trigger");

    expect(trigger.style.color).toBe("rgb(153, 153, 153)");
  });

  test("removes focused class on blur", () => {
    document.body.innerHTML = `
    <div class="custom-select-wrapper">
      <select id="test-select">
        <option value="" selected>Choose</option>
        <option value="1">Option 1</option>
      </select>
      <div class="custom-select">
        <div class="custom-select-trigger"></div>
        <div class="custom-options"></div>
      </div>
    </div>
  `;

    initCustomDropdowns();

    const wrapper = document.querySelector(".custom-select-wrapper");
    const select = document.querySelector("select");

    // simulate focus first (important)
    select.dispatchEvent(new Event("focus"));

    expect(wrapper.classList.contains("focused")).toBe(true);

    // now blur
    select.dispatchEvent(new Event("blur"));

    expect(wrapper.classList.contains("focused")).toBe(false);
  });
});
