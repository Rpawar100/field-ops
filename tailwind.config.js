/**
 * Tailwind CSS Configuration
 * Colors extracted from Edmin Bootstrap Admin Template
 * Source: https://lab.ubicos.in/edmin/Edmin_webpack_template/template/
 *
 * Edmin CSS Variables Reference:
 *   --theme-default: #43B9B2  (primary teal)
 *   --theme-secondary: #C280D2 (secondary purple)
 *   --body-color: #F9FAFC     (body background)
 *   --card-color: #fff         (card background)
 *   --body-font-color: #3D3D47 (text primary)
 *   --text-gray: rgba(153,153,169,0.8)
 *   --text-light-gray: #9B9B9B
 *   --text-dark-black: #363636
 *   --card-border-color: #f3f3f3
 */
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      colors: {
        // Edmin Primary — Teal
        primary: {
          50:  '#edf9f8',
          100: '#d2f0ee',
          200: '#a5e1dd',
          300: '#78d2cc',
          400: '#43B9B2', // --theme-default
          500: '#3aa5a0',
          600: '#2f8682',
          700: '#256865',
          800: '#1b4a48',
          900: '#112c2b',
          DEFAULT: '#43B9B2',
        },
        // Edmin Secondary — Purple
        secondary: {
          50:  '#faf5fc',
          100: '#f2e5f6',
          200: '#e5cbee',
          300: '#d4a8e1',
          400: '#C280D2', // --theme-secondary
          500: '#a85ebe',
          600: '#8a4a9c',
          700: '#6c3a7a',
          800: '#4e2a58',
          900: '#301a36',
          DEFAULT: '#C280D2',
        },
        // Bootstrap 5 Semantic Colors (from Edmin's Bootstrap 5.3)
        success: {
          50:  '#ecfdf0',
          100: '#d1fae0',
          200: '#a3f5c1',
          300: '#6ee7a2',
          400: '#34d37e',
          500: '#198754', // Bootstrap success
          600: '#146d43',
          700: '#105336',
          800: '#0b3925',
          900: '#071f15',
          DEFAULT: '#198754',
        },
        warning: {
          50:  '#fffbeb',
          100: '#fff3c4',
          200: '#ffe588',
          300: '#ffd54f',
          400: '#ffc107', // Bootstrap warning
          500: '#e6ad00',
          600: '#b38600',
          700: '#806000',
          800: '#4d3a00',
          900: '#1a1300',
          DEFAULT: '#ffc107',
        },
        danger: {
          50:  '#fef2f2',
          100: '#fde3e3',
          200: '#fbc8c8',
          300: '#f8a1a1',
          400: '#f06b6b',
          500: '#dc3545', // Bootstrap danger
          600: '#b02a37',
          700: '#842029',
          800: '#58151c',
          900: '#2c0b0e',
          DEFAULT: '#dc3545',
        },
        info: {
          50:  '#ecfdff',
          100: '#cff7fe',
          200: '#a5effc',
          300: '#67e3f9',
          400: '#22ccee',
          500: '#0dcaf0', // Bootstrap info
          600: '#0aa2c0',
          700: '#087990',
          800: '#055160',
          900: '#032830',
          DEFAULT: '#0dcaf0',
        },
        // Edmin Layout Colors
        sidebar: {
          bg:      '#22262c',   // dark sidebar background (data-theme=dark-sidebar)
          card:    '#292E37',   // dark sidebar card/item bg
          text:    'rgba(255, 255, 255, 0.5)',  // --body-font-color in dark sidebar
          hover:   'rgba(255, 255, 255, 0.8)',  // --light-font in dark sidebar
          active:  '#43B9B2',   // active item uses theme-default
          DEFAULT: '#22262c',
        },
        body: {
          bg:      '#F9FAFC',   // --body-color
          DEFAULT: '#F9FAFC',
        },
        card: {
          bg:      '#ffffff',   // --card-color
          border:  '#f3f3f3',   // --card-border-color
          DEFAULT: '#ffffff',
        },
        text: {
          primary:   '#3D3D47',   // --body-font-color
          secondary: '#9B9B9B',   // --text-light-gray
          dark:      '#363636',   // --text-dark-black
          muted:     'rgba(153, 153, 169, 0.8)', // --text-gray
        },
      },
      fontFamily: {
        sans: ['Rubik', 'sans-serif'],
      },
      boxShadow: {
        card:      '0 3px 18px rgba(0, 0, 0, 0.05)',
        'card-hover': '0 0 40px rgba(8, 21, 66, 0.05)',
      },
    },
  },
  plugins: [],
};
