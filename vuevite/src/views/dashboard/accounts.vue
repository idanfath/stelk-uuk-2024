<template>
  <Dialog v-model:visible="visible" v-if="modal.context" modal :header="modal.header" class="w-[90%] max-w-[600px]">

    <template #header>
      <div class="inline-flex items-center justify-center gap-2">
        <Avatar :image="mf.backend.storage + mf_user.user.pfp" @error="avatarError = true" :label=" avatarError ? capitalizeFirstLetter(mf_user.user.username[0]) : null" shape="circle" />
        <span class="font-bold whitespace-nowrap">{{capitalizeFirstLetter(mf_user.user.username)}}</span>
      </div>
    </template>
    <div>
      <span
        class="text-surface-500 dark:text-surface-400 block text-2xl mt-2 font-semibold">{{modalProps[modal.context]['header']}}
      </span>

      <div v-if="imgview && modal.context === 'add'"
        class="dark:bg-[var(--background)] bg-[var(--primary)] mt-3 w-full rounded-xl ">
        <div class="p-3 flex items-center  justify-between">
          <div class="w-full">
            <img class="rounded-lg object-cover max-h-[150px] w-full" :src="imgview" />
          </div>
        </div>
      </div>

      <div class="flex flex-col gap-3 mt-3" v-if="modal.context === 'add'">
        <InputGroup>
          <InputGroupAddon>
            <i class="pi pi-pen-to-square"></i>
          </InputGroupAddon>
          <InputText type="text" v-model="newItemForm.name" placeholder="Customer name" autofocus />
        </InputGroup>
        <InputGroup>
          <InputGroupAddon>
            <i class="pi pi-book"></i>
          </InputGroupAddon>
          <Textarea placeholder="Customer address" v-model="newItemForm.address" rows="2" cols="auto" class="w-full" />
        </InputGroup>
        <InputGroup>
          <InputGroupAddon>
              <i class="pi pi-phone"></i>
          </InputGroupAddon>
          <InputMask id="basic" v-model="newItemForm.number" :autoClear="false" mask="+99-999-9999-9999"
              placeholder="Phone" />
      </InputGroup>
      </div>
      <!-- view -->

      <div class="flex flex-col gap-3 mt-3" v-if="modal.context === 'view'">
        <InputGroup>
          <InputGroupAddon>
            <i class="pi pi-pen-to-square"></i>
          </InputGroupAddon>
          <InputText type="text" v-model="dataItems.find(item => item.id === focusModal).username" placeholder="Account name" readonly />
        </InputGroup>
        <InputGroup>
          <InputGroupAddon>
            <i class="pi pi-envelope"></i>
          </InputGroupAddon>
          <InputText placeholder="Account email" v-model="dataItems.find(item => item.id === focusModal).email" rows="2" cols="auto" class="w-full" readonly />
        </InputGroup>
        <InputGroup>
          <InputGroupAddon>
            <i class="pi pi-phone"></i>
          </InputGroupAddon>
          <InputMask id="basic" v-model="dataItems.find(item => item.id === focusModal).number" :autoClear="false" mask="+99-999-9999-9999"
            placeholder="Phone" readonly />
        </InputGroup>
      </div>
      <!-- edit -->
      <div class="flex flex-col gap-3 mt-3" v-if="modal.context === 'edit'">
        <InputGroup>
          <InputGroupAddon>
            <i class="pi pi-pen-to-square"></i>
          </InputGroupAddon>
          <InputText type="text" v-model="dataItems.find(item => item.id === focusModal).username" placeholder="Account name" />
        </InputGroup>
        <InputGroup>
          <InputGroupAddon>
            <i class="pi pi-envelope"></i>
          </InputGroupAddon>
          <InputText placeholder="Account email" v-model="dataItems.find(item => item.id === focusModal).email" rows="2" cols="auto" class="w-full" />
        </InputGroup>
        <InputGroup>
          <InputGroupAddon>
            <i class="pi pi-phone"></i>
          </InputGroupAddon>
          <InputMask id="basic" v-model="dataItems.find(item => item.id === focusModal).number" :autoClear="false" mask="+99-999-9999-9999"
            placeholder="Phone" />
        </InputGroup>
      </div>

    </div>
    <template #footer>
      <Button label="Cancel" text severity="secondary" @click="onCancel()" />
      <Button label="Done" severity="primary" @click="onSave()" />
    </template>
  </Dialog>
  <div class="p-5 flex flex-row flex-wrap gap-5" v-motion-fade-visible-once>
    <Panel :toggleable="true" header="Accounts" class="w-full">
      <div class="py-5">
        <div class=" flex-col flex gap-2`">
          <h1 class="text-4xl font-bold">Accounts</h1>
          <p class="mt-2 text-lg text-gray-500">Here you can add, edit, delete, read your Accounts. Your server currently have
            <span v-if="dataItems" class="dark:text-[var(--primary-dark)] text-[var(--primary)]">{{dataItems.length}}
              Accounts <span v-if="dataItems.length === 1">(You)</span></span>.
          </p>
        </div>
      </div>
    </Panel>
    <Fieldset :toggleable="true" legend="Items" class="w-full">
      <div class="py-5">
        <DataTable v-model:filters="filters" v-model:selection="selectedItems"
          :globalFilterFields="['name', 'tag', 'desc', 'price']" dataKey="id" :value="dataItems" removableSort
          showGridlines sortMode="multiple" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
          paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
          currentPageReportTemplate="{first} to {last} of {totalRecords}">
          <template #empty> <div class="flex flex-center w-full">No item found.</div> </template>
          <template #loading> <div class="flex flex-center w-full">Loading items data. Please wait.</div> </template>
          <template #header>
            <div class="flex justify-between">
              <h1 class="text-3xl font-bold">MANAGE</h1>
              <div class="flex items-center gap-3">
                <Button v-if="selectedItems && selectedIds.length !== 0" label="Delete Selected"
                  @click="deleteMultiple()" icon="pi pi-trash" size="small" iconPos="right" severity="danger" />
                <Button label="Add" @click="openModal('add')" icon="pi pi-plus" size="small" iconPos="right" />
                <IconField>
                  <InputIcon>
                    <i class="pi pi-search relative bottom-1" />
                  </InputIcon>
                  <InputText v-model="filters['global'].value" placeholder="Keyword Search" />
                </IconField>
              </div>
            </div>
          </template>
          <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
          <Column field="pfp" header="Avatar">
            <template #body="slotProps">
              <Image v-if="slotProps.data.pfp" preview class="rounded-lg object-cover max-h-[150px] max-w-[200px]"
                :src="mf.backend.storage + slotProps.data.pfp" />
            </template>
          </Column>
          <Column field="username" sortable header="Name"></Column>
          <Column field="email" sortable header="Email"></Column>
          <Column field="number" sortable header="Phone">
            <template #body="slotProps">
              <span>{{'+' + numberFormat(slotProps.data.number)}}</span>
            </template>
          </Column>
          <Column field="id" header="ID"></Column>
          <Column field="id" header="Actions">
            <template #body="slotProps">
              <Button size="small" icon="pi pi-eye" class="mr-2" @click="openModal('view', slotProps.data.id)"></Button>
              <Button size="small" icon="pi pi-pencil" class="mr-2" @click="openModal('edit', slotProps.data.id)"
                severity="warn"></Button>
              <Button size="small" icon="pi pi-trash" @click="tryDelete(event, slotProps.data.id)"
                severity="danger"></Button>
            </template>
          </Column>
        </DataTable>
      </div>
    </Fieldset>
  </div>
  <ConfirmDialog group="templating">
    <template #message="slotProps">
      <div class="flex flex-col items-center w-full gap-4  border-surface-200 dark:border-surface-700">
        <p>{{ slotProps.message.message }}</p>
      </div>
    </template>
  </ConfirmDialog>
</template>

<script>

import { FilterMatchMode } from 'primevue/api';
  import { useToast } from 'primevue/usetoast';
  import { useConfirm } from "primevue/useconfirm";
  import ConfirmDialog from 'primevue/confirmdialog';
  import DatePicker from 'primevue/datepicker';
  import Textarea from 'primevue/textarea';
  import Select from 'primevue/select';
  import SelectButton from 'primevue/selectbutton';
  import DataTable from 'primevue/datatable';
  import Column from 'primevue/column';
  import ColumnGroup from 'primevue/columngroup';   // optional
  import Row from 'primevue/row';
  export default {
    name: 'profile',
    components: {
      'DatePicker': DatePicker,
      'Textarea': Textarea,
      'Select': Select,
      'SelectButton': SelectButton,
      'DataTable': DataTable,
      'Column': Column,
      'ColumnGroup': ColumnGroup,
      'Row': Row,
      'ConfirmDialog': ConfirmDialog
    },
    setup() {
      const toast = useToast();
      const confirm = useConfirm();
      return { toast, confirm };
    },
    computed: {
      selectedIds() {
        return this.selectedItems.map(item => item.id);
      }
    },
    watch: {
      // if visible is false, reset the form
      visible(val) {
        if (!val) {
          this.imgview = null,
            this.newItemForm = {
              username: '',
              pfp: '',
              number: '',
              email: '',
            }
          this.focusModal = '';
          this.modal.context = '';
          this.fetchMyData();
        }
      }
    },
    methods: {
      deleteMultiple() {
        this.confirm.require({
          group: 'templating',
          header: 'Are you sure?',
          message: 'This action cannot be undone',
          icon: 'pi pi-trash',
          rejectProps: {
            autofocus: true,
            label: 'Cancel',
            outlined: true,
            size: 'small'
          },
          acceptProps: {
            autofocus: false,
            label: 'Delete',
            severity: 'danger',
            size: 'small'
          },
          accept: () => {
            axios.delete('deleteUsers', { data: { id: this.selectedIds } }).then((response) => {
              if (response.status === 200) {
                this.toast.add({ severity: 'success', summary: 'Success', detail: 'Items has been deleted', life: 3000 });
              }
            }).catch((error) => {
              this.toast.add({ severity: 'error', summary: 'Error', detail: error.response.data.message, life: 3000 });
            });
          },
          reject: () => {
            this.toast.add({ severity: 'error', summary: 'Rejected', detail: 'Operation Cancelled', life: 3000 });
          }
        });
      },
      openModal(context, arg) {
        this.modal.context = context;
        if (arg) {
          this.focusModal = arg;
        }
        this.visible = true;
      },
      onCancel() {
        this.visible = false;
      },
      tryDelete(e, id) {
        this.confirm.require({
          group: 'templating',
          header: 'Are you sure?',
          message: 'This action cannot be undone',
          icon: 'pi pi-trash',
          rejectProps: {
            autofocus: true,
            label: 'Cancel',
            outlined: true,
            size: 'small'
          },
          acceptProps: {
            autofocus: false,
            label: 'Delete',
            severity: 'danger',
            size: 'small'
          },
          accept: () => {
            this.commitDelete(id)
          },
          reject: () => {
            this.toast.add({ severity: 'error', summary: 'Rejected', detail: 'Operation Cancelled', life: 3000 });
          }
        });
      },
      commitDelete(id) {
        axios.delete('deleteUser', { data: { id: id } }).then((response) => {
          if (response.status === 200) {
            this.toast.add({ severity: 'success', summary: 'Success', detail: 'Item has been deleted', life: 3000 });
            this.fetchMyData();
          }
        }).catch((error) => {
          this.toast.add({ severity: 'error', summary: 'Error', detail: error.response.data.message, life: 3000 });
        });
      },
      onFileChange2(e) {
        this.imgEdit = e.target.files[0];
        this.imgview2 = URL.createObjectURL(this.imgEdit);
      },
      onFileChange(e) {
        this.newItemForm.thumbnail = e.target.files[0];
        this.imgview = URL.createObjectURL(this.newItemForm.thumbnail);
      },
      onSave() {
        if (this.modal.context === 'add') {
        } else if (this.modal.context === 'view') {
          this.visible = false;
        } else if (this.modal.context === 'edit') {
          axios.post('updateUser', this.dataItems.find(item => item.id === this.focusModal)).then((response) => {
            if (response.status === 200) {
              this.toast.add({ severity: 'success', summary: 'Success', detail: 'Item has been updated', life: 3000 });
              this.visible = false;
              this.fetchMyData();
            }
            if (this.focusModal === mf_user.user.id) {
              this.mf_attempt();
            }
          }).catch((error) => {
            this.toast.add({ severity: 'error', summary: 'Error', detail: error.response.data.message, life: 3000 });
          });
        }
      },
      fetchMyData() {
        // request axios 'getItems'
        axios.get('fetchAllUsers').then((response) => {
          this.dataItems = response.data.data;
          console.log(this.dataItems)
        }).catch((error) => {
          this.toast.add({ severity: 'error', summary: 'Error', detail: error.response.data.message, life: 3000 });
        });
      },
    },
    created() {
      this.minDate = new Date();
      this.minDate.setDate(this.minDate.getDate() + 1); // Tomorrow's date
      this.maxDate = new Date();
      this.maxDate.setFullYear(this.maxDate.getFullYear() + 2);
    },
    mounted() {
      this.fetchMyData();
    },
    data() {
      return {
        selectedItems: null,
        focusModal: '',
        filters: {
          global: { value: null, matchMode: FilterMatchMode.CONTAINS },
          username: { value: null, matchMode: FilterMatchMode.CONTAINS },
          email: { value: null, matchMode: FilterMatchMode.CONTAINS },
          number: { value: null, matchMode: FilterMatchMode.CONTAINS },
        },
        dataItems: null,
        visible: false,
        modal: {
          context: ''
        },
        modalProps: {
          'add': {
            header: 'Add new accounts',
          },
          'edit': {
            header: 'Edit accounts',
          },
          'delete': {
            header: 'Delete accounts',
          },
          'view': {
            header: 'View accounts',
          }
        },
        
        newItemForm: {
          pfp: '',
          username: '',
          number: '',
          password: '',
          email: '',
          role: '',
        },
        editForm: {
          pfp: '',
          username: '',
          number: '',
          password: '',
          email: '',
          role: '',
        },
        avatarError: false,
      }
    },
  }
</script>

<style scoped>

</style>
