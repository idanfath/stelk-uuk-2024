<script setup>


</script>

<template>
    <form @submit.prevent class="page smallFix flex-center mx-5">
        <Card style="width: 600px; max-width: 100%; overflow: hidden">
            <template #title>Register</template>
            <template #subtitle>Register a new account</template>
            <template #content>
                <div class="flex flex-col gap-4 my-4">
                    <div
                        class="dark:bg-[var(--background)] bg-[var(--primary)] shadow-[inset_0_0px_5px_rgba(0,0,0,0.2)]  rounded-xl w-full">
                        <div class="py-5 px-4 flex items-center  justify-between">
                            <div class="flex items-center gap-3">
                                <Avatar size="xlarge" class="raised" :image="pfpView" round shape="circle" />
                                <div>
                                    <Input type="file" ref="fileInput" class="hidden" @change="onFileChange"
                                        accept="image/*" maxFileSize="5242880" />
                                    <Button :label="pfpView ? 'Change' :'Select'" severity="secondary"
                                        icon="pi pi-upload" size="small" iconPos="left" raised
                                        @Click="()=>{this.$refs.fileInput.click();}" />
                                </div>
                            </div>
                            <SelectButton class="p-button-raised" v-model="r.role" :options="roles"
                                aria-labelledby="basic" />
                        </div>
                    </div>
                    <InputGroup>
                        <InputGroupAddon>
                            <i class="pi pi-user"></i>
                        </InputGroupAddon>
                        <InputText placeholder="Username" v-model="r.username" autofocus />
                    </InputGroup>
                    <InputGroup>
                        <InputGroupAddon>
                            <i class="pi pi-phone"></i>
                        </InputGroupAddon>
                        <InputMask id="basic" v-model="r.number" :autoClear="false" mask="+99-999-9999-9999"
                            placeholder="Phone" />
                    </InputGroup>
                    <InputGroup>
                        <InputGroupAddon>
                            <i class="pi pi-envelope"></i>
                        </InputGroupAddon>
                        <InputText placeholder="Email" v-model="r.email" />
                    </InputGroup>
                    <InputGroup>
                        <InputGroupAddon>
                            <i class="pi pi-key"></i>
                        </InputGroupAddon>
                        <Password placeholder="Password" v-model="r.password" toggleMask>
                            <template #header>
                                <div class="font-semibold text-xm mb-4">Pick a password</div>
                            </template>
                            <template #footer>
                                <Divider />
                                <ul class=" my-0 leading-normal">
                                    <li class="font-semibold">Password must contain:</li>
                                    <li>Lowercase character</li>
                                    <li>Uppercase character</li>
                                    <li>Numeric character</li>
                                    <li>At least 8 characters</li>
                                </ul>
                            </template>
                        </Password>
                    </InputGroup>
                    <div v-auto-animate :class="r.password ? '' : 'hidden'  ">
                        <InputGroup v-if="r.password">
                            <InputGroupAddon>
                                <i class="pi pi-key"></i>
                            </InputGroupAddon>
                            <InputText type="password" v-model="confirmPassword" placeholder="Confirm Password" />
                        </InputGroup>
                    </div>
                    <div class="flex items-center gap-2">
                        <Checkbox id="accept" v-model="accept" name="accept" value="Accept"
                            @blur="() => {this.$refs.registButton.$el.focus();}" />
                        <label for="accept">I agree to the terms and conditions.</label>
                    </div>
                </div>
            </template>
            <template #footer>
                <div class="text-center">
                    <div class="flex gap-4 mt-0">
                        <Button @click="() => {this.$router.push({name: 'home'});}" label="Home" severity="secondary"
                            outlined class="w-[50%]" />
                        <Button @click="validate(e)" ref="registButton" type="submit" label="Register" class="w-full" />
                    </div>
                    <div class="mt-5 text-sm">
                        Already have an account?
                        <router-link :to="{name: 'login'}"
                            class="text-[var(--primary)] font-semibold">Login.</router-link>
                    </div>
                </div>
            </template>
        </Card>
        <input style="display: none;" type="name?" v-model="antibot">
    </form>
</template>

<script>
    import { useToast } from 'primevue/usetoast';
    import { useConfirm } from "primevue/useconfirm";

    export default {
        name: 'register',

        setup() {
            const toast = useToast();
            const confirm = useConfirm();

            return { toast, confirm };
        },
        computed: {
            isPasswordMatch() {
                status = this.r.password === this.confirmPassword;
                return this.r.password.length > 2 && status;
            },
            cleanNumber() {
                return this.r.number.replace(/[\s-+_]/g, '');
            },
        },
        methods: {
            onFileChange(e) {
                this.pfp = e.target.files[0];
                this.pfpView = URL.createObjectURL(this.pfp);
            },
            validate(e) {
                if (this.antibot) {
                    this.toast.add({ severity: 'error', summary: 'Error', detail: 'You are a bot', life: 3000 });
                    return false;
                } else if (!this.accept) {
                    this.toast.add({ severity: 'error', summary: 'Error', detail: 'Please accept the terms and conditions', life: 3000 });
                    return false;
                } else if (!this.r.username || !this.r.email || !this.r.password || !this.confirmPassword || !this.r.number) {
                    this.toast.add({ severity: 'error', summary: 'Error', detail: 'Please fill in all fields', life: 3000 });
                    return false;
                } else if (this.r.password.length < 8) {
                    this.toast.add({ severity: 'error', summary: 'Error', detail: 'Password must be at least 8 characters', life: 3000 });
                    return false;
                } else if (!this.r.password.match(/[a-z]/)) {
                    this.toast.add({ severity: 'error', summary: 'Error', detail: 'Password must contain lowercase characters', life: 3000 });
                    return false;
                } else if (!this.r.password.match(/[A-Z]/)) {
                    this.toast.add({ severity: 'error', summary: 'Error', detail: 'Password must contain uppercase characters', life: 3000 });
                    return false;
                } else if (!this.r.password.match(/[0-9]/)) {
                    this.toast.add({ severity: 'error', summary: 'Error', detail: 'Password must contain numeric characters', life: 3000 });
                    return false;
                } else if (!this.r.email.match(/\S+@\S+\.\S+/)) {
                    this.toast.add({ severity: 'error', summary: 'Error', detail: 'Invalid email', life: 3000 });
                    return false;
                } else if (!this.isPasswordMatch) {
                    this.toast.add({ severity: 'error', summary: 'Error', detail: 'Password does not match', life: 3000 });
                    return false;
                } else if (!this.pfp) {
                    this.confirm.require({
                        target: event.currentTarget,
                        message: 'Register without profile picture?',
                        icon: 'pi pi-exclamation-triangle',
                        rejectProps: {
                            label: 'Cancel',
                            severity: 'secondary',
                            outlined: true
                        },
                        acceptProps: {
                            label: 'Save'
                        },
                        accept: () => {
                            this.onSubmit(e)
                        },
                        reject: () => {
                            this.toast.add({ severity: 'error', summary: 'Rejected', detail: 'Please select your profile picture', life: 3000 });
                            return false;
                        }
                    });
                } else {
                    this.onSubmit(e)
                }
            },
            onSubmit(e) {
                const form = new FormData();
                if (this.pfp) {
                    form.append('pfp', this.pfp);
                }
                form.append('username', this.r.username);
                form.append('email', this.r.email);
                form.append('password', this.r.password);
                form.append('number', this.cleanNumber);
                this.mf_register(form).then(() => {
                    this.toast.add({ severity: 'success', summary: 'Success', detail: 'Registered successfully', life: 3000 });
                }).catch((error) => {
                    console.log(error)
                    // if (error.response) {
                    //     error.response.data.errors.forEach(err => {
                    //         this.toast.add({ severity: 'error', summary: 'Error', detail: err.message, life: 3000 });
                    //     });
                    // } else {
                    //     this.toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to register', life: 3000 });
                    // }
                });

            },
        },
        data() {
            return {
                antibot: '',
                accept: false,
                avatarError: false,
                // roles: ['administrator', 'officer'],
                test: ref(''),
                pfpView: null,
                pfp: null,
                r: {
                    // role: 'employee',
                    number: '',
                    username: '',
                    email: '',
                    password: ''
                },
                confirmPassword: ''
            }
        },
    }
</script>
