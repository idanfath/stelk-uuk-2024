<template>
  <Dialog v-model:visible="visible" v-if="modal.context" modal :header="modal.header" class="w-[90%] max-w-[600px]">
    <template #header>
      <div class="inline-flex items-center justify-center gap-2">
        <Avatar :image="mf.backend.storage + mf_user.user.pfp" shape="circle" />
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
        <div class="w-full">
          <Input type="file" ref="fileInput" class="hidden" @change="onFileChange" accept="image/*"
            maxFileSize="5242880" />
          <Button :label="newItemForm.thumbnail ? 'Change Thumbnail' :'Select Thumbnail'" severity="secondary"
            class="h-full w-full" icon="pi pi-upload" size="small" iconPos="left" raised
            @Click="()=>{this.$refs.fileInput.click();}" />
        </div>
        <InputGroup>
          <InputGroupAddon>
            <i class="pi pi-pen-to-square"></i>
          </InputGroupAddon>
          <InputText type="text" v-model="newItemForm.name" placeholder="Item name" autofocus />
        </InputGroup>
        <InputGroup>
          <InputGroupAddon>
            <i class="pi pi-book"></i>
          </InputGroupAddon>
          <Textarea placeholder="Item description" v-model="newItemForm.desc" rows="2" cols="auto" class="w-full" />
        </InputGroup>
        <InputNumber v-model="newItemForm.stok" min="1" inputId="horizontal-buttons" showButtons
          buttonLayout="horizontal" :step="1">
          <template #incrementbuttonicon>
            <span class="pi pi-plus" />
          </template>
          <template #decrementbuttonicon>
            <span class="pi pi-minus" />
          </template>
        </InputNumber>
        <InputNumber v-model="newItemForm.price" inputId="horizontal-buttons" showButtons buttonLayout="horizontal"
          :step="500" mode="currency" currency="IDR">
          <template #incrementbuttonicon>
            <span class="pi pi-plus" />
          </template>
          <template #decrementbuttonicon>
            <span class="pi pi-minus" />
          </template>
        </InputNumber>
        <div class="flex w-full justify-center dark:bg-[var(--background)] bg-[var(--primary)] py-2 rounded-lg">
          <SelectButton v-model="newItemForm.tag" :options="options" optionLabel="name" aria-labelledby="multiple" />
        </div>
      </div>

      <!-- make for view based on add -->

      <div class="flex flex-col gap-3 mt-3" v-if="modal.context === 'view'">
        <InputGroup>
          <InputGroupAddon>
            <i class="pi pi-pen-to-square"></i>
          </InputGroupAddon>
          <InputText type="text" v-model="dataItems.find(item => item.id === focusModal).name" placeholder="Item name"
            readonly />
        </InputGroup>
        <InputGroup>
          <InputGroupAddon>
            <i class="pi pi-book"></i>
          </InputGroupAddon>
          <Textarea placeholder="Item description" v-model="dataItems.find(item => item.id === focusModal).desc"
            rows="2" cols="auto" class="w-full" readonly />
        </InputGroup>
        <InputNumber v-model="dataItems.find(item => item.id === focusModal).stok" min="1" inputId="horizontal-buttons"
          showButtons buttonLayout="horizontal" :step="1" readonly>
          <template #incrementbuttonicon>
            <span class="pi pi-plus" />
          </template>
          <template #decrementbuttonicon>
            <span class="pi pi-minus" />
          </template>
        </InputNumber>
        <InputNumber v-model="dataItems.find(item => item.id === focusModal).price" inputId="horizontal-buttons"
          showButtons buttonLayout="horizontal" :step="500" mode="currency" currency="IDR" readonly>
        </InputNumber>
      </div>

      <div v-if="imgview2 && modal.context === 'edit'"
        class="dark:bg-[var(--background)] bg-[var(--primary)] mt-3 w-full rounded-xl ">
        <div class="p-3 flex items-center  justify-between">
          <div class="w-full">
            <img class="rounded-lg object-cover max-h-[150px] w-full" :src="imgview2" />
          </div>
        </div>
      </div>
      <div class="flex flex-col gap-3 mt-3" v-if="modal.context === 'edit'">
        <div class="w-full">
          <Input type="file" ref="fileInput2" class="hidden" @change="onFileChange2" accept="image/*"
            maxFileSize="5242880" />
          <Button :label="newItemForm.thumbnail ? 'Change Thumbnail' :'Select Thumbnail'" severity="secondary"
            class="h-full w-full" icon="pi pi-upload" size="small" iconPos="left" raised
            @Click="()=>{this.$refs.fileInput2.click();}" />
        </div>
        <InputGroup>
          <InputGroupAddon>
            <i class="pi pi-pen-to-square"></i>
          </InputGroupAddon>
          <InputText type="text" v-model="dataItems.find(item => item.id === focusModal).name" placeholder="Item name"
            autofocus />
        </InputGroup>
        <InputGroup>
          <InputGroupAddon>
            <i class="pi pi-book"></i>
          </InputGroupAddon>
          <Textarea placeholder="Item description" v-model="dataItems.find(item => item.id === focusModal).desc"
            rows="2" cols="auto" class="w-full" />
        </InputGroup>
        <InputNumber v-model="dataItems.find(item => item.id === focusModal).stok" min="0" inputId="horizontal-buttons"
          showButtons buttonLayout="horizontal" :step="1">
          <template #incrementbuttonicon>
            <span class="pi pi-plus" />
          </template>
          <template #decrementbuttonicon>
            <span class="pi pi-minus" />
          </template>
        </InputNumber>
        <InputNumber v-model="dataItems.find(item => item.id === focusModal).price" inputId="horizontal-buttons"
          showButtons buttonLayout="horizontal" :step="500" mode="currency" currency="IDR">
          <template #incrementbuttonicon>
            <span class="pi pi-plus
            " />
          </template>
          <template #decrementbuttonicon>
            <span class="pi pi-minus" />
          </template>
        </InputNumber>
        <div class="flex w-full justify-center dark:bg-[var(--background)] bg-[var(--primary)] py-2 rounded-lg">
          <SelectButton v-model="dataItems.find(item => item.id === focusModal).tag" :options="options"
            optionLabel="name" aria-labelledby="multiple" />
        </div>
      </div>

    </div>
    <template #footer>
      <Button label="Cancel" text severity="secondary" @click="onCancel()" />
      <Button label="Done" severity="primary" @click="onSave()" />
    </template>
  </Dialog>

  <div class="p-5 flex flex-row flex-wrap gap-5" v-motion-fade-visible-once>
    <Panel :toggleable="true" header="Items" class="w-full">
      <div class="py-5">
        <div class=" flex-col flex gap-2`">
          <h1 class="text-4xl font-bold">Items</h1>
          <p class="mt-2 text-lg text-gray-500">Here you can add, edit, delete, read your items. You currently have
            <span v-if="dataItems" class="dark:text-[var(--primary-dark)] text-[var(--primary)]">{{dataItems.length}}
              items</span>.
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
          <template #empty>
            <div class="flex flex-center w-full">No item found.</div>
          </template>
          <template #loading>
            <div class="flex flex-center w-full">Loading items data. Please wait.</div>
          </template>
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
          <Column field="thumbnail" header="Thumbnail">
            <template #body="slotProps">
              <Image v-if="slotProps.data.thumbnail" preview class="rounded-lg object-cover max-h-[150px] max-w-[200px]"
                :src="mf.backend.storage + slotProps.data.thumbnail" />
            </template>
          </Column>
          <Column field="name" sortable header="Name"></Column>

          <Column field="desc" sortable header="Description"></Column>
          <Column field="tag" sortable header="Tag">
            <template #body="slotProps">
              <div v-if="typeof slotProps.data.tag !== 'object'">
                <Badge v-if="slotProps.data.tag" :value="slotProps.data.tag" :severity="slotProps.data.tag === 'Food' ? 'success' : slotProps.data.tag === 'Drink' ? 'info' : slotProps.data.tag === 'Snack' ? 'warning' : slotProps.data.tag === 'Dessert' ? 'danger' : 'primary'" />
              </div>
              <div v-else>
                <Badge v-if="slotProps.data.tag" :value="slotProps.data.tag.name" :severity="slotProps.data.tag.name === 'Food' ? 'success' : slotProps.data.tag.name === 'Drink' ? 'info' : slotProps.data.tag.name === 'Snack' ? 'warning' : slotProps.data.tag.name === 'Dessert' ? 'danger' : 'primary'" />
              </div>
            </template>
          </Column>
          <Column field="stok" sortable header="Stok">
            <template #body="slotProps">
              <Badge :value="slotProps.data.stok" :severity="slotProps.data.stok < 2 ? 'danger' : 'success'" />
            </template>
          </Column>
          <Column field="price" sortable header="Price"></Column>
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
              name: '',
              date: '',
              thumbnail: '',
              desc: '',
              tag: null,
              price: 0.0,
              stok: 1
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
            axios.delete('deleteItems', { data: { id: this.selectedIds } }).then((response) => {
              if (response.status === 200) {
                this.toast.add({ severity: 'success', summary: 'Success', detail: 'Items has been deleted', life: 3000 });
                this.fetchMyData();
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
        if (this.modal.context === 'edit') {
          this.fetchMyData();
          this.visible = false
        }
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
        axios.delete('deleteItem', { data: { id: id } }).then((response) => {
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
          const form = new FormData();
          form.append('name', this.newItemForm.name);
          form.append('thumbnail', this.newItemForm.thumbnail);
          form.append('desc', this.newItemForm.desc);
          form.append('price', this.newItemForm.price);
          form.append('stok', this.newItemForm.stok);
          if (this.newItemForm.tag) {
            form.append('tag', this.newItemForm.tag['name']);
          }
          const config = {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          }
          console.log(form)
          axios.post('addItem', form, config).then((response) => {
            console.log(response)
            if (response.status === 200) {
              this.toast.add({ severity: 'success', summary: 'Success', detail: 'Item has been added', life: 3000 });
              this.visible = false;
              this.fetchMyData();
            }
          }).catch((error) => {
            this.toast.add({ severity: 'error', summary: 'Error', detail: error.response.data.message, life: 3000 });
          });
        } else if (this.modal.context === 'view') {
          this.fetchMyData()
          this.visible = false;
        } else if (this.modal.context === 'edit') {
          const form = new FormData();
          form.append('id', this.focusModal);
          form.append('name', this.dataItems.find(item => item.id === this.focusModal).name);
          if (this.imgEdit) {
            form.append('thumbnail', this.imgEdit);
          }
          if (this.dataItems.find(item => item.id === this.focusModal).desc) {
            form.append('desc', this.dataItems.find(item => item.id === this.focusModal).desc);
          }
          // tag json
          if (this.dataItems.find(item => item.id === this.focusModal).tag.name) {
            form.append('tag', this.dataItems.find(item => item.id === this.focusModal).tag.name);
          }
          form.append('price', this.dataItems.find(item => item.id === this.focusModal).price);
          form.append('stok', this.dataItems.find(item => item.id === this.focusModal).stok);
          // thumbnail
          const config = {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          }
          console.log(form)
          axios.post('editItem', form, config).then((response) => {
            console.log(response)
            if (response.status === 200) {
              this.toast.add({ severity: 'success', summary: 'Success', detail: 'Item has been updated', life: 3000 });
              this.visible = false;
              this.fetchMyData();
            }
          }).catch((error) => {
            this.toast.add({ severity: 'error', summary: 'Error', detail: error.response.data.message, life: 3000 });
            this.fetchMyData()
            this.visible = false;
          });
        }
      },
      fetchMyData() {
        // request axios 'getItems'
        axios.get('fetchAllItems').then((response) => {
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
        imgEdit: null,
        filters: {
          global: { value: null, matchMode: FilterMatchMode.CONTAINS },
          name: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
          desc: { value: null, matchMode: FilterMatchMode.CONTAINS },
          stok: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
          price: { value: null, matchMode: FilterMatchMode.CONTAINS },
          description: { value: null, matchMode: FilterMatchMode.CONTAINS },
          tag: { value: null, matchMode: FilterMatchMode.IN },
        },
        dataItems: null,
        // date: null,
        // minDate: null,
        // maxDate: null,
        visible: false,
        options: [
          { name: 'Food' },
          { name: 'Drink' },
          { name: 'Snack' },
          { name: 'Dessert' },
          { name: 'Others' }
        ],
        modal: {
          context: ''
        },
        modalProps: {
          'add': {
            header: 'Add new item',
          },
          'edit': {
            header: 'Edit item',
          },
          'delete': {
            header: 'Delete item',
          },
          'view': {
            header: 'View item',
          }
        },
        imgview: null,
        imgview2: null,
        newItemForm: {
          name: '',
          thumbnail: '',
          price: 0.0,
          stok: 1,
          desc: '',
          tag: '',
        }
      }
    },
  }
</script>

<style scoped>

</style>
