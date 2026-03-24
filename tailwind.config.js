const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                burgundy: '#800020',
                'brand-accent': '#B6FF5C',
                'brand-dark': '#0D1F1C',
                'brand-darker': '#081412',
            },
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
    ],
};
