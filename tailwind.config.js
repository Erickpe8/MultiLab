const defaultTheme = require('tailwindcss/defaultTheme')
const forms = require('@tailwindcss/forms')
const typography = require('@tailwindcss/typography')

module.exports = {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
        fontFamily: {
            sans: ['Figtree', ...defaultTheme.fontFamily.sans],
        },

        colors: {
            multilab: {
            light: '#FDECF3',
            blue: '#0077B6',
            darkblue: '#2274A5',
            dark: '#110B11',
            gray: '#CCCCCC',
            },
        },

        boxShadow: {
            soft: '0 4px 12px rgba(0,0,0,0.08)',
            card: '0 2px 6px rgba(0,0,0,0.06)',
        },

        backgroundImage: {
            'gradient-multilab': 'linear-gradient(135deg, #0077B6, #2274A5)',
        },
        },
    },

    plugins: [forms, typography],
}
