import { computeHeight, computeWidth, computeFocalLength, computeAverageFocal, nearestStandardLens } from './lens-calculator-core.js';

const sensors = lens_calculator.sensors;

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
    if (sensorIndex === 0) { alert(messages.sensor); return false; }
    if (isNaN(distance)) { alert(messages.distance); focusElement.focus(); return false; }
    if (isNaN(size)) { alert(messages.size); focusElement.focus(); return false; }
    return true;
}

/**
 * Compute object height in pixels on the sensor
 * Formula: pixels = (distance × sensorHeight) / objectHeight
 * @param {HTMLFormElement} form - Form containing height inputs
 * Inputs: sensor, distance (m), object height (m)
 * Returns: Pixels height or fallback text
 */
function computeHeightForm(form) {
    const sensorIndex = form.answer1.selectedIndex;
    const distance = parseFloat(form.objectafstand.value);
    const objectHeight = parseFloat(form.objecthoogte.value);

    if (!validateInputs(sensorIndex, distance, objectHeight, {
        sensor: lens_calculator.message1,
        distance: lens_calculator.message2,
        size: lens_calculator.message31
    }, form.objecthoogte)) return;

    const pixels = computeHeight(sensorIndex, distance, objectHeight, sensors);
    form.output.value = pixels !== null ? pixels : lens_calculator.nnb;
}

/**
 * Compute object width in pixels on the sensor
 * Formula: pixels = (distance × sensorWidth) / objectWidth
 * @param {HTMLFormElement} form - Form containing width inputs
 * Inputs: sensor, distance (m), object width (m)
 * Returns: Pixels width or fallback text
 */
function computeWidthForm(form) {
    const sensorIndex = form.answer1.selectedIndex;
    const distance = parseFloat(form.objectafstand.value);
    const objectWidth = parseFloat(form.objectbreedte.value);

    if (!validateInputs(sensorIndex, distance, objectWidth, {
        sensor: lens_calculator.message1,
        distance: lens_calculator.message2,
        size: lens_calculator.message32
    }, form.objectbreedte)) return;

    const pixels = computeWidth(sensorIndex, distance, objectWidth, sensors);
    form.output.value = pixels !== null ? pixels : lens_calculator.nnb;
}

/**
 * Compute recommended lens focal length
 * Uses both width and height to calculate average focal
 * Formulas:
 *   fWidth  = (distance × sensorWidth) / objectWidth
 *   fHeight = (distance × sensorHeight) / objectHeight
 *   fAverage = (fWidth + fHeight) / 2
 * Inputs: sensor, distance (m), object width (m), object height (m)
 * Returns: Recommended lens focal length (mm) or fallback text
 */
function computeLensForm(form) {
    const sensorIndex = form.answer1.selectedIndex;
    const distance = parseFloat(form.objectafstand.value);
    const objectWidth = parseFloat(form.objectbreedte.value);
    const objectHeight = parseFloat(form.objecthoogte.value);

    // Validate width and height separately
    if (!validateInputs(sensorIndex, distance, objectWidth, {
        sensor: lens_calculator.message1,
        distance: lens_calculator.message2,
        size: lens_calculator.message32
    }, form.objectbreedte)) return;

    if (!validateInputs(sensorIndex, distance, objectHeight, {
        sensor: lens_calculator.message1,
        distance: lens_calculator.message2,
        size: lens_calculator.message31
    }, form.objecthoogte)) return;

    const fWidth = computeFocalLength(sensorIndex, distance, objectWidth, 'width', sensors);
    const fHeight = computeFocalLength(sensorIndex, distance, objectHeight, 'height', sensors);
    const fAverage = computeAverageFocal(fWidth, fHeight);
    const recommendedLens = nearestStandardLens(fAverage);

    form.output.value = recommendedLens !== null ? recommendedLens : lens_calculator.nnb;
}

// Expose functions to global scope for inline HTML
window.computeHeightForm = computeHeightForm;
window.computeWidthForm = computeWidthForm;
window.computeLensForm = computeLensForm;
