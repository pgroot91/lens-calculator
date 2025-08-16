# 📘 Lens Calculator Reference

This document describes the formulas and functions used in the lens calculator.

---

## 📐 Camera Model

We use the **pinhole camera model** (similar triangles):

```
   Object (real world)             Sensor (camera)
   ┌──────────────┐                ┌──────────────┐
   │              │                │              │
   │     H_obj    │                │    H_img     │
   │              │                │              │
   └──────────────┘                └──────────────┘
       ↕ distance = D                   ↕ sensor size = S
                \
                 \ focal length f
                  \
                   *
                (lens)
```

From similar triangles:

```
H_img / S = H_obj / D
```

Rearranging:

```
f = (D × S) / H_obj
```

This applies both for **width** and **height**.

---

## ⚙️ Functions

### `computeHeight(sensorIndex, distance, objectHeight, sensors)`
**Formula:**

```
pixels = (distance × sensorHeight) / objectHeight
```

**Parameters:**
- `sensorIndex` – index in `sensors` array (1-based)  
- `distance` – object distance in meters  
- `objectHeight` – object height in meters  
- `sensors` – array of `{width, height}`  

**Returns:**  
Number of pixels tall the object appears on sensor, or `null` if invalid.  

---

### `computeWidth(sensorIndex, distance, objectWidth, sensors)`
**Formula:**

```
pixels = (distance × sensorWidth) / objectWidth
```

**Parameters:**
- `sensorIndex` – index in `sensors` array (1-based)  
- `distance` – object distance in meters  
- `objectWidth` – object width in meters  
- `sensors` – array of `{width, height}`  

**Returns:**  
Number of pixels wide the object appears on sensor, or `null`.  

---

### `computeFocalLength(sensorIndex, distance, objectSize, axis, sensors)`
**Formula (pinhole camera model):**

```
f = (D × S) / O
```

Where:
- `f` = focal length (mm)  
- `D` = distance to object (mm)  
- `S` = sensor size (mm, width or height)  
- `O` = object size (mm, width or height)  

**Parameters:**
- `sensorIndex` – index in sensors array (1-based)  
- `distance` – object distance (meters)  
- `objectSize` – object size (meters, depends on axis)  
- `axis` – `"width"` or `"height"`  
- `sensors` – array of `{width, height}`  

**Returns:**  
Focal length in mm, or `null`.  

---

### `computeAverageFocal(focalWidth, focalHeight)`
**Formula:**

```
f_avg = (f_width + f_height) / 2
```

**Parameters:**
- `focalWidth` – focal length from width calculation (mm)  
- `focalHeight` – focal length from height calculation (mm)  

**Returns:**  
Average focal length (mm), or one of the values if only one valid.  

---

### `nearestStandardLens(focalLength)`
Chooses the **closest available lens** from a list of common focal lengths.  

**Lens set:**
```
[8, 12, 16, 24, 25, 28, 35, 50, 85, 100, 135, 200, 300, 400]
```

**Parameters:**
- `focalLength` – computed focal length (mm)  

**Returns:**  
Nearest standard focal length (mm), or `null` if invalid.  

---

## ✅ Example Workflow

1. Select sensor size  
2. Enter object distance, width, height  
3. Compute:  
   - `computeHeight` → pixels tall  
   - `computeWidth` → pixels wide  
   - `computeFocalLength` (both width + height) → focal lengths  
   - `computeAverageFocal` → average lens requirement  
   - `nearestStandardLens` → practical lens recommendation  
