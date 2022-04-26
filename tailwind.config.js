module.exports = {
  content: [
    "./assets/**/*.{vue,js,ts,jsx,tsx}",
    "./templates/**/*.{html,twig}"
  ],
  theme: {
    extend: {},
  },
  variants: {
    fill: ['hover', 'focus'],
  },
  darkMode: 'class',
  plugins: [require('@tailwindcss/forms')],
}
