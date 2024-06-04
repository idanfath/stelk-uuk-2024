<script setup>
</script>

<template>
  <u-cursor></u-cursor>
  <ScrollTop />
  <router-view />
  <Toast />
  <ConfirmPopup></ConfirmPopup>
</template>

<style>
  /* ubah warna seleksi teks ke invert */ 
  ::selection { 
    background-color: var(--primary); 
    color: #000; 
  } 
 
  /* noscrollbar */ 
  ::-webkit-scrollbar { 
    scrollbar-width: none; 
    width: 0px; 
    display: none; 
  }
</style>
<script>
  import Toast from 'primevue/toast';
  import ConfirmPopup from 'primevue/confirmpopup';

  export default {
    name: 'App',
    components: {
      Toast,
      ConfirmPopup,
    },
  }
</script>
