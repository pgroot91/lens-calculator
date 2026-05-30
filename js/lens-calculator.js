import {
  roundedValue,
  computeHeight,
  computeWidth,
  computeFocalLength,
  computeAverageFocal,
  nearestStandardLens,
  nearestRangeStandardLenses,
} from "./lens-calculator-core.js";
import { initCustomDropdowns } from "./dropdown.js";

const sensors = lens_calculator.sensors;

// Suppose this runs when a user clicks on a custom option
document.addEventListener("DOMContentLoaded", () => {
  initCustomDropdowns();
  const form = document.getElementById("wpcl-form");
  if (form) setupDimensionSync(form, sensors);

  if (form) {
    form.addEventListener("reset", () => {
      // let the browser reset native fields first
      setTimeout(() => {
        const advice = document.getElementById("wpcl-advice");
        if (advice) advice.style.display = "none";
      }, 0);
    });
  }

  // Allow decimals and commas (normalize on input), block exponent and explicit +/- only
  document.querySelectorAll('input[type="number"].wplc_field').forEach((input) => {
    input.addEventListener("keydown", (e) => {
      if (["e", "E", "+", "-"].includes(e.key)) e.preventDefault();
    });
    input.addEventListener("input", (e) => {
      const el = e.target;
      if (el && typeof el.value === "string" && el.value.includes(",")) {
        el.value = el.value.replace(/,/g, ".");
      }
    });
  });
});

/**
 * Generic validation function for form inputs
 * @param {number} sensorIndex - Selected sensor index (1-based)
 * @param {number} distance - Distance to object in meters
 * @param {number} size - Object size (width or height) in meters
 * @param {object} messages - { sensor, distance, size } messages for alerts
 * @param {HTMLElement} focusElement - Element to focus if invalid
 * @returns {boolean} - True if inputs are valid, false otherwise
 */
function validateInputs(sensorIndex, distance, size, messages, focusElement) {
  // if (sensorIndex === 0) {
  //   alert(messages.sensor);
  //   return false;
  // }
  // if (isNaN(distance)) {
  //   alert(messages.distance);
  //   focusElement?.focus();
  //   return false;
  // }
  // if (isNaN(size)) {
  //   alert(messages.size);
  //   focusElement?.focus();
  //   return false;
  // }
  return true;
}

/**
 * Compute object height in pixels on the sensor
 * Formula: pixels = (distance × sensorHeight) / objectHeight
 */
function computeHeightForm(form) {
  const sensorIndex = form.answer1.selectedIndex;
  const distance = parseFloat(form.objectafstand.value);
  const objectHeight = parseFloat(form.objecthoogte.value);

  // require sensor selected and a valid height to show advice
  if (sensorIndex === 0 || isNaN(objectHeight)) {
    hideAdvice();
    form.output.value = lens_calculator.nnb;
    return;
  }

  if (
    !validateInputs(
      sensorIndex,
      distance,
      objectHeight,
      {
        sensor: lens_calculator.message1,
        distance: lens_calculator.message2,
        size: lens_calculator.message31,
      },
      form.objecthoogte
    )
  )
    return;

  const pixels = computeHeight(sensorIndex, distance, objectHeight, sensors);
  const pixelsRounded = roundedValue(pixels);
  const lens = nearestRangeStandardLenses(pixelsRounded);
  form.output.value = pixels !== null ? pixels : lens_calculator.nnb;
  showAdvice(lens, pixelsRounded);
}

/**
 * Compute object width in pixels on the sensor
 * Formula: pixels = (distance × sensorWidth) / objectWidth
 */
function computeWidthForm(form) {
  const sensorIndex = form.answer1.selectedIndex;
  const distance = parseFloat(form.objectafstand.value);
  const objectWidth = parseFloat(form.objectbreedte.value);

  // require sensor selected and a valid width to show advice
  if (sensorIndex === 0 || isNaN(objectWidth)) {
    hideAdvice();
    form.output.value = lens_calculator.nnb;
    return;
  }

  if (
    !validateInputs(
      sensorIndex,
      distance,
      objectWidth,
      {
        sensor: lens_calculator.message1,
        distance: lens_calculator.message2,
        size: lens_calculator.message32,
      },
      form.objectbreedte
    )
  )
    return;

  const pixels = computeWidth(sensorIndex, distance, objectWidth, sensors);
  const pixelsRounded = roundedValue(pixels);
  const lens = nearestRangeStandardLenses(pixelsRounded);
  form.output.value = pixels !== null ? pixels : lens_calculator.nnb;
  showAdvice(lens, pixelsRounded);
}

function setupDimensionSync(form, sensors) {
  const widthInput = form.objectbreedte;
  const heightInput = form.objecthoogte;
  const sensorSelect = form.answer1; // this is your <select>
  const distanceInput = form.objectafstand;

  function getAspectRatio() {
    const sensorIndex = sensorSelect.selectedIndex;
    const sensor = sensors[sensorIndex];
    return sensor ? sensor.width / sensor.height : null;
  }

  function syncFromHeight() {
    const r = getAspectRatio();
    const h = parseFloat(heightInput.value);
    if (r && !isNaN(h)) widthInput.value = (h * r).toFixed(1);
  }

  function syncFromWidth() {
    const r = getAspectRatio();
    const w = parseFloat(widthInput.value);
    if (r && !isNaN(w)) heightInput.value = (w / r).toFixed(1);
  }

  function syncFromSensorOrDistance() {
    const aspect = getAspectRatio();
    const width = parseFloat(widthInput.value);
    const height = parseFloat(heightInput.value);

    if (aspect) {
      if (!isNaN(width)) {
        syncFromWidth();
      } else if (!isNaN(height)) {
        syncFromHeight();
      }
    }
  }

  widthInput.addEventListener("input", syncFromWidth);
  heightInput.addEventListener("input", syncFromHeight);

  sensorSelect.addEventListener("change", syncFromSensorOrDistance);
  distanceInput.addEventListener("input", syncFromSensorOrDistance);

  // auto-recompute
  [widthInput, heightInput, sensorSelect, distanceInput].forEach((el) => {
    el.addEventListener("input", () => computeLensForm(form));
    el.addEventListener("change", () => computeLensForm(form));
  });
}

/**
 * Compute recommended lens focal length
 * Uses both width and height to calculate average focal
 */
function computeLensForm(form) {
  const sensorIndex = form.answer1.selectedIndex;
  const distance = parseFloat(form.objectafstand.value);
  const objectWidth = parseFloat(form.objectbreedte.value);
  const objectHeight = parseFloat(form.objecthoogte.value);

  if (
    !validateInputs(
      sensorIndex,
      distance,
      Math.max(objectWidth, objectHeight),
      {
        sensor: lens_calculator.message1,
        distance: lens_calculator.message2,
        size: lens_calculator.message31,
      },
      form.objectafstand
    )
  )
    return;

  // Compute focal lengths
  const fWidth = computeFocalLength(
    sensorIndex,
    distance,
    objectWidth,
    "width",
    sensors
  );
  const fHeight = computeFocalLength(
    sensorIndex,
    distance,
    objectHeight,
    "height",
    sensors
  );

  // Round them to 1 decimal
  const fWidthRounded = roundedValue(fWidth);
  const fHeightRounded = roundedValue(fHeight);
  const fAverage = computeAverageFocal(fWidthRounded, fHeightRounded);
  const fAverageRounded = roundedValue(fAverage);

  // Show the calculated focal length (average of width/height results).
  const calculatedFocal = !isNaN(fAverageRounded) ? fAverageRounded : lens_calculator.nnb;
  if (form.output) form.output.value = calculatedFocal;
  const elById = document.getElementById("focalelengste");
  if (elById) elById.value = calculatedFocal;

  // Only show advice when a sensor is selected AND at least one size is provided
  const hasSize = !isNaN(objectHeight) || !isNaN(objectWidth);
  if (sensorIndex === 0 || !hasSize) {
    hideAdvice();
    return;
  }

  // Suggest lenses based on the calculated focal length:
  // - ask nearestRangeStandardLenses for suggestions around the calculated focal,
  // - if that returns null/undefined, fall back to the pixels-based suggestions.
  const focalSuggestions = nearestRangeStandardLenses(fAverageRounded);
  const pixels = computeHeight(sensorIndex, distance, objectHeight, sensors);
  const pixelsRounded = roundedValue(pixels);
  const pixelsSuggestions = nearestRangeStandardLenses(pixelsRounded);

  const lens = focalSuggestions ?? pixelsSuggestions;
  // pass calculated focal as second param so showAdvice can use it as sensible fallback text
  showAdvice(lens, calculatedFocal);
}

function showAdvice(lens, pixelsRounded) {
  const adviceEl = document.getElementById("wpcl-advice");
  const p0 = document.getElementById("advice-calc-0");
  const p1 = document.getElementById("advice-calc-1");
  if (!adviceEl) return;

  // Build candidates array (keep minimal changes)
  let candidates = [];
  if (lens == null) {
    if (pixelsRounded == null) {
      hideAdvice();
      return;
    }
    candidates = [pixelsRounded];
  } else if (Array.isArray(lens)) {
    candidates = lens.slice();
  } else if (typeof lens === "number" && !isNaN(lens)) {
    candidates = [lens];
  } else {
    if (pixelsRounded == null) {
      hideAdvice();
      return;
    }
    candidates = [pixelsRounded];
  }

  candidates = candidates
    .map((v) => (v == null || isNaN(v) ? null : roundedValue(v)))
    .filter((v) => v != null);

  if (candidates.length === 0) {
    hideAdvice();
    return;
  }

  // If first two are identical -> treat as exact match (only one shown)
  if (candidates.length > 1 && candidates[0] === candidates[1]) {
    candidates = [candidates[0]];
  }

  // Render advice
  adviceEl.style.display = "flex";
  if (p0) {
    p0.textContent = candidates[0];
    p0.style.display = "";
  }

  // Show or hide the entire second fragment (and its preceding "of") based on presence
  if (candidates[1] != null) {
    if (p1) {
      p1.textContent = candidates[1];
      p1.style.display = "";
      const parent = p1.parentElement;
      if (parent) parent.style.display = ""; // show <strong> wrapper

      // restore simple separator text (template uses "of ")
      if (parent && parent.previousSibling && parent.previousSibling.nodeType === 3) {
        parent.previousSibling.textContent = " of ";
      }

      // Also ensure any .advice-sep / .sep elements are shown
      const sepEls = adviceEl.querySelectorAll(".advice-sep, .sep");
      sepEls.forEach((el) => (el.style.display = ""));
    }
  } else {
    if (p1) {
      p1.textContent = "";
      const parent = p1.parentElement;
      if (parent) parent.style.display = "none"; // hide the <strong> wrapper that contains id="advice-calc-1"
      // remove the preceding text node (likely "of ")
      if (parent && parent.previousSibling && parent.previousSibling.nodeType === 3) {
        parent.previousSibling.textContent = "";
      }

      // hide any separator wrappers as well
      const sepEls = adviceEl.querySelectorAll(".advice-sep, .sep");
      sepEls.forEach((el) => (el.style.display = "none"));
    }
  }
}

function hideAdvice() {
  const adviceEl = document.getElementById("wpcl-advice");
  if (!adviceEl) return;
  adviceEl.style.display = "none";
  const p0 = document.getElementById("advice-calc-0");
  const p1 = document.getElementById("advice-calc-1");
  if (p0) p0.textContent = "";
  if (p1) p1.textContent = "";
  // hide any separater wrapper if present
  const sepEls = adviceEl.querySelectorAll(".advice-sep, .sep");
  sepEls.forEach((el) => (el.style.display = "none"));
}

// Expose functions to global scope for inline HTML handlers
window.computeLensForm = computeLensForm;
window.computeHeightForm = computeHeightForm;
window.computeWidthForm = computeWidthForm;
window.hideAdvice = hideAdvice;
