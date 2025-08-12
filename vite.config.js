import { defineConfig } from 'vite';

export default defineConfig({
  build: {
    outDir: 'dist',         // output folder
    rollupOptions: {
      input: 'js/lens-calculator.js',  // your main entry file
      output: {
        entryFileNames: 'lens-calculator.bundle.js',
      },
    },
    sourcemap: true, // optional, helpful for debugging
  },
});
