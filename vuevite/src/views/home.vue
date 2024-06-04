<script setup>

</script>

<template>

  <div class="page flex-center flex-col items-center text-center">

    <div v-motion-fade-visible class="flex-col select-none mc">
      <div class="text-[var(--primary)] font-bold mb-3"><i class="pi pi-whatsapp"></i>&nbsp;POWERED BY WHATSAPP</div>

      <p style="font-size: clamp(2rem, 5vw, 5rem);" class="my-2 font-bold dark:text-[var(--primary)]">Welcome to <span class="!cursor-none animate__animated animate__pulse animate__repeat-2 animate__faster animate__delay-1s inline-block">{{mf.app.title}}</span>!</p>
      <p class="my-2 mb-5 ">{{mf.app.desc}}</p>

    </div>
    <Button label="Join Now" class="font-bold mt-5 w-[200px] py-3  white-space-nowrap"></Button>

    <div v-motion-fade-visible class="flex-center gap-2 mt-2 max-md:hidden w-full chip-container absolute" style="top: 10vh;">
        <Chip @click="()=>{this.$router.push({name: i})}" v-for="(i, u) in {'Our Work':'admin', 'Trusted by Many':'Not Found', 'Why Choose Us':'dashboard'}"  :label="u"  :style="chip_isDark" class="myChip"/>
    </div>
  </div>
</template>
<style scoped>
  @keyframes slidedown-icon {
    0% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(20px);
    }

    100% {
        transform: translateY(0);
    }
}

.slidedown-icon {
    animation: slidedown-icon;
    animation-duration: 3s;
    animation-iteration-count: infinite;
}
  .chip-container .p-chip {
    padding-left: 1rem;
    padding-right: 1rem;
    font-weight: 500;
  }
  .myChip {
    @apply cursor-pointer ;
  }
</style>
<script>
  export default {
    name: 'home',
    setup() {
      const route = useRoute();
      const params = route.params;
    },
    data() {
      return {
      }
    },
    computed: {
      chip_isDark() {
        return {
          'background-color': this.mf_isDark() ? 'var(--secondary)' : 'var(--primary)',
          'color': this.mf_isDark() ? 'var(--primary)' : 'var(--inverse)'}
      }
    },
    methods: {
      testing() {

        Swal.fire({
          title: 'Hello Vue3', //or titleText to avoid HTML injection

          position: "top-start",
          padding: "1em",
          width: 600,
          heightAuto: true,
          toast: true,
          animation: true,
          allowOutsideClick: true,
          allowEscapeKey: true,
          allowEnterKey: true,

          timer: 3000,
          timerProgressBar: true,

          // imageUrl: "https://unsplash.it/400/200",
          // imageWidth: 400,
          // imageHeight: 200,
          // imageAlt: "Custom image",

          backdrop: true,
          backdrop: "rgba(0,0,123,0.4) blur(10px)",

          // ! html is not recommended for security reasons
          // html: `<b>bold text</b>, <a href="#">links</a>, and other HTML tags`,
          icon: 'success',
          footer: '<a href="#" class="no-dec-2">EH ini knp ya</a>',

          reverseButtons: false,
          focusConfirm: true,
          focusDeny: false,
          focusCancel: false,
          showConfirmButton: true,
          showDenyButton: true,
          showCloseButton: true,
          showCancelButton: true,
          denyButtonText: 'Nope',
          confirmButtonText: 'Cool',
          cancelButtonText: 'Cancel',

          // background: "#414141",
          color: "var(--primary)",
          confirmButtonColor: 'var(--confirm)',
          denyButtonColor: 'var(--error)',
          cancelButtonColor: 'var(--grey)',

          // customClass: {
          //   container: '...',
          //   popup: '...',
          //   header: '...',
          //   title: '...',
          //   closeButton: '...',
          //   icon: '...',
          //   image: '...',
          //   htmlContainer: '...',
          //   input: '...',
          //   inputLabel: '...',
          //   validationMessage: '...',
          //   actions: '...',
          //   confirmButton: '...',
          //   denyButton: '...',
          //   cancelButton: '...',
          //   loader: '...',
          //   footer: '....',
          //   timerProgressBar: '....',
          // },

          showClass: {
            popup: `animate__animated animate__fadeIn aniamte__faster`
          },
          hideClass: {
            popup: `animate__animated animate__fadeOut animate__faster`
          }

        }).then((result) => {
          if (result.isConfirmed) {
            Swal.fire('Saved!', '', 'success')
          } else if (result.isDenied) {
            Swal.fire('Fym nope?', '', 'info')
            throw new Error('Operation denied')
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire('Cancelled', '', 'error')
            throw new Error('Operation cancelled')
          }
        }).then(() => {
          console.log('done')
        })
      },
    }
  }
</script>

