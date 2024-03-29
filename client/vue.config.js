const path = require('path')

const resolve = dir => path.resolve(__dirname, dir)

module.exports = {
    chainWebpack: config => {
        config.resolve.alias
            .set('auth', resolve('src/modules/auth'))
            .set('core', resolve('src/modules/core'))
            .set('shared', resolve('src/modules/shared'))
            // .set('subreddit', resolve('src/modules/subreddit'))
    },
}
