/**
 * Compute required pixel height of the object in the sensor image.
 *
 * Formula:
 *   pixels = (distance × sensorHeight) / objectHeight
 *
 * Where:
 *   - distance = object distance (meters)
 *   - sensorHeight = physical sensor height (from sensors array)
 *   - objectHeight = real object height (meters)
 *
 * @param {number} sensorIndex - Index in sensors array (1-based, matches dropdown)
 * @param {number} distance - Distance to object (meters)
 * @param {number} objectHeight - Height of object (meters)
 * @param {Array<{width:number,height:number}>} sensors - Array of available sensor dimensions
 * @returns {number|null} - Object height in pixels, or null if input invalid
 */
export function computeHeight(sensorIndex, distance, objectHeight, sensors) {
    if (sensorIndex === 0 || isNaN(distance) || isNaN(objectHeight)) {
        return null;
    }
    return Math.round(distance * sensors[sensorIndex - 1].height / objectHeight);
}

/**
 * Compute required pixel width of the object in the sensor image.
 *
 * Formula:
 *   pixels = (distance × sensorWidth) / objectWidth
 *
 * Where:
 *   - distance = object distance (meters)
 *   - sensorWidth = physical sensor width (from sensors array)
 *   - objectWidth = real object width (meters)
 *
 * @param {number} sensorIndex - Index in sensors array (1-based, matches dropdown)
 * @param {number} distance - Distance to object (meters)
 * @param {number} objectWidth - Width of object (meters)
 * @param {Array<{width:number,height:number}>} sensors - Array of available sensor dimensions
 * @returns {number|null} - Object width in pixels, or null if input invalid
 */
export function computeWidth(sensorIndex, distance, objectWidth, sensors) {
    if (sensorIndex === 0 || isNaN(distance) || isNaN(objectWidth)) {
        return null;
    }
    return Math.round(distance * sensors[sensorIndex - 1].width / objectWidth);
}

/**
 * Compute the required focal length (mm) to frame the object correctly.
 *
 * Formula (from pinhole camera model / similar triangles):
 *   f = (D × S) / O
 *
 * Where:
 *   - f = focal length (mm)
 *   - D = distance to object (mm)
 *   - S = sensor size (mm) in chosen axis (width or height)
 *   - O = object size (mm) in chosen axis
 *
 * @param {number} sensorIndex - Index in sensors array (1-based, matches dropdown)
 * @param {number} distance - Distance to object (meters)
 * @param {number} objectSize - Object size (meters, width or height depending on axis)
 * @param {"width"|"height"} axis - Whether to use width or height dimension
 * @param {Array<{width:number,height:number}>} sensors - Array of available sensor dimensions
 * @returns {number|null} - Required focal length (mm), or null if input invalid
 */
export function computeFocalLength(sensorIndex, distance, objectSize, axis, sensors) {
    if (sensorIndex === 0 || isNaN(distance) || isNaN(objectSize)) {
        return null;
    }
    const sensor = sensors[sensorIndex - 1];
    const sensorSize = axis === "width" ? sensor.width : sensor.height;

    const D = distance * 1000;  // convert meters → mm
    const O = objectSize * 1000; // convert meters → mm

    return Math.round((D * sensorSize) / O);
}

/**
 * Compute the average focal length from width-based and height-based values.
 *
 * Formula:
 *   f_avg = (f_width + f_height) / 2
 *
 * @param {number|null} focalWidth - Focal length from width calculation (mm) or null
 * @param {number|null} focalHeight - Focal length from height calculation (mm) or null
 * @returns {number|null} - Average focal length (mm), or one of the values if only one valid
 */
export function computeAverageFocal(focalWidth, focalHeight) {
    if (focalWidth === null && focalHeight === null) return null;
    if (focalWidth === null) return focalHeight;
    if (focalHeight === null) return focalWidth;
    return Math.round((focalWidth + focalHeight) / 2);
}

/**
 * Find the nearest standard focal length to the computed value.
 *
 * Method:
 *   - Compare computed focal length against a list of common prime lens focal lengths
 *   - Select the one with the smallest absolute difference
 *
 * Example:
 *   Input: 37 mm
 *   Output: 35 mm
 *
 * @param {number|null} focalLength - Computed focal length (mm)
 * @returns {number|null} - Nearest standard lens focal length (mm), or null if input invalid
 */
export function nearestStandardLens(focalLength) {
    if (focalLength === null) return null;
    const standardLenses = [8, 12, 16, 24, 25, 28, 35, 50, 85, 100, 135, 200, 300, 400];
    return standardLenses.reduce((prev, curr) =>
        Math.abs(curr - focalLength) < Math.abs(prev - focalLength) ? curr : prev
    );
}
