import './bootstrap';

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { Ziggy } from './ziggy.js';
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    title: title => `${title} - ${appName}`,
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .component("v-select", vSelect)
            .use(ZiggyVue, Ziggy)
            .mount(el)
    },
    progress: {
        color: '#FF0000',
    },
});

// router.on('navigate', (event) => {
//     console.log(`Navigated to ${event.detail.page.url}`)
// });
