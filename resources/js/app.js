require('./bootstrap');

import { createApp, h, ref } from 'vue';
import { createInertiaApp, usePage } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { Inertia } from '@inertiajs/inertia';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

Object.defineProperty(window, '$flashes', {
    value: ref([]),
})

Object.defineProperty(window, '__', {
    value: text => {
        const translations = usePage().props.value.$translations

        for (let key in usePage().props.value.translations) {
            translations[key] = usePage().props.value.translations[key]
        }

        if (translations && translations.hasOwnProperty(text)) {
            return translations[text]
        }

        return text
    }
})

Object.defineProperty(window, 'showFlashMessage', {
    value() {
        for (let flash of usePage().props.value.flash) {
            window.$flashes.value.push(flash)

            if (flash.timer) {
                setTimeout(() => {
                    window.$flashes.value = window.$flashes.value.filter((f, i) => i !== window.$flashes.value.indexOf(flash))
                }, 5000)
            }
        }
    }
})

Object.defineProperty(window, 'pushFlashMessage', {
    value(flash) {
        window.$flashes.value.push(flash)

        if (flash.timer) {
            setTimeout(() => {
                window.$flashes.value = window.$flashes.value.filter((f, i) => i !== window.$flashes.value.indexOf(flash))
            }, flash.timer)
        }
    },
})

Object.defineProperty(window, 'removeFlashMessage', {
    value(flash) {
        window.$flashes.value = window.$flashes.value.filter((f, i) => i !== window.$flashes.value.indexOf(flash))
    }
})

Object.defineProperty(window, '$token', {
    value() {
        return usePage().props.value.token
    },
})

Object.defineProperty(window, 'can', {
    value() {
        const abilities = Array.isArray(arguments[0]) ? arguments[0] : (arguments.length > 1 ? [...arguments] : arguments[0])
        const roles = usePage().props.value.$roles || []
        const permissions = usePage().props.value.$permission || []

        if (Array.isArray(abilities)) {
            for (let ability of abilities) {
                if (window.can(ability)) {
                    return true
                }
            }
        } else {
            for (let role of roles) {
                for (let permission of role.permissions) {
                    if (permission.name === abilities) {
                        return true
                    }
                }
            }

            for (let permission of permissions) {
                if (permission.name === abilities) {
                    return true
                }
            }
        }

        return false
    },
})

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .mixin({
                methods: {
                    __,
                    can,
                    route,
                    usePage,
                    showFlashMessage,
                    pushFlashMessage,
                    removeFlashMessage,
                    $flashes() {
                        return window.$flashes.value
                    },
                    $token() {
                        return window.$token
                    },
                }
            })
            .mount(el);
    },
});

window.usePage = usePage

Inertia.on('finish', e => {
    showFlashMessage()
})

InertiaProgress.init({
    delay: 0,
    color: '#fff',
})