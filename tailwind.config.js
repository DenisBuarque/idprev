module.exports = {
    future: {},
    purge: [],
    theme: {
      extend: {
        backgroundImage: theme => ({
          'banner-principal': "url('/images/banner-principal-2.jpg')",
        })
      },
    },
    variants: {
      backgroundImage: ['responsive', 'cover'],
    },
    plugins: [
      require('tailwindcss'),
      require('autoprefixer'),
    ],
  }