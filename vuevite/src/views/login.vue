<template>
    <form @submit.prevent="validate()" class="page smallFix flex-center mx-5">
        <Card style="width: 25rem; overflow: hidden">
            <template #header>
                <img alt="user header" src="/assets/login/login.png"
                    class="h-full w-full object-center object-cover relative p-5 pt-6 bg-[var(--primary)] dark:bg-[var(--primary-dark)]" />
            </template>
            <template #title>Sign in</template>
            <template #subtitle>Log into your account</template>
            <template #content>
                <div class="flex flex-col gap-4 my-4">
                    <InputGroup>
                        <InputGroupAddon>
                            <i class="pi pi-user"></i>
                        </InputGroupAddon>
                        <InputText placeholder="Username" autofocus v-model="login.username" />
                    </InputGroup>
                    <InputGroup>
                        <InputGroupAddon>
                            <i class="pi pi-key"></i>
                        </InputGroupAddon>
                        <InputText type="password" placeholder="Password" v-model="login.password"
                            @blur="() => {this.$refs.loginButton.$el.focus();}" />
                    </InputGroup>
                </div>
            </template>
            <template #footer>
                <div class="text-center">
                    <div class="flex gap-4">
                        <Button @click="() => {this.$router.push({name: 'home'});}" label="Home" severity="secondary"
                            outlined class="w-full" />
                        <Button type="submit" label="Login" ref="loginButton" class="w-full" />
                    </div>
                    <div class="mt-5 text-sm">
                        Are you an administrator?
                        <router-link :to="{name: 'register'}"
                            class="text-[var(--primary)] font-semibold">Register.</router-link>
                        <!-- :to="{name: 'register'}" -->
                    </div>
                </div>
            </template>
        </Card>
    </form>
</template>

<script>
    import { useToast } from 'primevue/usetoast';
    import { useConfirm } from "primevue/useconfirm";

    export default {
        name: 'register',
        data() {
            return {
                login: {
                    username: '',
                    password: ''
                }
            }
        },

        setup() {
            const toast = useToast();
            const confirm = useConfirm();

            return { toast, confirm };
        },
        methods: {
            validate() {
                if (this.login.username === '' || this.login.password === '') {
                    this.$toast.add({
                        severity: 'error',
                        summary: 'Error',
                        detail: 'Username and password are required.',
                        life: 3000
                    });
                    return false;
                } else {
                    return this.onLogin();
                }
            },
            onLogin() {
                this.mf_login(this.login).then((response) => {
                    if (response.status === 200) {
                        console.log(response)
                        this.$router.push({ name: 'dashboard' });
                    }
                }).catch((e) => {
                    console.log(e)
                    this.toast.add({
                        severity: 'error',
                        summary: 'Error',
                        detail: 'Invalid username or password.',
                        life: 3000
                    });
                });
            }
        }
    }
</script>
