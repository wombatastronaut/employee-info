export function get(url) {
    return axios({
    	method: 'GET',
    	url: url,
    })
}

export function post(url, payload) {
    return axios({
    	method: 'POST',
    	url: url,
    	data: payload
    })
}


export function interceptors(cb) {
    axios.interceptors.response.use((res) => {
        return res;
    }, (err) => {
        cb(err)
        return Promise.reject(err)
    })
}
