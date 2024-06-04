<template>
  <div class="">
    <Menubar :model="items">
      <template #start>
        <div class="font-bold mr-3 mc ml-2 cursor-none max-md:hidden dark:text-[var(--primary)]">
          {{mf.app.title}}
        </div>
      </template>
      <template #item="{ item, props, hasSubmenu }">
        <!-- vif adminonly -->
        <router-link v-if="item.route"  v-slot="{ href, navigate }" :to="item.route" custom>
          <a :href="href" v-bind="props.action" @click="navigate">
            <span :class="item.icon" />
            <span>{{ item.label }}</span>
          </a>
        </router-link>
        <a v-else :href="item.url" :target="item.target" v-bind="props.action">
          <span :class="item.icon" />
          <span class="">{{ item.label }}</span>
          <span v-if="hasSubmenu" class="pi pi-fw pi-angle-down ml-[-5px]" />
        </a>
      </template>
      <template #end>

        <div class="flex items-center gap-2" v-auto-animate>


          <Button @click="mf_dark()" :outlined="mf_isDark() ? true : false"
            :severity="mf_isDark() ? 'secondary' : 'primary'"><span
              :class=" mf_isDark() ? 'pi pi-moon' : 'pi pi-sun'"></span></Button>
          <!-- <i-mdi-account-circle /> -->
         <div class="max-md:hidden">
              <Button v-if="mf_auth"  size="small" @click="()=>{this.$router.push({name: 'dashboard'})}"
                :outlined="mf_isDark() ? true : false" :severity="mf_isDark() ? 'secondary' : 'primary'" label=""><span
                  class="pi pi-table "></span>Dashboard</Button>
         </div>
          <!-- <IconField>
            <InputIcon v-if="searchLoading" class="pi pi-spin pi-spinner" />
            <InputIcon v-else class="pi pi-search" :class="searches ? 'cursor-pointer' : ''" @click="doSearch()" />
            <InputText v-model="searches" class="search" placeholder="Search" />
          </IconField> -->

          <Button @click="mf_onSignOut()" v-if="mf_auth" icon="pi pi-sign-out" aria-label="Save" size="small" outlined
            severity="secondary" />
          <Avatar @click="()=>{this.$router.push({name: 'profile'})}" v-if="mf_auth"
            :image="mf.backend.storage + this.mf_user.user.pfp" class="ml-2 cursor-pointer" round shape="circle"
            @error="avatarError = true" :label=" avatarError ? capitalizeFirstLetter(mf_user.user.username[0]) : null" />

          <router-link v-else :to="{name: 'login'}">
            <Button icon="pi pi-sign-in" aria-label="Save" size="small" outlined severity="secondary" />
          </router-link>
        </div>
      </template>
    </Menubar>
  </div>
</template>

<script>
  export default {
    name: 'header',
    methods: {
      // doSearch() {
      //   if (this.searches) {
      //     this.searchLoading = true;
      //     setTimeout(() => {
      //       this.searchLoading = false;
      //       alert('searching for ' + this.searches);
      //     }, 1000);

      //   }
      // }
    },
    data() {
      return {
        avatarError: false,
        // Add your data properties here
        // searches: '',
        // searchLoading: false,
        items: [
          {
            route: '/home',
            icon: 'pi pi-home',
            label: 'Home',
          },
          {
            route: '/about',
            icon: 'pi pi-info',
            label: 'About',
            adminonly: true
          },
          {
            url: 'https://example.com',
            target: '_blank',
            icon: 'pi pi-link',
            label: 'External Link'
          },
          // submenu
          {
            label: 'Submenu',
            icon: 'pi pi-fw pi-bars',
            items: [
              {
                route: '/submenu1',
                icon: 'pi pi-fw pi-bars',
                label: 'Submenu 1'
              },
              {
                route: '/submenu2',
                icon: 'pi pi-fw pi-bars',
                label: 'Submenu 2'
              }
            ]
          }
        ]
      }
    },
  }
</script>

<style scoped>
  .search {
    width: 200px;
    max-width: 40vw;
  }

  .p-menubar {
    /* border bottom only */
    border: 0px;
    border-bottom: 1px solid var(--p-menubar-border-color);
    border-radius: 0px;
  }
</style>
