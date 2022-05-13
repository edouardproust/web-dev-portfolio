const Encore = require('@symfony/webpack-encore');
const CKEditorWebpackPlugin = require( '@ckeditor/ckeditor5-dev-webpack-plugin' );
const { styles } = require( '@ckeditor/ckeditor5-dev-utils' );

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    //.setManifestKeyPrefix('build/')

    .addEntry('app', './assets/_app.js')
    .addEntry('admin', './assets/_admin.js')
    .addEntry('404', './assets/404.js')
    .addEntry('about', './assets/about.js')
    .addEntry('contact', './assets/contact.js')
    .addEntry('home', './assets/home.js')
    .addEntry('lesson', './assets/lesson.js')
    .addEntry('lessons', './assets/lessons.js')
    .addEntry('login-register', './assets/login-register.js')
    .addEntry('post', './assets/post.js')
    .addEntry('posts', './assets/posts.js')
    .addEntry('project', './assets/project.js')
    .addEntry('projects', './assets/projects.js')
    .addEntry('user', './assets/user.js')

    .enableStimulusBridge('./assets/controllers.json')
    
    .splitEntryChunks()
    
    .enableSingleRuntimeChunk()
    
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })
    
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    .copyFiles({
        from: './assets/images',
        to: 'images/[path][name].[hash:8].[ext]',
    })
    .copyFiles({
        from: './assets/ckfinder',
        to: 'ckfinder/[path][name].[ext]',
    })
    
    .enableSassLoader()
    
    .autoProvidejQuery()

    // CKEditor

    // .addPlugin( new CKEditorWebpackPlugin( {
        // See https://ckeditor.com/docs/ckeditor5/latest/features/ui-language.html
        // language: 'pl'
    // } ) )

    // Use raw-loader for CKEditor 5 SVG files.
    .addRule( {
        test: /ckeditor5-[^/\\]+[/\\]theme[/\\]icons[/\\][^/\\]+\.svg$/,
        loader: 'raw-loader'
    } )

    // Configure other image loaders to exclude CKEditor 5 SVG files.
    .configureLoaderRule( 'images', loader => {
        loader.exclude = /ckeditor5-[^/\\]+[/\\]theme[/\\]icons[/\\][^/\\]+\.svg$/;
    } )

    // Configure PostCSS loader.
    .addLoader({
        test: /ckeditor5-[^/\\]+[/\\]theme[/\\].+\.css$/,
        loader: 'postcss-loader',
        options: {
            postcssOptions: styles.getPostCssConfig( {
                themeImporter: {
                    themePath: require.resolve( '@ckeditor/ckeditor5-theme-lark' )
                },
                minify: true
            } )
        }
    } )
;

module.exports = Encore.getWebpackConfig();
