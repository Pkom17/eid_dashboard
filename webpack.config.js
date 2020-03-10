var Encore = require('@symfony/webpack-encore');
var CopyWebpackPlugin = require('copy-webpack-plugin');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
        // directory where compiled assets will be stored
        .setOutputPath('public/build/')
        // public path used by the web server to access the output path
        .setPublicPath('/build')
        // only needed for CDN's or sub-directory deploy
        //.setManifestKeyPrefix('build/')

        /*
         * ENTRY CONFIG
         *
         * Add 1 entry for each "page" of your app
         * (including one that's included on every page - e.g. "app")
         *
         * Each entry will result in one JavaScript file (e.g. app.js)
         * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
         */
        .addEntry('app', './assets/js/app.js')
        .addEntry('form_upload', './assets/js/upload.js')
        .addEntry('home', './assets/js/home.js')
        .addEntry('trends', './assets/js/trends.js')
        .addEntry('labs', './assets/js/labs.js')
        .addEntry('org_filter', './assets/js/org_filter.js')
//        .addEntry('custom', './assets/js/custom.js')
//        .addEntry('shim', './assets/js/shim.js')
//        .addEntry('highcharts', './assets/js/highcharts.js')
//        .addEntry('exporting', './assets/js/exporting.js')
//        .addEntry('export_data', './assets/js/export-data.js')
//        .addEntry('accessibility', './assets/js/accessibility.js')
        //.addEntry('page2', './assets/js/page2.js')

        // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
        .splitEntryChunks()

        // will require an extra script tag for runtime.js
        // but, you probably want this, unless you're building a single-page app
        .enableSingleRuntimeChunk()

        /*
         * FEATURE CONFIG
         *
         * Enable & configure other features below. For a full
         * list of features, see:
         * https://symfony.com/doc/current/frontend.html#adding-more-features
         */
        .cleanupOutputBeforeBuild()
        .enableBuildNotifications()
        .enableSourceMaps(!Encore.isProduction())
        // enables hashed filenames (e.g. app.abc123.css)
        .enableVersioning(Encore.isProduction())

        // enables @babel/preset-env polyfills
        .configureBabel(() => {
        }, {
            useBuiltIns: 'usage',
            corejs: 3
        })

        // enables Sass/SCSS support
        .enableSassLoader()

        .addPlugin(new CopyWebpackPlugin([
            {from: './assets/fonts', to: 'fonts'},
            {from: './assets/images', to: 'images'}
        ]))

        // uncomment if you use TypeScript
        //.enableTypeScriptLoader()

        // uncomment to get integrity="..." attributes on your script & link tags
        // requires WebpackEncoreBundle 1.4 or higher
        //.enableIntegrityHashes(Encore.isProduction())

        // uncomment if you're having problems with a jQuery plugin
        .autoProvidejQuery()
        .autoProvideVariables()

        // uncomment if you use API Platform Admin (composer req api-admin)
        //.enableReactPreset()
        //.addEntry('admin', './assets/js/admin.js')
        ;

module.exports = Encore.getWebpackConfig();
