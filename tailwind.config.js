/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#6DBE3B',
          dark: '#58A02E',
          light: '#E8F5DE',
          xlight: '#F4FAF0',
        },
        dark: {
          DEFAULT: '#1A2332', // note: '#2D3748' (dark-2) maps to gray-800 already in Tailwind's default palette
        },
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
        display: ['Plus Jakarta Sans', 'sans-serif'],
      },
      keyframes: {
        float: {
          '0%, 100%': { transform: 'translateY(0px)' },
          '50%': { transform: 'translateY(-12px)' },
        },
        fadeInUp: {
          from: { opacity: 0, transform: 'translateY(30px)' },
          to: { opacity: 1, transform: 'translateY(0)' },
        },
        marquee: {
          '0%': { transform: 'translateX(0)' },
          '100%': { transform: 'translateX(-50%)' },
        },
      },
      animation: {
        float: 'float 4s ease-in-out infinite',
        'fade-up': 'fadeInUp 0.6s ease both',
        marquee: 'marquee 20s linear infinite',
      },
    },
  },
  plugins: [],
}
