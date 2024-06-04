import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import path from 'path'
import Icons from 'unplugin-icons/vite'
import IconsResolver from 'unplugin-icons/resolver'
import {PrimeVueResolver} from 'unplugin-vue-components/resolvers'

// https://vitejs.dev/config/
export default defineConfig({
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'src')
    }
  },
  plugins: [
    vue(),
    Icons({
      autoInstall: true,
    }),
    Components({
      dirs: ['./src/components'],
      extensions: ['vue'],
      directoryAsNamespace: true,
      resolvers: [
        PrimeVueResolver(),
        IconsResolver({
          componentPrefix: 'i', // default
        }
        ),
      ],
    }),
    AutoImport({
      include: [
        /\.[tj]sx?$/,
        /\.vue$/,
        /\.vue\?vue/, // .vue
      ],
      dirs: ['./src',],
      imports: [
        'vue',
        'vue-router',
        {
          'vuex': ['mapState', 'mapGetters', 'mapMutations', 'mapActions'],
          '@vueuse/core': ['useStorage', 'useWindowSize'],
          'swiper/vue': ['Swiper', 'SwiperSlide'],
          '@vueuse/router': ['useRouteParams'],
          'axios': [['default', 'axios']], //default as axios
          'sweetalert2': [['default', 'Swal']],
          '@vuelidate/core': ['useVuelidate'],
          '@vuelidate/validators': ['email', 'required', 'minLength', 'maxLength', 'sameAs', 'requiredIf', 'requiredUnless', 'minValue', 'maxValue', 'alpha', 'alphaNum', 'numeric', 'integer', 'between', 'decimal', 'and', 'or', 'not', 'helpers'],
          '@iconify/vue': ['Icon'],
          'swiper/modules': ['Navigation', 'Pagination', 'Scrollbar', 'A11y', 'Autoplay', 'EffectFade', 'EffectCube', 'EffectFlip', 'EffectCoverflow', 'EffectCreative', 'EffectCards', 'EffectLazy', 'Thumbs'],
        }
      ]
    }),
  ],
})

