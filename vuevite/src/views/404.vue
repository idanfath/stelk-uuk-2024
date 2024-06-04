<template>
    <div class="page smallFix flex-center">
        <Card style="width: 25rem; overflow: hidden" class="mx-5">
            <template #header>
                <img alt="404 Not Found" src="/assets/404/404.png" class="h-full w-full object-center object-cover relative" style="top: 20px;"/>
            </template>
            <template #title>
                Not Found
            </template>
            <template #subtitle>The requested url was not found</template>
            <template #content>
                <p class="my-3">
                    The page you're looking for may have been removed or is temporarily unavailable. Please check the URL or return to our homepage.
                </p>
            </template>
            <template #footer>
                    <router-link to="/">
                        <Button label="Return"  class="w-full" severity="danger" />
                    </router-link>
            </template>
        </Card>
    </div>
</template>
