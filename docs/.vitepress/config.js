import { defineConfig } from 'vitepress';

// https://github.com/vuejs/vitepress/blob/main/docs/.vitepress/config/en.ts
export default defineConfig({
    title: 'Laravel OpenAPI',
    description: 'OpenAPI spec generator for Laravel Applications',
    base: '/laravel-openapi/',

    lastUpdated: false, // this needs git installed
    cleanUrls: true,
    metaChunk: true,

    themeConfig: {
        socialLinks: [
            { icon: 'github', link: 'https://github.com/Mohammad-Alavi/laravel-openapi' },
        ],
        nav: nav(),
        sidebar: {
            '/guide/': {
                base: '/guide/',
                items: sidebarGuide(),
            },
            '/reference/': {
                base: '/reference/',
                items: sidebarReference(),
            },
        },

        editLink: {
            pattern: 'https://github.com/Mohammad-Alavi/laravel-openapi/edit/master/docs/:path',
            text: 'Edit this page on GitHub',
        },

        footer: {
            message: 'Released under the MIT License.',
            copyright: 'Copyright © 2023-present Mohammad Alavi',
        },
    },

    // search: {
    //     provider: 'algolia',
    //     options: {
    //         appId: '8J64VVRP8K',
    //         apiKey: 'a18e2f4cc5665f6602c5631fd868adfd',
    //         indexName: 'laravel-openapi',
    //     },
    // },
});

function nav() {
    return [
        {
            text: 'Guide',
            link: 'guide/what-is-laravel-openapi',
            activeMatch: '/guide/',
        },
        {
            text: 'Reference',
            link: 'reference/generator-config',
            activeMatch: '/reference/',
        },
    ];
}

function sidebarReference() {
    return [
        {
            text: 'Reference',
            items: [
                {
                    text: 'Config & API Reference',
                    link: 'generator-config',
                },
            ],
        },
    ];
}

function sidebarGuide() {
    return [
        {
            text: 'Introduction',
            collapsed: false,
            items: [
                { text: 'What is Laravel OpenAPI?', link: 'what-is-laravel-openapi' },
                { text: 'Getting Started', link: 'getting-started' },
            ],
        },
        {
            text: 'Path',
            collapsed: false,
            items: [
                { text: 'Operation', link: 'operations' },
                { text: 'Parameter', link: 'parameters' },
                { text: 'Request Body', link: 'request-bodies' },
                { text: 'Response', link: 'responses' },
            ],
        },
        {
            text: 'Sag',
            collapsed: false,
            items: [
                { text: 'Schema', link: 'schemas' },
                { text: 'Collection', link: 'collections' },
                { text: 'Middleware', link: 'middlewares' },
                { text: 'Security', link: 'security' },
            ],
        },
        {
            text: 'Laragen',
            collapsed: false,
            items: [
                { text: 'Auto-Generation', link: 'laragen-auto-generation' },
                { text: 'Annotations', link: 'laragen-annotations' },
                { text: 'Extending', link: 'laragen-extending' },
            ],
        },
        { text: 'Config & API Reference', base: '/reference/', link: 'generator-config' },
    ];
}