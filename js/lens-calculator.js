import { computeHeight, computeWidth } from './lens-calculator-core.js';

const sensors = lens_calculator.sensors;

function compute_it_hoogte(form) {
    const result1 = document.hoogte.answer1.selectedIndex;
    const result2 = parseFloat(document.hoogte.objectafstand.value);
    const result3 = parseFloat(document.hoogte.objecthoogte.value);

    if (result1 === 0) alert(lens_calculator.message1);
    if (isNaN(result2)) { alert(lens_calculator.message2); document.hoogte.objectafstand.focus(); }
    if (isNaN(result3)) { alert(lens_calculator.message31); document.hoogte.objecthoogte.focus(); }

    const result = computeHeight(result1, result2, result3, sensors);
    document.hoogte.output.value = result !== null ? result : lens_calculator.nnb;
}

function compute_it_breedte(form) {
    const result1 = document.breedte.answer1.selectedIndex;
    const result2 = parseFloat(document.breedte.objectafstand.value);
    const result3 = parseFloat(document.breedte.objectbreedte.value);

    if (result1 === 0) alert(lens_calculator.message1);
    if (isNaN(result2)) { alert(lens_calculator.message2); document.breedte.objectafstand.focus(); }
    if (isNaN(result3)) { alert(lens_calculator.message32); document.breedte.objectbreedte.focus(); }

    const result = computeWidth(result1, result2, result3, sensors);
    document.breedte.output.value = result !== null ? result : lens_calculator.nnb;
}

// Expose to global scope if needed for inline HTML
window.compute_it_hoogte = compute_it_hoogte;
window.compute_it_breedte = compute_it_breedte;
