/** @type {import('tailwindcss').Config} */
export default {
  content: ['./src/**/*.{html,js,svelte,ts}'],
  theme: {
    extend: {
      colors: {
        'background': "#D2C8B4",
        'primary': "#37007D",
        'secondary': "#FF5300",
      }
    },
  },
  plugins: [require('@tailwindcss/forms')],
}
