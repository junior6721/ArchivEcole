import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                marine: {
                    DEFAULT: '#0A2A4D',
                    dark: '#071D36',
                    light: '#123A66',
                },
                vert: '#16A34A',
                'vert-light': '#22C55E',
                'vert-dark': '#15803D',
                jaune: '#FCD116',
                'jaune-light': '#FDE68A',
                rouge: '#E8112D',
                'rouge-light': '#FCA5A5',
                or: '#F59E0B',
                'or-light': '#FCD34D',
                azur: '#3B82F6',
                'azur-light': '#93C5FD',
                corail: '#F97316',
                'corail-light': '#FDBA74',
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                mono: ['"IBM Plex Mono"', ...defaultTheme.fontFamily.mono],
            },
            boxShadow: {
                'glow-vert': '0 0 20px rgba(22, 163, 74, 0.15)',
                'glow-or': '0 0 20px rgba(245, 158, 11, 0.15)',
                'glow-azur': '0 0 20px rgba(59, 130, 246, 0.15)',
                'warm': '0 4px 24px rgba(245, 158, 11, 0.08)',
                'warm-lg': '0 8px 40px rgba(245, 158, 11, 0.12)',
            },
        },
    },
    plugins: [forms],
};
