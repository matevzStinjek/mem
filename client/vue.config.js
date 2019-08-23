module.exports = {
    configureWebpack: config => {
        config.resolve.alias['design-system'] = path.resolve(__dirname, './components/design_system')
    },
}