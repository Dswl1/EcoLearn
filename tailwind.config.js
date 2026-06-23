import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
                    "colors": {
                        "on-primary": "#00363a",
                        "inverse-on-surface": "#2e3134",
                        "tertiary-fixed": "#ffd6ff",
                        "outline": "#849495",
                        "on-secondary-container": "#b1bbff",
                        "error": "#ffb4ab",
                        "background": "#050816",
                        "surface-container-highest": "#323539",
                        "on-surface": "#e1e2e7",
                        "tertiary": "#fff5fa",
                        "primary-fixed": "#74f5ff",
                        "on-error-container": "#ffdad6",
                        "on-primary-fixed-variant": "#004f54",
                        "on-secondary": "#001d93",
                        "on-error": "#690005",
                        "primary-container": "#00f2ff",
                        "on-surface-variant": "#b9cacb",
                        "inverse-primary": "#00696f",
                        "on-secondary-fixed-variant": "#002ccd",
                        "surface-tint": "#00dbe7",
                        "surface-dim": "#111417",
                        "surface": "#0B1120",
                        "on-primary-fixed": "#002022",
                        "tertiary-container": "#fecbff",
                        "on-primary-container": "#006a71",
                        "inverse-surface": "#e1e2e7",
                        "secondary-container": "#0231de",
                        "surface-container-high": "#282a2e",
                        "on-tertiary-fixed": "#350040",
                        "surface-bright": "#37393d",
                        "secondary-fixed": "#dee0ff",
                        "surface-container": "#1d2023",
                        "surface-container-low": "#191c1f",
                        "surface-container-lowest": "#0c0e12",
                        "primary-fixed-dim": "#00dbe7",
                        "secondary": "#bbc3ff",
                        "on-tertiary": "#570067",
                        "on-secondary-fixed": "#000f5d",
                        "primary": "#e1fdff",
                        "outline-variant": "#3a494b",
                        "on-background": "#e1e2e7",
                        "surface-variant": "#323539",
                        "on-tertiary-container": "#a400c0",
                        "error-container": "#93000a",
                        "tertiary-fixed-dim": "#f8acff",
                        "secondary-fixed-dim": "#bbc3ff",
                        "on-tertiary-fixed-variant": "#7b0090"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "container-padding-desktop": "64px",
                        "section-gap": "80px",
                        "gutter": "24px",
                        "unit": "8px",
                        "container-padding-mobile": "20px"
                    },
                    "fontFamily": {
                        "label-sm": ["JetBrains Mono"],
                        "display-lg-mobile": ["Space Grotesk"],
                        "body-md": ["Geist"],
                        "headline-md": ["Space Grotesk"],
                        "display-lg": ["Space Grotesk"],
                        "body-lg": ["Geist"]
                    },
                    "fontSize": {
                        "label-sm": ["12px", {
                            "lineHeight": "1.0",
                            "letterSpacing": "0.05em",
                            "fontWeight": "500"
                        }],
                        "display-lg-mobile": ["32px", {
                            "lineHeight": "1.2",
                            "fontWeight": "700"
                        }],
                        "body-md": ["16px", {
                            "lineHeight": "1.5",
                            "fontWeight": "400"
                        }],
                        "headline-md": ["24px", {
                            "lineHeight": "1.3",
                            "fontWeight": "600"
                        }],
                        "display-lg": ["48px", {
                            "lineHeight": "1.1",
                            "letterSpacing": "-0.02em",
                            "fontWeight": "700"
                        }],
                        "body-lg": ["18px", {
                            "lineHeight": "1.6",
                            "fontWeight": "400"
                        }]
                    }
                },
    },

    plugins: [forms],
};
