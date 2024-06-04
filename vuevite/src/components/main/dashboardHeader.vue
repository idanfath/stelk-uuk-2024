<template>
    <div class="">
        <Menubar :model="items">
            <template #start>
                <div class="font-bold mr-3 mc ml-2 cursor-none  dark:text-[var(--primary)]">
                    DASHBOARD
                </div>
                <div class="xl:hidden">
                    <Button @click="$emit('onClickSidebar')" :outlined="mf_isDark() ? true : false"
                        :severity="mf_isDark() ? 'secondary' : 'primary'"><span class="pi pi-bars"></span></Button>
                </div>
            </template>
            <template #end>
                <div class="flex items-center gap-2" v-auto-animate>
                    <Button @click="mf_dark()" :outlined="mf_isDark() ? true : false"
                        :severity="mf_isDark() ? 'secondary' : 'primary'"><span
                            :class=" mf_isDark() ? 'pi pi-moon' : 'pi pi-sun'"></span></Button>
                    <Button @click="()=>{this.$router.push({name: 'home'})}" :outlined="mf_isDark() ? true : false"
                        :severity="mf_isDark() ? 'secondary' : 'primary'"><span class="pi pi-home"></span></Button>
                    <!-- <i-mdi-account-circle /> -->
                    <!-- <IconField>
                        <InputIcon v-if="searchLoading" class="pi pi-spin pi-spinner" />
                        <InputIcon v-else class="pi pi-search" :class="searches ? 'cursor-pointer' : ''"
                            @click="doSearch()" />
                        <InputText v-model="searches" class="search" placeholder="Search" />
                    </IconField> -->
                    <Button @click="()=>{this.$router.push({name: 'register'})}" v-if="mf_user.user.role === 'administrator'" icon="pi pi-asterisk"
                        aria-label="Save" size="small" outlined severity="info" />
                    <Button @click="mf_onSignOut()" v-if="mf_auth" icon="pi pi-sign-out" aria-label="Save" size="small"
                        outlined severity="secondary" />
                    
                    <Avatar @click="()=>{this.$router.push({name: 'profile'})}" style="background-color: var(--grey);"
                        v-if="mf_auth" :image="mf.backend.storage + this.mf_user.user.pfp" class="ml-2 cursor-pointer"
                        round shape="circle" @error="avatarError = true" :label=" avatarError ? 'P' : null" />
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
            //     if (this.searches) {
            //         this.searchLoading = true;
            //         setTimeout(() => {
            //             this.searchLoading = false;
            //             alert('searching for ' + this.searches);
            //         }, 1000);

            //     }
            // }
        },
        emits: ['onClickSidebar'],
        data() {
            return {
                avatarError: false,
                // Add your data properties here
                // searches: '',
                // searchLoading: false,
                // items: [
                //     {
                //         route: '/home',
                //         icon: 'pi pi-home',
                //         label: 'Home',
                //     },
                //     // {
                //     //   url: 'https://example.com',
                //     //   target: '_blank',
                //     //   icon: 'pi pi-link',
                //     //   label: 'External Link'
                //     // },
                //     {
                //         label: 'Submenu',
                //         icon: 'pi pi-fw pi-bars',
                //         items: [
                //             {
                //                 route: '/submenu1',
                //                 icon: 'pi pi-fw pi-bars',
                //                 label: 'Submenu 1'
                //             },
                //             {
                //                 route: '/submenu2',
                //                 icon: 'pi pi-fw pi-bars',
                //                 label: 'Submenu 2'
                //             }
                //         ]
                //     }
                // ]
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
