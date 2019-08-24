const path = require('path')

module.exports = {
    publicPath: '/',
    outputDir: '../server/public/assets',
    devServer: {
        proxy: {
            '/api': {
                target: 'http://localhost:8081',
                changeOrigin: true,
                secure: false,
            },
        },
    },
    chainWebpack: config => {
        config.resolve.alias
            .set('design-system', path.resolve(__dirname, 'src/components/design_system'))
            .set('api-client',    path.resolve(__dirname, 'src/common/apiClient.js'))
    },
}