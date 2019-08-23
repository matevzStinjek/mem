import axios from 'axios'

class ApiClient {

    get (url) {
        return this._postRequest('GET', url, null, config)
    }

    _postRequest (method, url, body = null) {
        return axios({
            method,
            url,
            data: body,
        })
    }
}

export default new ApiClient()
