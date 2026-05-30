export function initCustomDropdowns(wrapperSelector = ".custom-select-wrapper") {
  document.querySelectorAll(wrapperSelector).forEach((wrapper) => {
    const select = wrapper.querySelector("select");
    const customSelect = wrapper.querySelector(".custom-select");
    const trigger = wrapper.querySelector(".custom-select-trigger");
    const optionsContainer = wrapper.querySelector(".custom-options");
    const label = document.querySelector(`label[for="${select.id}"]`);

    // --- Populate custom dropdown from <select> ---
    optionsContainer.innerHTML = ""; // clear to avoid duplicates
    Array.from(select.options).forEach((option) => {
      const div = document.createElement("div");
      const spanOne = document.createElement("span");
      const spanTwo = document.createElement("span");

      const [main, sub] = option.textContent.split(" — ");
      div.classList.add("custom-option");
      div.dataset.value = option.value;

      spanOne.textContent = main || "";
      spanTwo.textContent = sub || "";

      div.append(spanOne, spanTwo);

      if (option.selected) div.classList.add("selected");
      optionsContainer.appendChild(div);
    });

    // --- Set initial trigger text and color ---
    const setTriggerFromSelect = () => {
      const selectedOption = select.options[select.selectedIndex];
      //const [main, sub] = selectedOption.textContent.split(" — ");
      //trigger.innerHTML = `<span>${main || ""}</span><span>${sub || ""}</span>`;
      trigger.innerHTML = selectedOption.textContent;
      trigger.style.color = select.selectedIndex > 0 ? "#333" : "#999";
      optionsContainer.querySelectorAll(".custom-option").forEach((opt) => {
        opt.classList.toggle(
          "selected",
          opt.dataset.value === selectedOption.value
        );
      });
    };
    setTriggerFromSelect();

    // --- Toggle dropdown ---
    const toggleDropdown = (e) => {
      e.stopPropagation();
      document.querySelectorAll(".custom-select.open").forEach((openSelect) => {
        if (openSelect !== customSelect) openSelect.classList.remove("open");
      });
      customSelect.classList.toggle("open");
    };

    // Open/close via trigger
    trigger.addEventListener("click", toggleDropdown);

    // Also open via label
    if (label) {
      label.style.cursor = "pointer";
      label.addEventListener("click", (e) => {
        e.preventDefault();
        select.focus(); // applies focus styles
        toggleDropdown(e);
      });
    }

    // --- Option click handling ---
    optionsContainer.addEventListener("click", (e) => {
      const optionDiv = e.target.closest(".custom-option");
      if (optionDiv) {
        select.value = optionDiv.dataset.value;
        setTriggerFromSelect();
        customSelect.classList.remove("open");
      }
    });

    // --- Focus/blur styling ---
    select.addEventListener("focus", () => {
      wrapper.classList.add("focused");
    });
    select.addEventListener("blur", () => {
      wrapper.classList.remove("focused");
    });

    // --- Reset handling ---
    const form = wrapper.closest("form");
    if (form) {
      form.addEventListener("reset", () => {
        setTimeout(() => {
          setTriggerFromSelect();
        }, 0);
      });
    }
  });

  // --- Close dropdowns on outside click ---
  document.addEventListener("click", () => {
    document.querySelectorAll(".custom-select.open").forEach((openSelect) => {
      openSelect.classList.remove("open");
    });
  });
}
