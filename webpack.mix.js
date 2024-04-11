let mix = require('laravel-mix')
const webpack = require('webpack')

mix.ts('resources/menu/ts/main.ts', 'assets/main.js').vue()
mix.sass('resources/menu/sass/main.sass', 'assets/main.css')

mix.options({
    processCssUrls: false,
})

mix.webpackConfig({
    plugins: [
        new webpack.DefinePlugin({
            __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: 'false',
        })
    ],
})

mix.alias({
    '@': '/resources/common/ts',
    '@menu': '/resources/menu/ts',
})