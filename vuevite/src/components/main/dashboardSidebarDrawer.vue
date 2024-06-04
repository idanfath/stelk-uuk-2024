<template>
    <Sidebar :visible="visible">
        <template #container="{closeCallback}">
            <div class="flex flex-col h-full sticky top-0"
                style="border-right: 1px solid #3f3f46;">
                <div class="flex items-center justify-between px-6 mt-8 shrink-0">
                    <span class="inline-flex items-center gap-2">
                        <Avatar :image="mf.backend.storage + mf_user.user.pfp" @error="avatarError = true" :label=" avatarError ? capitalizeFirstLetter(mf_user.user.username[0]) : null" size="large" shape="circle" />
                        <span class="font-semibold text-2xl text-primary">{{capitalizeFirstLetter(mf_user.user.username)}}</span>
                    </span>
                    <span>
                        <Button type="button" @click="$emit('onClickClose')" icon="pi pi-times" outlined
                            class="h-2rem w-2rem"></Button>
                    </span>
                </div>
                <div class="overflow-y-auto">
                    <ul class="list-none p-4 m-0">
                        <li>
                            <div
                                class="p-4 flex items-center justify-between text-surface-500 dark:text-surface-400 p-ripple">
                                <span class="font-semibold">TABS</span>
                            </div>
                            <ul class="list-none p-0 m-0 overflow-hidden">
                                <li v-for="item in datas.sidebar.items" :key="item.label">
                                    <router-link :to="{name: item.to}" @click="$emit('onClickItem')">
                                        <di v-ripple active active-class="!bg-red-100 dark:bg-red-800"
                                            class="flex items-center cursor-pointer p-4 rounded text-surface-700 hover:bg-surface-100 dark:text-surface-0 dark:hover:bg-surface-800 duration-150 transition-colors p-ripple">
                                            <i :class="item.icon" class=" mr-3"></i>
                                            <span class="font-medium">{{item.label}}</span>
                                        </di>
                                    </router-link>
                                </li>
                                <li>
                                    <router-link :to="{name: 'accounts'}" v-if="mf_user.user.role === 'administrator'">
                                        <di v-ripple active active-class="!bg-red-100 dark:bg-red-800"
                                            class="flex items-center cursor-pointer p-4 rounded text-surface-700 hover:bg-surface-100 dark:text-surface-0 dark:hover:bg-surface-800 duration-150 transition-colors p-ripple">
                                            <i class="pi pi-shield mr-3"></i>
                                            <span class="font-medium">Accounts</span>
                                        </di>
                                    </router-link>
                                </li>
                            </ul>
                            <div
                                class="p-4 flex items-center justify-between text-surface-500 dark:text-surface-400 p-ripple">
                                <span class="font-semibold">FUNCTION</span>
                            </div>
                            <ul class="list-none p-0 m-0 overflow-hidden">
                                <li>
                                    <div @click="mf_onSignOut" v-ripple active
                                        active-class="!bg-red-100 dark:bg-red-800"
                                        class="flex items-center cursor-pointer p-4 rounded text-surface-700 hover:bg-surface-100 dark:text-surface-0 dark:hover:bg-surface-800 duration-150 transition-colors p-ripple">
                                        <i class="pi pi-sign-out mr-3"></i>
                                        <span class="font-medium">Logout</span>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </template>
    </Sidebar>
</template>

<script>
    export default {
        props: {
            visible: Boolean
        },
        emits: ['onClickClose'],
        data() {
            return {
                avatarError: false,
            }
        },
        methods: {

        },
    }
</script>
