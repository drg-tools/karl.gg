const colors = require("tailwindcss/colors");

module.exports = {
    content: [
        "./resources/views/**/*.blade.php",
        "./resources/scss/**/*.scss",
        "./resources/js/**/*.js",
        "./resources/js/**/*.vue",
    ],
    safelist: [
        { pattern: /-driller/ },
        { pattern: /-gunner/ },
        { pattern: /-engineer/ },
        { pattern: /-scout/ },
        { pattern: /(red|green)/ }
    ],
    theme: {
        extend: {
            colors: {
                green: colors.emerald,
                yellow: colors.amber,
                purple: colors.violet,
                "karl-orange": {
                    DEFAULT: "#fc9e00",
                },
                driller: {
                    light: "#fffc31",
                    DEFAULT: "#fdfa00",
                    dark: "#cac800",
                },
                gunner: {
                    light: "#59a648",
                    DEFAULT: "#407734",
                    dark: "#335f29",
                },
                engineer: {
                    light: "#ff4131",
                    DEFAULT: "#fd1400",
                    dark: "#ca1000",
                },
                scout: {
                    light: "#5084fd",
                    DEFAULT: "#2566fd",
                    dark: "#0246e5",
                },
            },
        },
    },
    plugins: [
        require("@tailwindcss/typography"),
        require("@tailwindcss/forms"),
    ],
};
