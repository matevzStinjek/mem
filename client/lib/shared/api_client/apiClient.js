import axios from 'axios'

class ApiClient {

    constructor () {
        this.baseURL = '//localhost:8081/api/';
        // this.baseURL = '/api/';
    }

    get (url) {
        return this._makeRequest('GET', url)
    }

    post (url, body) {
        return this._makeRequest('POST', url, body)
    }

    put (url, body) {
        return this._makeRequest('PUT', url, body)
    }

    delete (url) {
        return this._makeRequest('DELETE', url)
    }

    _makeRequest (method, url, data = null) {
        return axios({ method, baseURL: this.baseURL, url, data }).then(r => r.data)
    }
}

export default new ApiClient()
