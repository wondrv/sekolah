import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import aspectRatio from '@tailwindcss/aspect-ratio';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/views/components/**/*.blade.php',
        './app/View/Components/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['var(--font-family)', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: 'var(--color-primary)',
                secondary: 'var(--color-secondary)',
                accent: 'var(--color-accent)',
                success: 'var(--color-success)',
                warning: 'var(--color-warning)',
                error: 'var(--color-error)',
            },
            fontSize: {
                base: 'var(--font-size-base)',
            },
            lineHeight: {
                base: 'var(--line-height-base)',
            },
            fontWeight: {
                normal: 'var(--font-weight-normal)',
                semibold: 'var(--font-weight-semibold)',
                bold: 'var(--font-weight-bold)',
            },
            backdropBlur: {
                'xs': '2px',
            },
            animation: {
                'fade-in-up': 'fadeInUp 0.6s ease-out',
            },
        },
    },

    // Enable browser compatibility features
    future: {
        hoverOnlyWhenSupported: true,
    },

    // Ensure core plugins that might need vendor prefixes are enabled
    corePlugins: {
        backdropFilter: true,
        backdropBlur: true,
    },

    plugins: [
        forms,
        typography,
        aspectRatio,
        function({ addUtilities }) {
            addUtilities({
                '.text-primary': {
                    color: 'var(--color-primary)',
                },
                '.bg-primary': {
                    backgroundColor: 'var(--color-primary)',
                },
                '.border-primary': {
                    borderColor: 'var(--color-primary)',
                },
                '.text-secondary': {
                    color: 'var(--color-secondary)',
                },
                '.bg-secondary': {
                    backgroundColor: 'var(--color-secondary)',
                },
                '.text-accent': {
                    color: 'var(--color-accent)',
                },
                '.bg-accent': {
                    backgroundColor: 'var(--color-accent)',
                },
            })
        },
    ],
};
