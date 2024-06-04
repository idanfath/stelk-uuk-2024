<template>
    <div>
        <main-dashboardHeader @onClickSidebar="visible = !visible"></main-dashboardHeader>
        <main-dashboardSidebarDrawer :visible="visible" @onClickItem="visible = !visible" @onClickClose="visible = !visible"></main-dashboardSidebarDrawer>
        <div class="grid grid-cols-12 h-screen overflow-auto">
            <main-dashboardSidebar class="col-span-2 max-xl:hidden h-screen sticky top-0"></main-dashboardSidebar>
            <div class="col-span-10 max-xl:col-span-full">
                <div class="w-full">
                        <router-view></router-view>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'dashboardShell',
        data() {
            return {
                visible: false
            }
        },
    }
</script>

<style scoped>
</style>
