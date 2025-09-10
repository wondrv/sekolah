export default {
    plugins: {
        tailwindcss: {},
        autoprefixer: {
            overrideBrowserslist: [
                'last 3 versions',
                'Safari >= 9',
                'iOS >= 9',
                'Firefox >= 52',
                'Chrome >= 60',
                'Edge >= 16'
            ]
        },
    },
};
