require('./bootstrap');

// Import modules...
import { createApp, h } from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import CKEditor from '@ckeditor/ckeditor5-vue';
import { SetupCalendar, Calendar, DatePicker } from 'v-calendar';

const el = document.getElementById('app');

createApp({
    render: () =>
        h(InertiaApp, {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: (name) => require(`./Pages/${name}`).default,
        }),
})
    .mixin({ methods: { route } })
    .use(InertiaPlugin)
    .use(CKEditor)
    // Setup the plugin with optional defaults
    .use(SetupCalendar, {})
    // Use the components
    .component('Calendar', Calendar)
    .component('DatePicker', DatePicker)
    .mount(el);

InertiaProgress.init({ color: '#4B5563' });
