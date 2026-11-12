import { defineConfig } from 'vitepress'

export default defineConfig({
    title: 'DataTable',
    description: 'Headless tables for Laravel and Inertia.',
    lang: 'en-US',
    themeConfig: {
        sidebar: [{ text: 'Introduction', link: '/' }],
        search: {
            provider: 'local',
        },
    },
})
