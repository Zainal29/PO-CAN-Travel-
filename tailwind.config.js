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
                brand: {
                    50: '#f6f8fa',
                    100: '#e9eef3',
                    500: '#e0a13a',
                    600: '#bd7d1d',
                    700: '#955d12',
                    900: '#102a43',
                    950: '#081c2c',
                },
            },
        },
    },

    plugins: [forms],
};
