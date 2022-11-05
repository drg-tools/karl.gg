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
    ],
    theme: {
        extend: {
            typography: (theme) => ({
                DEFAULT: {
                    css: {
                        color: theme("colors.gray.300"),
                        strong: {
                            color: theme("colors.white"),
                        },
                        '[class~="lead"]': {
                            color: theme("colors.gray.300"),
                        },
                        a: {
                            color: theme("colors.orange.500"),
                        },
                        blockquote: {
                            color: theme("colors.gray.400"),
                            borderLeftColor: theme("colors.gray.600"),
                        },
                        h1: {
                            color: theme("colors.white"),
                        },
                        h2: {
                            color: theme("colors.white"),
                        },
                        h3: {
                            color: theme("colors.white"),
                        },
                        h4: {
                            color: theme("colors.white"),
                        },
                        code: {
                            color: theme("colors.gray.300"),
                        },
                        "a code": {
                            color: theme("colors.gray.300"),
                        },
                        thead: {
                            color: theme("colors.gray.300"),
                        },
                    },
                },
            }),
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
