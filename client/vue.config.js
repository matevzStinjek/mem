const path = require('path')

module.exports = {
    publicPath: '/',
    outputDir: '../server/public/assets',
    devServer: {
        proxy: {
            "/api/*": {
                target: 'http://localhost:8081',
                changeOrigin: true,
                secure: false,
            },
        },
    },
    chainWebpack: config => {
        config.resolve.alias
            .set('api-client', path.resolve(__dirname, 'src/api_client/apiClient.js'))
    },
}