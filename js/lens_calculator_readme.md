# Lens Calculator

The Lens Calculator computes:

- Object height in pixels on the sensor  
- Object width in pixels on the sensor  
- Recommended lens focal length (mm)  

It uses sensor dimensions, object size, and object distance to determine results. The calculator can handle different types of forms: height-only, width-only, or full lens focal length computation.

---

## Table of Contents

- [Functions](#functions)
  - [computeHeightForm(form)](#computeheightformform)
  - [computeWidthForm(form)](#computewidthformform)
  - [computeLensForm(form)](#computelensformform)
- [Validation](#validation)
- [Usage Examples](#usage-examples)
- [Formulas](#formulas)
- [Notes](#notes)

---

## Functions

### `computeHeightForm(form)`

**Purpose:**  
Compute the object height in pixels on the sensor.

**Inputs:**
- `form.answer1.selectedIndex` → Selected sensor index (1-based)  
- `form.objectafstand.value` → Object distance in meters  
- `form.objecthoogte.value` → Object height in meters  

**Formula:**

```
pixels = (distance × sensorHeight) / objectHeight
```

**Returns:**  
Number of pixels (height) or fallback text if inputs are invalid.

---

### `computeWidthForm(form)`

**Purpose:**  
Compute the object width in pixels on the sensor.

**Inputs:**
- `form.answer1.selectedIndex` → Selected sensor index (1-based)  
- `form.objectafstand.value` → Object distance in meters  
- `form.objectbreedte.value` → Object width in meters  

**Formula:**

```
pixels = (distance × sensorWidth) / objectWidth
```

**Returns:**  
Number of pixels (width) or fallback text if inputs are invalid.

---

### `computeLensForm(form)`

**Purpose:**  
Compute the recommended lens focal length using width and height measurements.

**Inputs:**
- `form.answer1.selectedIndex` → Selected sensor index (1-based)  
- `form.objectafstand.value` → Object distance in meters  
- `form.objectbreedte.value` → Object width in meters  
- `form.objecthoogte.value` → Object height in meters  

**Formulas:**

```
fWidth  = (distance × sensorWidth) / objectWidth
fHeight = (distance × sensorHeight) / objectHeight
fAverage = (fWidth + fHeight) / 2
```

The calculator can select the nearest standard lens from a predefined list.

**Returns:**  
Recommended lens focal length in mm, or fallback text if inputs are invalid.

---

## Validation

Each function checks:

- `sensorIndex` must be selected (not 0)  
- `distance`, `objectWidth`, `objectHeight` must be numbers  
- Alerts are shown and invalid inputs are focused  

This ensures reliable calculations and prevents runtime errors.

---

## Usage Examples

### Height-Only Form

```html
<form name="hoogte">
  <select name="answer1">
    <option value="0">Select Sensor</option>
    <option value="1">Sensor 1</option>
  </select>
  <input type="number" name="objectafstand" placeholder="Distance (m)">
  <input type="number" name="objecthoogte" placeholder="Object height (m)">
  <input type="text" name="output" readonly>
  <button type="button" onclick="computeHeightForm(this.form)">Compute Height</button>
</form>
```

### Width-Only Form

```html
<form name="breedte">
  <select name="answer1">
    <option value="0">Select Sensor</option>
    <option value="1">Sensor 1</option>
  </select>
  <input type="number" name="objectafstand" placeholder="Distance (m)">
  <input type="number" name="objectbreedte" placeholder="Object width (m)">
  <input type="text" name="output" readonly>
  <button type="button" onclick="computeWidthForm(this.form)">Compute Width</button>
</form>
```

### Full Lens Focal Length Form

```html
<form name="lens">
  <select name="answer1">
    <option value="0">Select Sensor</option>
    <option value="1">Sensor 1</option>
  </select>
  <input type="number" name="objectafstand" placeholder="Distance (m)">
  <input type="number" name="objecthoogte" placeholder="Object height (m)">
  <input type="number" name="objectbreedte" placeholder="Object width (m)">
  <input type="text" name="output" readonly>
  <button type="button" onclick="computeLensForm(this.form)">Compute Lens</button>
</form>
```

---

## Formulas Summary

- **Height in pixels:**  
  `pixels = (distance × sensorHeight) / objectHeight`  

- **Width in pixels:**  
  `pixels = (distance × sensorWidth) / objectWidth`  

- **Lens focal length (mm):**  
  ```
fWidth  = (distance × sensorWidth) / objectWidth
fHeight = (distance × sensorHeight) / objectHeight
fAverage = (fWidth + fHeight) / 2
```

---

## Notes

- Sensor dimensions are stored in `lens_calculator.sensors` array.  
- Functions are exposed globally for inline HTML usage (`window.computeHeightForm`, etc.).  
- Nearest standard lens selection ensures practical recommendations.  
- Alerts guide users to enter valid numbers for distance, height, and width.

