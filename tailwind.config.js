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
                primary:  "#ba0024" ,
                primaryDark:  "#95001d" ,
                secondary: "#333",
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
                role: '#1abc9c',
                roleHover: '#16a085',
                success: '#4caf50',
            },
            fontFamily: {
                sans: ["Nunito", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
        require('tailwind-scrollbar'),
        require('flowbite/plugin')
    ],
};
