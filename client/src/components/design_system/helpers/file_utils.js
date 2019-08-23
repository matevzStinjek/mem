function loadVideo (file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader()
        reader.onloadend = () => {
            const blob = new Blob([reader.result], { type: file.type })
            const url = URL.createObjectURL(blob)
            const video = document.createElement('video')
            video.preload = 'auto'

            const errorCallback = (e) => {
                reject()
                cleanVideo()
            }

            const loadedCallback = () => {
                const mozAudio = video.mozHasAudio
                const safariAudio = video.audioTracks && video.audioTracks.length !== 0
                const chromeAudio = video.webkitAudioDecodedByteCount > 0

                resolve({
                    width: video.videoWidth,
                    height: video.videoHeight,
                    duration: video.duration,
                    hasAudio: mozAudio || safariAudio || chromeAudio,
                })

                cleanVideo()
            }

            const cleanVideo = () => {
                video.removeEventListener('error', errorCallback)
                video.removeEventListener('loadeddata', loadedCallback)
                video.src = ''
                URL.revokeObjectURL(url)
            }

            video.addEventListener('error', errorCallback)
            video.addEventListener('loadeddata', loadedCallback)
            video.src = url
        }

        reader.readAsArrayBuffer(file)
    })
}

function loadImage (file) {
    return new Promise ((resolve, reject) => {
        const reader = new FileReader()
        reader.onloadend = () => {
            const image = new Image()
            image.onerror = () => {
                reject()
                image.src = ''
            }
            image.onload = () => {
                resolve({
                    width: image.width,
                    height: image.height,
                })
            }
            image.src = reader.result
        }

        reader.readAsDataURL(file)
    })
}

export function readFileMetadata (file, onUpdate = null) {
    let currentData = {}
    const updateData = (addData) => {
        currentData = { ...currentData, ...addData }
        if (onUpdate) {
            onUpdate(currentData)
        }
    }
    return new Promise((resolve, reject) => {
        updateData({ name: file.name, type: file.type, size: file.size })

        let metadataPromise = new Promise((resolve) => resolve())
        if (file.type.indexOf('image') === 0) {
            metadataPromise = loadImage(file)
        } else if (file.type.indexOf('video') === 0) {
            metadataPromise = loadVideo(file)
        }

        metadataPromise.then((image) => {
            updateData(image)
            resolve(currentData)
        }).catch(() => {
            updateData({ unreadable: true })
            resolve(currentData)
        })
    })
}

export function uploadFile (file, url, onProgress = null) {
    const activeXhr = new XMLHttpRequest()
    activeXhr.open('POST', url, true)

    const promise = new Promise((resolve, reject) => {
        if (onProgress) {
            activeXhr.upload.onprogress = (e) => {
                if (e.lengthComputable) {
                    onProgress(e.loaded / e.total)
                }
            }
        }

        activeXhr.onload = function () {
            if (this.status == 201) {
                const data = JSON.parse(this.response)
                resolve(data.hash)
            } else {
                reject('There was an error uploading this file to the server.')
            }
        }

        activeXhr.send(file)
    })
    promise.abort = function () {
        activeXhr.abort()
    }
    return promise
}
