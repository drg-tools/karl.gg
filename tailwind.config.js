module.exports = {
    future: {
        removeDeprecatedGapUtilities: true,
    },
    purge: [
        './resources/views/**/*.blade.php',
        './resources/scss/**/*.scss',
    ],
    theme: {
        extend: {}
    },
    variants: {},
    plugins: [
        require('@tailwindcss/ui'),
        require('@tailwindcss/typography'),
    ]
}
