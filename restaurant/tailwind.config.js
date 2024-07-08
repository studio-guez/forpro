/** @type {import('tailwindcss').Config} */
export default {
	content: ['./src/**/*.{html,js,svelte,ts}'],
	theme: {
		borderRadius: {
			none: '0',
			sm: '0.125rem',
			'3xl': '4rem',
			md: '0.375rem',
			lg: '0.5rem',
			'2xl': '2rem',
			full: '9999px'
		},
		extend: {
			colors: {
				background: '#D2C8B4',
				primary: '#37007D',
				secondary: '#FF5300'
			}
		}
	},
	plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
