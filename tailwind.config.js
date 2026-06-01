import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/View/Components/**/*.php",
    "./app/Livewire/**/*.php",
  ],
  theme: {
    extend: {
      colors: {
        'ja-red': '#DC2626',
        'ja-gold': '#F59E0B',
        'ja-dark': '#1F2937',
      }
    },
  },
  plugins: [],
}