export default {
    metaName: 'Typeahead',
    usecases: [
        {
            name: 'Basic',
            label: 'Something',
            value: 'Lorem',
            getSuggestions: () => [
                { id: '1', label: "Something", metadata: 'zan.kusterle@gmail.com', icon: 'plus-circle' },
                { id: '2', label: "Lorem" },
                { id: '3', label: "Ipsum", metadata: 'someone@lorem.ipsum' },
            ],
        },
    ],
}
