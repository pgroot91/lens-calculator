import { computeHeight, computeWidth } from '../js/lens-calculator-core';

const sensors = [
    { width: 12.80, height: 9.60 }, // 1 inch
    { width: 8.80,  height: 6.60 }  // 2/3 inch
];

test('computes height correctly', () => {
    const result = computeHeight(1, 20, 5, sensors);
    expect(result).toBe(38); // Math.round(20 * 9.6 / 5) = 38
});

test('computes width correctly', () => {
    const result = computeWidth(1, 30, 8, sensors);
    expect(result).toBe(48); // Math.round(30 * 12.8 / 8) = 48
});

test('handles invalid inputs gracefully', () => {
    const result = computeHeight(0, NaN, 5, sensors);
    expect(result).toBe(null); // null if invalid
});
