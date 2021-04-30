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
    }
    theme: {
        extend: {
            colors: {
                orange: colors.orange,
                driller: '#FDFA00',
                gunner: '#407734',
                engineer: '#FD1400',
                scout: '#2566FD'
            }
        }
    },
    variants: {},
    plugins: [
        require('@tailwindcss/typography'),
        require('@tailwindcss/forms'),
    ]
}
