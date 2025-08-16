/**
 * Compute required pixels for object height in the frame
 * @param {number} sensorIndex - index in sensors array (1-based, matches dropdown)
 * @param {number} distance - object distance in meters
 * @param {number} objectHeight - object height in meters
 * @param {Array} sensors - array of {width, height}
 * @returns {number|null}
 */
export function computeHeight(sensorIndex, distance, objectHeight, sensors) {
    if (sensorIndex === 0 || isNaN(distance) || isNaN(objectHeight)) {
        return null;
    }
    return Math.round(distance * sensors[sensorIndex - 1].height / objectHeight);
}

/**
 * Compute required pixels for object width in the frame
 * @param {number} sensorIndex - index in sensors array (1-based)
 * @param {number} distance - object distance in meters
 * @param {number} objectWidth - object width in meters
 * @param {Array} sensors - array of {width, height}
 * @returns {number|null}
 */
export function computeWidth(sensorIndex, distance, objectWidth, sensors) {
    if (sensorIndex === 0 || isNaN(distance) || isNaN(objectWidth)) {
        return null;
    }
    return Math.round(distance * sensors[sensorIndex - 1].width / objectWidth);
}
