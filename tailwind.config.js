const colors = require('tailwindcss/colors')


module.exports = {
    purge: {
        content: [
            './resources/views/**/*.blade.php',
            './resources/scss/**/*.scss',
            './resources/js/**/*.js',
            './resources/js/**/*.vue',
        ],

        // These options are passed through directly to PurgeCSS
        options: {
            safelist: [/-driller/, /-gunner/, /-engineer/, /-scout/],
        },
    },
    theme: {
        extend: {
            colors: {
                orange: colors.orange,
                driller: {
                    light: '#fffc31',
                    DEFAULT: '#fdfa00',
                    dark: '#cac800',
                },
                gunner: {
                    light: '#59a648',
                    DEFAULT: '#407734',
                    dark: '#335f29'
                },
                engineer: {
                    light: '#ff4131',
                    DEFAULT: '#fd1400',
                    dark: '#ca1000',
                },
                scout: {
                    light: '#5084fd',
                    DEFAULT: '#2566fd',
                    dark: '#0246e5',
                }
            }
        }
    },
    variants: {},
    plugins: [
        require('@tailwindcss/typography'),
        require('@tailwindcss/forms'),
    ]
}
