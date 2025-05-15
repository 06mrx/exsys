import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'neo-bg': '#e0e5ec',
                'neo-dark': '#d1d9e6',
                'neo-light': '#f3f6f9',
            },
            boxShadow: {
                'neo': '8px 8px 16px #d1d9e6, -8px -8px 16px #f3f6f9',
                'neo-inset': 'inset 4px 4px 8px #d1d9e6, inset -4px -4px 8px #f3f6f9',
            },
        },
    },

    plugins: [forms],
};
