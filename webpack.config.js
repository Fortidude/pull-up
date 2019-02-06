var Encore = require('@symfony/webpack-encore');


Encore
    // directory where all compiled assets will be stored
    .setOutputPath('web/build/')

    // what's the public path to this directory (relative to your project's document root dir)
    .setPublicPath('/build')

    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()

    .configureBabel(function (babelConfig) {
        babelConfig.plugins.push("@babel/plugin-proposal-class-properties");
    })

    // will output as web/build/app.js
    .addEntry('app', ['./app/Resources/js/front/app.js'])

    // will output as web/build/global.css
    .addStyleEntry('front', [
        'antd/dist/antd.css',
        './app/Resources/css/front.scss'
    ])

    // allow sass/scss files to be processed
    .enableSassLoader()

    // Enable ReactJS
    .enableReactPreset()

    // create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning()
    ;

module.exports = Encore.getWebpackConfig();