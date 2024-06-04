<template>
  <div class="p-5  flex flex-row flex-wrap gap-5" v-motion-fade-visible-once>
    <Panel :toggleable="true" header="Profile" class="w-full">
      <div class="py-5">
        <div class=" flex-col flex gap-2`">
          <h1 class="text-4xl font-bold">Profile</h1>
          <p class="text-lg mt-2 text-gray-500">Welcome to your profile</p>
          <Message severity="info">
            <div class="ml-1">
              To make the site prettier, on certain element your username will be capitalized. This message is
              persistent.
            </div>
          </Message>
        </div>
      </div>
    </Panel>
    <Fieldset :toggleable="true" :legend="capitalizeFirstLetter(mf_user.user.username)"
      class="w-fit xl:h-fit max-xl:w-full">
      <div class="xl:pb-5 max-xl:pb-2 xl:pt-2 xl:pr-5">
        <div class="mt-4">
          <div class="flex items-center gap-4">
            <avatar size="xlarge" :image="mf.backend.storage + mf_user.user.pfp" @error="avatarError = true" :label=" avatarError ? capitalizeFirstLetter(mf_user.user.username[0]) : null"
              class="image-bg w-[100px] h-[100px] rounded-full object-cover" alt="Profile Picture" shape="circle" />
            <div>
              <h2 class="text-2xl font-bold">{{mf_user.user.username}}</h2>
              <p class="text-gray-500">{{mf_user.user.email}}</p>
              <p class="text-gray-500">{{numberFormat(mf_user.user.number)}}</p>
            </div>
          </div>
        </div>
      </div>
    </Fieldset>
    <Fieldset :toggleable="true" legend="Edit Profile" class="w-fit max-xl:w-full xl:h-fit">
      <form @submit.prevent class="py-5 px-4">
        <div
          class="dark:bg-[var(--background)] bg-[var(--primary)] shadow-[inset_0_0px_5px_rgba(0,0,0,0.2)] w-full rounded-xl ">
          <div class="py-5 px-4 flex items-center  justify-between">
            <div class="flex items-center gap-3">
              <Avatar size="xlarge" class="raised" :image="pfpView"  round shape="circle" />
              <div>
                <Input type="file" ref="fileInput" class="hidden" @change="onFileChange" accept="image/*"
                  maxFileSize="5242880" />
                <Button :label="pfpView ? 'Change' :'Select'" severity="secondary" icon="pi pi-upload" size="small"
                  iconPos="left" raised @Click="()=>{this.$refs.fileInput.click();}" />
              </div>
            </div>
          </div>
        </div>
        <div class="flex my-4 gap-4 flex-col w-full">
          <InputGroup>
            <InputGroupAddon>
              <i class="pi pi-user"></i>
            </InputGroupAddon>
            <InputText :placeholder="mf_user.user.username" v-model="editProfile.username" autofocus />
          </InputGroup>
          <InputGroup>
            <InputGroupAddon>
              <i class="pi pi-phone"></i>
            </InputGroupAddon>
            <InputMask id="basic" v-model="editProfile.number" :autoClear="false" mask="+99-999-9999-9999"
              :placeholder="numberFormat(mf_user.user.number)" />
          </InputGroup>
          <InputGroup>
            <InputGroupAddon>
              <i class="pi pi-envelope"></i>
            </InputGroupAddon>
            <InputText :placeholder="mf_user.user.email" v-model="editProfile.email" />
          </InputGroup>
        </div>
        <div class="mt-6 flex gap-3">
          <Button label="Save Changes" @click="validate(e)" type="submit" size="small" />
          <!-- cancel -->
          <Button label="Cancel" @click="resetForm" size="small" severity="secondary" />
        </div>
      </form>
    </Fieldset>

    <Fieldset :toggleable="true" legend="Change Password" class="w-fit max-xl:w-full xl:h-fit">
      <form @submit.prevent class="py-5 px-4">
        <div class="flex my-4 gap-4 flex-col w-full">
          <InputGroup>
            <InputGroupAddon>
              <i class="pi pi-key"></i>
            </InputGroupAddon>
            <InputText type="password" placeholder="Current Password" v-model="editPw.current" autofocus />
          </InputGroup>
          <InputGroup>
            <InputGroupAddon>
              <i class="pi pi-key"></i>
            </InputGroupAddon>
            <InputText type="password" placeholder="New Password" v-model="editPw.new" autofocus />
          </InputGroup>
          <InputGroup>
            <InputGroupAddon>
              <i class="pi pi-key"></i>
            </InputGroupAddon>
            <InputText type="password" placeholder="Confirm Password" v-model="editPw.confirm" autofocus />
          </InputGroup>
        </div>
        <div class="mt-6 flex gap-3">
          <Button label="Save Changes" @click="validatePw(e)" type="submit" size="small" />
          <!-- cancel -->
          <Button label="Cancel" @click="resetFormPw" size="small" severity="secondary" />
        </div>
      </form>
    </Fieldset>
  </div>
</template>

<script>

  import { useToast } from 'primevue/usetoast';
  import { useConfirm } from "primevue/useconfirm";

  export default {
    name: 'profile',
    setup() {

      const toast = useToast();
      const confirm = useConfirm();

      return { toast, confirm };
    },
    computed: {
      // isPasswordMatch() {
      //   status = this.r.password === this.confirmPassword;
      //   return this.r.password.length > 2 && status;
      // },
      cleanNumber() {
        return this.editProfile.number.replace(/[\s-+_]/g, '');
      },
    },
    methods: {
      resetFormPw() {
        if (this.editPw.current !== '' || this.editPw.new !== '' || this.editPw.confirm !== '') {
          this.editPw = {
            current: '',
            new: '',
            confirm: ''
          }
          this.toast.add({
            severity: 'info',
            summary: 'Reset',
            detail: 'Form has been reset',
            life: 3000
          });
        }
      },
      validatePw(e) {
        // check if current password is correct, new password is valid, confirm password is valid
        if (this.editPw.current === '' || this.editPw.new === '' || this.editPw.confirm === '') {
          this.toast.add({ severity: 'warn', summary: 'Warning', detail: 'Not enough input provided', life: 3000 });
          return false;
        }
        if (this.editPw.new.length < 8 || this.editPw.confirm.length < 8 || this.editPw.current.length < 8) {
          this.toast.add({ severity: 'error', summary: 'Error', detail: 'Password must be at least 8 characters', life: 3000 });
          return false;
        }
        if (this.editPw.new !== this.editPw.confirm) {
          this.toast.add({ severity: 'error', summary: 'Error', detail: 'New password and confirm password do not match', life: 3000 });
          return false;
        }
        this.confirm.require({
          target: event.currentTarget,
          message: 'Change your password?',
          icon: 'pi pi-exclamation-triangle',
          rejectProps: {
            label: 'Cancel',
            severity: 'secondary',
            outlined: true
          },
          acceptProps: {
            label: 'Change'
          },
          accept: () => {
            this.onSubmitPw()
          },
          reject: () => {
            this.toast.add({ severity: 'info', summary: 'Rejected', detail: 'Your password is not changed', life: 3000 });
            this.resetFormPw();
            return false;
          }
        });
      },
      onSubmitPw() {
        // make it compatible for PUT laravel
        const form = {
          currentpassword: this.editPw.current,
          newpassword: this.editPw.new,
          confirmpassword: this.editPw.confirm
        }
        axios.put('updatePassword', form).then((response) => {
          console.log(response)
          if (response.status === 200) {
            this.toast.add({ severity: 'success', summary: 'Success', detail: 'Password has been updated', life: 3000 });
            this.resetFormPw();
            this.mf_attempt();
          }
        }).catch((error) => {
          if (error.response) {
            console.log(error.response.data);
            this.toast.add({ severity: 'error', summary: 'Error', detail: error.response.data.message, life: 3000 });
          } else {
            console.log(error.message);
            this.toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to register', life: 3000 });
          }
        });
      },
      onFileChange(e) {
        this.editProfile.pfp = e.target.files[0];
        this.pfpView = URL.createObjectURL(this.editProfile.pfp);
      },
      resetForm() {
        if (this.editProfile.email !== '' || this.editProfile.number !== '' || this.editProfile.username !== '' || this.editProfile.pfp !== '' || this.pfpView !== null) {
          this.editProfile = {
            email: '',
            number: '',
            username: '',
            pfp: ''
          }
          this.pfpView = null;
          this.toast.add({
            severity: 'info',
            summary: 'Reset',
            detail: 'Form has been reset',
            life: 3000
          });
        }
      },
      validate(e) {
        // check if email is valid, number is valid using cleanNumber, username is valid
        if (this.editProfile.email === '' && this.editProfile.number === '' && this.editProfile.username === '' && this.editProfile.pfp === '') {
          this.toast.add({ severity: 'warn', summary: 'Warning', detail: 'No input provided', life: 3000 });
          return false;
        }
        if (this.editProfile.email !== '' && !this.editProfile.email.match(/\S+@\S+\.\S+/)) {
          this.toast.add({ severity: 'error', summary: 'Error', detail: 'Invalid email', life: 3000 });
          return false;
        }
        this.confirm.require({
          target: event.currentTarget,
          message: 'Change your profile?',
          icon: 'pi pi-exclamation-triangle',
          rejectProps: {
            label: 'Cancel',
            severity: 'secondary',
            outlined: true
          },
          acceptProps: {
            label: 'Change'
          },
          accept: () => {
            this.onSubmit()
          },
          reject: () => {
            this.toast.add({ severity: 'info', summary: 'Rejected', detail: 'Your profile is not changed', life: 3000 });
            this.resetForm();
            return false;
          }
        });
      },
      onSubmit() {
        const form = new FormData();
        form.append('email', this.editProfile.email);
        form.append('number', this.cleanNumber);
        form.append('username', this.editProfile.username);
        form.append('pfp', this.editProfile.pfp);
        const config = {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }
        axios.post('update', form).then((response) => {
          console.log(response)
          if (response.status === 200) {
            this.toast.add({ severity: 'success', summary: 'Success', detail: 'Profile has been updated', life: 3000 });
            this.resetForm();
            this.mf_attempt();
            document.title = 'Profile | ' + mf_user.user.username.charAt(0).toUpperCase() + mf_user.user.username.slice(1);
          }
        }).catch((error) => {
          if (error.response) {
            error.response.data.errors.forEach(err => {
              this.toast.add({ severity: 'error', summary: 'Error', detail: err.message, life: 3000 });
            });
          } else {
            console.log(error.message);
            console.log("if it's about the mf_user, ignore this error")
          }
        });
      },

    },
    
    data() {
      return {
        avatarError: false,
        avatarError2: false,
        pfpView: null,
        editPw: {
          current: '',
          new: '',
          confirm: ''
        },
        editProfile: {
          email: '',
          number: '',
          username: '',
          pfp: ''
        }
      }
    },
  }
</script>

<style scoped>
</style>
