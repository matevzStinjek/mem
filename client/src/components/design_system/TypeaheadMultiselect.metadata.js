export default {
    usecases: [
        {
            name: 'Basic',
            label: 'Something',
            value: [],
            getSuggestions: (text) => {
                return [
                    { id: '1', label: "Something Something Something Something Something Something Something Something Something", metadata: 'zan.kusterle@gmail.comzan.kusterle@gmail.comzan.kusterle@gmail.com', icon: 'plus-circle' },
                    { id: '2', label: "Lorem" },
                    { id: '3', label: "Ipsum", metadata: 'someone@lorem.ipsum' },
                ]
            },
            isValid: (text) => {
                if (text && text.length > 0 && text.indexOf('@') === -1) {
                    return 'Not a valid email address'
                }
                return null
            },
        },
    ],
}
