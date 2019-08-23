export default {
    metaName: 'FileUploadRequirements',
    hasAbsolutePosition: true,
    usecases: [
        {
            name: 'Basic',
            requirements: [
                { name: 'Format', value: 'PNG, JPG' },
                { name: 'Size', value: 'max. 3MB' },
            ],
        },
    ],
}
