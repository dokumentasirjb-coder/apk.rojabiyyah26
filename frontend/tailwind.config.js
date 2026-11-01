/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Nunito', 'Plus Jakarta Sans', 'system-ui', 'sans-serif'],
        nunito: ['Nunito', 'sans-serif'],
      },
      colors: {
        vristo: {
          primary: '#059669', // Fresh emerald
          'primary-hover': '#047857',
          'primary-light': '#ecfdf5',
          secondary: '#0d9488', // Teal
          success: '#10b981',
          danger: '#ef4444',
          warning: '#f97316', // Tangerine / Orange accent
          info: '#0284c7',
          dark: '#0f172a', // Deep readable slate
          bg: '#f8fafc', // Bright clean slate canvas
          card: '#ffffff', // Crisp white card
          border: '#e2e8f0', // Clean slate border
          sidebar: '#ffffff',
        },
        primary: {
          50: '#ecfdf5',
          100: '#d1fae5',
          200: '#a7f3d0',
          300: '#6ee7b7',
          400: '#34d399',
          500: '#10b981',
          600: '#059669',
          700: '#047857',
          800: '#065f46',
          900: '#064e3b',
          950: '#022c22',
        },
        flyer: {
          orange: '#f97316', // Orange banner accent
          'orange-dark': '#ea580c',
          green: '#059669',
          'green-dark': '#047857',
          'green-deep': '#064e3b',
          'green-bg': '#f8fafc',
          'green-card': '#ffffff',
          'green-border': '#e2e8f0',
          mint: '#10b981',
          light: '#ecfdf5',
        },
        accent: {
          50: '#fff7ed',
          100: '#ffedd5',
          200: '#fed7aa',
          300: '#fdba74',
          400: '#fb923c',
          500: '#f97316',
          600: '#ea580c',
          700: '#c2410c',
          800: '#9a3412',
          900: '#7c2d12',
        }
      }
    },
  },
  plugins: [],
}
