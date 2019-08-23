export default {
    metaName: 'FileUpload',
    width: 280,
    variations: {
        theme: ['dark', 'light'],
        disabled: [false, true],
    },
    usecases: [
        {
            name: 'Empty',
            file: {},
            uploadUrl: 'https://example.com',
        },
        {
            testOnly: true,
            name: 'Uploading start',
            file: { name: 'File name', type: 'MP3' },
            uploadUrl: 'https://example.com',
        },
        {
            name: 'Uploading',
            file: { name: 'File name', type: 'MP3' },
            uploadUrl: 'https://example.com',
            setup (vm) {
                vm.progress = 50
                return new Promise(resolve => setTimeout(resolve, 1000))
            },
        },
        {
            name: 'Done',
            file: { name: 'CeltraLogo.jpg', type: 'image/jpg', thumbnailUrl: 'https://s3.amazonaws.com/test-ui-componnets/logo_400x400.jpg' },
            uploadUrl: 'https://example.com',
            setup () {
                return new Promise(resolve => setTimeout(resolve, 1000))
            },
        },
        {
            name: 'Error',
            file: {},
            error: 'Internet connection lost.',
            uploadUrl: 'https://example.com',
        },
        {
            name: 'Drop active',
            file: {},
            dropActive: true,
            uploadUrl: 'https://example.com',
        },
    ],
}
