import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        'surface-variant': '#d3e4fe',
        'on-tertiary-fixed': '#001e2d',
        'surface-bright': '#f8f9ff',
        'on-primary': '#ffffff',
        'secondary': '#49607e',
        'on-surface': '#0b1c30',
        'outline': '#717786',
        'surface-container-low': '#eff4ff',
        'on-secondary-container': '#49617f',
        'on-secondary': '#ffffff',
        'inverse-surface': '#213145',
        'primary-fixed': '#d8e2ff',
        'surface-container-lowest': '#ffffff',
        'tertiary': '#006389',
        'surface-container': '#e5eeff',
        'primary-container': '#0070ea',
        'on-background': '#0b1c30',
        'primary': '#0059bb',
        'surface-tint': '#005bc0',
        'on-primary-fixed-variant': '#004493',
        'on-surface-variant': '#414754',
        'background': '#f8f9ff',
        'error': '#ba1a1a',
      },
      spacing: {
        'space-xs': '0.25rem',
        'space-sm': '0.5rem',
        'space-md': '1rem',
        'space-lg': '1.5rem',
        'space-xl': '2rem',
        'gutter-lg': '1.5rem',
      },
      fontFamily: {
        body: ['Inter', ...defaultTheme.fontFamily.sans],
      },
    },
  },
  plugins: [],
};