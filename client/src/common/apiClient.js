import axios from 'axios'

class ApiClient {

    constructor () {
        this.baseURL = 'localhost:8081/api/';
    }

    get (url) {
        return this._postRequest('GET', url)
    }

    post (url, body) {
        return this._postRequest('POST', url, body)
    }

    put (url, body) {
        return this._postRequest('PUT', url, body)
    }

    delete (url) {
        return this._postRequest('DELETE', url)
    }

    _postRequest (method, url, data = null) {
        return axios({ method, baseUrl: this.baseURL, url, data })
    }
}

export default new ApiClient()
