var Encore = require('@symfony/webpack-encore');

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
    // JS entry
    // .addEntry('js/addList', './assets/js/addList.js')
    // .addEntry('js/comment', './assets/js/comment.js')
    // .addEntry('js/homeSearchBar', './assets/js/homeSearchBar.js')
    // .addEntry('js/infoPage', './assets/js/infoPage.js')
    // .addEntry('js/listSearchBar', './assets/js/listSearchBar.js')
    // .addEntry('js/main', './assets/js/main.js')
    // .addEntry('js/my_progressbar', './assets/js/my_progressbar.js')
    // .addEntry('js/ratingBar', './assets/js/ratingBar.js')
    // .addEntry('js/sidebar', './assets/js/sidebar.js')
    // .addEntry('js/toWatchBtn', './assets/js/toWatchBtn.js')
    // .addEntry('js/watchBtn', './assets/js/watchBtn.js')

    // Css Entry
    .addStyleEntry('css/home', './assets/scss/home.css')
    .addStyleEntry('css/info_page', './assets/scss/info_page.css')
    .addStyleEntry('css/login', './assets/scss/login.css')
    .addStyleEntry('css/main', './assets/scss/main.css')
    .addStyleEntry('css/navbar', './assets/scss/navbar.css')
    .addStyleEntry('css/profil_page', './assets/scss/profil_page.css')
    .addStyleEntry('css/ratingBar', './assets/scss/ratingBar.css')
    .addStyleEntry('css/register', './assets/scss/register.css')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()
    // .disableSingleRuntimeChunk() 

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
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    // enables Sass/SCSS support
    .enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    // .autoProvidejQuery()

    // uncomment if you use API Platform Admin (composer req api-admin)
    //.enableReactPreset()
    //.addEntry('admin', './assets/js/admin.js')
;

module.exports = Encore.getWebpackConfig();
