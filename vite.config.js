import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
  build: {
    outDir: 'dist',         // Output folder
    sourcemap: true,        // Optional: generate source maps
    cssCodeSplit: true,     // Keep CSS in separate file
    minify: 'esbuild',      // Minify JS + CSS with esbuild
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'js/lens-calculator.js'), // Main JS entry
        style: path.resolve(__dirname, 'css/plugin.css'), // Main CSS entry
      },
      output: {
        entryFileNames: 'lens-calculator.bundle.min.js', // JS bundle name
        assetFileNames: 'lens-calculator.min.css', // CSS output name as original
      },
    },
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'js'),  // optional alias for imports
    },
  },
});
