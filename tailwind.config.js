const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
    important: true,

    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./node_modules/flowbite/**/*.js"
    ],

    theme: {
        extend: {
            colors: {
                primaryLighter: "#d6667c",
                primaryLight: "#c83350",
                primary: "#ba0024",
                primaryDark: "#95001d",
                primaryDarker: "#700016",

                secondaryLighter: "#858585",
                secondaryLight: "#5c5c5c",
                secondary: "#333",
                secondaryDark: "#292929",
                secondaryDarker: "#1f1f1f",

                facebook: "#1877f2",
                twitter: "#1da1f2",
                google: "#dd4b39",
                youtube: "#ff0000",
                whatsapp: "#25d366",
                view: "#3498db",
                viewHover: "#2980b9",
                edit: "#e67e22",
                editHover: "#d35400",
                delete: "#e74c3c",
                deleteHover: "#c0392b",
                role: "#1abc9c",
                roleHover: "#16a085",

                successLighter: "#94cf96",
                successLight: "#70bf73",
                success: "#4caf50",
                successDark: "#3d8c40",
                successDarker: "#2e6930",

                infoLighter: "#4cb2c9",
                infoLight: "#2ca2c0",
                info: "#3498db",
                infoDark: "#0099ae",
                infoDarker: "#007a8c",

                male: "#4cb2c9",
                female: "#e85591",
            },
            fontFamily: {
                sans: ["Nunito", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
        require("tailwind-scrollbar"),
        require("flowbite/plugin"),
    ],
};
