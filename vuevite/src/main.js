import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import './styles/main.css'
import myFunct from './myFunct'
import { MotionPlugin } from '@vueuse/motion'
import { autoAnimatePlugin } from '@formkit/auto-animate/vue'
import 'animate.css'
import 'swiper/swiper-bundle.css'
import PrimeVue from 'primevue/config'
import Aura from 'primevue/themes/Aura'
import 'primeicons/primeicons.css'
import axios from 'axios'
import ToastService from 'primevue/toastservice'
import ConfirmationService from 'primevue/confirmationservice';
import store from '@/store'

const app = createApp(App);

import '@/store/subscriber'
axios.defaults.baseURL = 'http://127.0.0.1:8000/api/'
store.dispatch('auth/attempt', localStorage.getItem('token')).then(() => {

app.use(PrimeVue, {
        pt: {
            avatar: {
                image: {
                    class: 'avatar-image',
                }
            },
        },
        theme: {
            // preset: Lara,
            preset: Aura,
            options: {
                darkModeSelector: '.dark',
            }
        }
    })
    .use(ToastService)
    .use(ConfirmationService)
    .use(myFunct)
    .use(autoAnimatePlugin)
    .use(MotionPlugin)
    .use(router)
    .use(store)
    .mount('#app')
})
