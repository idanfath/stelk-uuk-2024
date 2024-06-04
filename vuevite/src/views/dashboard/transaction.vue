<template>
  
  <div class="p-5  flex flex-row flex-wrap gap-5" v-motion-fade-visible-once>
    <Panel :toggleable="true" header="Transaction" class="w-[49%] h-fit max-xl:w-full">
      <div class="py-5">
        <div class=" flex-col flex gap-2`">
          <h1 class="text-4xl font-bold">Transaction</h1>
          <p class="text-lg mt-2 text-gray-500">Welcome to your transaction</p>
        </div>
      </div>
    </Panel>
    <Fieldset :toggleable="true" legend="Login Sebagai" class="w-[49%] h-fit max-xl:w-full">
      <div class="xl:pb-5 max-xl:pb-2 xl:pt-2 xl:pr-5">
        <div class="mt-4">
          <div class="flex items-center gap-4">
            <avatar size="xlarge" :image="mf.backend.storage + mf_user.user.pfp" @error="avatarError = true"
              :label=" avatarError ? capitalizeFirstLetter(mf_user.user.username[0]) : null"
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
    <Fieldset :toggleable="true" legend="Input Transaksi" class="  xl:h-fit">
      <form @submit.prevent class="py-5 px-4">
        <div class="flex my-4 gap-4 flex-col w-full">
          <InputGroup>
            <InputGroupAddon>
              <i class="pi pi-calendar"></i>
            </InputGroupAddon>
            <DatePicker readonly v-model="newItemForm.date" autofocus />
          </InputGroup>
          <InputGroup>
            <InputGroupAddon>
              <i class="pi pi-user"></i>
            </InputGroupAddon>
            <Select v-model="selectedCustomer" showClear display="chip" :options="customersWithID" optionLabel="name" filter
              placeholder="Select Customer" class="max-xl:w-full" />
          </InputGroup>
          <InputGroup class="mb-4">
            <InputGroupAddon>
              <i class="pi pi-cart-arrow-down"></i>
            </InputGroupAddon>
            <MultiSelect v-model="selectedItems" display="chip" :options="items" optionLabel="name" filter
              placeholder="Select Items" class="max-xl:w-full" />
          </InputGroup>
          <div v-for="item in selectedItems">

            <InputGroup>
              <div class="mr-4 flex flex-center">
                {{item.name}}
              </div>
              <InputGroupAddon class="cursor-pointer" @click="()=>{
                //remove the item from selected items
                this.selectedItems = this.selectedItems.filter(i => i !== item)
              }">
                <i class="pi pi-times"></i>
              </InputGroupAddon>
              <InputNumber v-model="item.qty" :min="1" :max="item.stok" :step="1" inputId="horizontal-buttons"
                showButtons buttonLayout="horizontal">
                <template #incrementbuttonicon>
                  <span class="pi pi-plus" />
                </template>
                <template #decrementbuttonicon>
                  <span class="pi pi-minus" />
                </template>
              </InputNumber>
            </InputGroup>
          </div>
        </div>
        <div class="mt-10 flex gap-3 ">
          <Button label="Save Changes" @click="onSave()" type="submit" size="small" />
          <Button label="Cancel" @click="resetFormOne()" size="small" severity="secondary" />
          <div class="text-xl px-4 py-3 rounded-md bg-[var(--background)]" v-if="mf_rpFormat(totalPrice > 0)">{{mf_rpFormat(totalPrice)}}</div>

        </div>
      </form>
    </Fieldset>

    <Fieldset :toggleable="true" legend="Items" class="w-full">

      <div class="py-5">
        <DataTable v-model:filters="filters" v-model:selection="selectedItemsT"
        :globalFilterFields="['id', 'total', 'transaction_date', 'pelanggan.name', 'total', 'details']" dataKey="id" :value="dataItems" removableSort
        showGridlines sortMode="multiple" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
        paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
        currentPageReportTemplate="{first} to {last} of {totalRecords}">
        <template #empty> <div class="flex flex-center w-full">No item found.</div> </template>
          <template #loading> <div class="flex flex-center w-full">Loading items data. Please wait.</div> </template>
          <template #header>
            <div class="flex justify-between">
              <h1 class="text-3xl font-bold">MANAGE</h1>
              <div class="flex items-center gap-3">
                <Button v-if="selectedItemsT.length !== 0" label="Delete Selected"
                  @click="deleteMultiple()" icon="pi pi-trash" size="small" iconPos="right" severity="danger" />
                <IconField>
                  <InputIcon>
                    <i class="pi pi-search relative bottom-1" />
                  </InputIcon>
                  <InputText v-model="filters['global'].value" placeholder="Keyword Search" />
                </IconField>
              </div>
            </div>
          </template>
        <!-- [ { "id": 3, "total": 10000, "transaction_date": "2024-06-03", "created_at": "2024-06-03T07:19:38.000000Z", "pelanggan": { "id": 1, "name": "123a", "address": "123a", "number": "+12-312-3123-123_" }, "details": [ { "quantity": 2, "subtotal": 5000, "item": { "name": "donat", "price": 5000, "desc": null, "tag": "Snack", "thumbnail": "thumbnail/a8026q8VO1A5SgrWqCx3WE9kxupJ8AoYEjHmPmyI.png" } } ] }, { "id": 4, "total": 26000, "transaction_date": "2024-06-03", "created_at": "2024-06-03T07:21:07.000000Z", "pelanggan": { "id": 1, "name": "123a", "address": "123a", "number": "+12-312-3123-123_" }, "details": [ { "quantity": 3, "subtotal": 15000, "item": { "name": "donat", "price": 5000, "desc": null, "tag": "Snack", "thumbnail": "thumbnail/a8026q8VO1A5SgrWqCx3WE9kxupJ8AoYEjHmPmyI.png" } }, { "quantity": 2, "subtotal": 11000, "item": { "name": "12312", "price": 5500, "desc": "123123", "tag": null, "thumbnail": "thumbnail/VecsTs7QWG7GsjRzyiXwlPxKUO8dVQixJzTOvdsP.png" } } ] } ]  -->
        <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>

        <Column field="total" header="Price" sortable filter />
        <Column field="transaction_date" header="Date" sortable filter />
        <Column field="pelanggan.name" header="Customer" sortable filter />
        <Column field="details" header="Items" sortable filter ">
          <template #body="slotProps">
            {{ slotProps.data.details.map(detail => `${detail.quantity} x ${detail.item.name} = ${detail.subtotal}`).join(', ') }}
          </template>
        </Column>
        <Column field="id" header="Actions" sortable filter>
          <template #body="slotProps">
            <Button icon="pi pi-trash" class="mr-2" severity="danger" @click="onDelete(slotProps.data.id)" />
          </template>
        </Column>
      </DataTable>
      </div>
    </Fieldset>
  </div>

  <Dialog header="Receipt" v-model:visible="visible" :modal="true" class="w-[500px] max-w-[90%]" :closable="false">
    <div class="p-5">
      <h2 class="text-2xl font-bold">Receipt</h2>
      <p class="text-lg mt-2">Cashier: {{ mf_user.user.username }}</p>
      <p class="text-lg mt-2">Customer: {{ selectedCustomer['name'] }}</p>
      <!-- date -->
      <p class="text-lg mt-2">Date: {{ new Date(newItemForm.date).toLocaleDateString() }}</p>
    </div>
    <div class="p-5">
      <h2 class="text-2xl font-bold">Items</h2>
      <ul>
        <li v-for="item in selectedItems">
          <p>{{ item.qty }} {{ item.name }} / {{ mf_rpFormat(item.price) }} </p>
        </li>
      </ul>
    </div>
    <div class="p-5">
      <h2 class="text-2xl font-bold">Total</h2>
      <p class="text-lg mt-2">Total Items Type: {{ selectedItems.length }}</p>
      <p class="text-lg mt-2">Total Items: {{ selectedItems.reduce((total, item) => total + item.qty, 0) }}</p>
      <p class="text-lg mt-2">Total Price: {{ totalPrice }}</p>
    </div>
    <div class="flex justify-end p-5">
      <Button label="Abort" @click="resetFormOne()" class="mr-2" severity="danger" />
      <Button label="Accept" @click="acceptReceipt()" />
    </div>
  </Dialog>

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
  import DataTable from 'primevue/datatable';
  import Column from 'primevue/column';
  import ColumnGroup from 'primevue/columngroup';   // optional
  import Row from 'primevue/row';
  import { useConfirm } from "primevue/useconfirm";
  import DatePicker from 'primevue/datepicker';
  import Select from 'primevue/select';
  import Dialog from 'primevue/dialog';
  import ConfirmDialog from 'primevue/confirmdialog';

  export default {
    name: 'profile',
    components: {

      DatePicker,
      Select,
      Dialog,
      DataTable,
      Column,
      ColumnGroup,
      Row,
      ConfirmDialog

    },
    setup() {

      const toast = useToast();
      const confirm = useConfirm();

      return { toast, confirm };
    },
    computed: {
      cleanNumber() {
        return this.editProfile.number.replace(/[\s-+_]/g, '');
      },
      // totalItems() {
      //   return this.selectedItems.reduce((acc, item) => acc + item.price, 0)
      // },
      totalPrice() {
        return this.selectedItems ? this.selectedItems.reduce((total, item) => total + item.price * item.qty, 0) : 0
      },
      // add customer id to the customer name in customersWithID
      customersWithID() {
  return this.customers.map(customer => ({
    ...customer,
    name: `${customer.name} (${customer.id})`
  }));
}

    },
    methods: {
      acceptReceipt() {
        const payload = {
          pID: this.selectedCustomer.id,
          total: this.totalPrice,
          transaction_date: new Date(),
          items: this.selectedItems.map(item => ({
            iID: item.id,
            quantity: item.qty,
            subtotal: item.price * item.qty
          }))
        };
        console.log(payload)
        axios.post('addTrx', payload)
          .then(response => {
            this.toast.add({
              severity: 'success',
              summary: 'Success',
              detail: 'Transaction success',
              life: 3000
            });
            this.selectedItems = this.selectedItems.map(item => ({ ...item, qty: 0 }))

            this.resetFormOne()
            this.fetchTrxData()
            // also reset every selected items amount
            console.log(response.data);
            
          })
          .catch(error => {
            this.toast.add({
              severity: 'error',
              summary: 'Error',
              detail: 'Transaction failed',
              life: 3000
            });
            console.error(error);
          });
      },
      resetFormOne() {
        this.visible = false;
        this.selectedItems = []
        this.selectedCustomer = {};
        this.toast.add({
          severity: 'success',
          summary: 'Success',
          detail: 'Form reset success',
          life: 3000
        });
      },
      onSave() {
        if (this.selectedItems.length === 0) {
          this.$toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Please select at least one item',
            life: 3000
          });
          return;
        } if (Object.keys(this.selectedCustomer).length === 0) {
          this.$toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Please select a customer',
            life: 3000
          });

          return;
        }
        // if any selected amount is not  more than 0
        if (this.selectedItems.some(item => item.qty <= 0)) {
          this.$toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Please select at least one item',
            life: 3000
          });
          return;
        }
        this.visible = true;
      },
      getNow() {
        const date = new Date();
        this.newItemForm.date = date;
        return date;
      },
      initFetchCust() {
        axios.get('getCustomersNameNumber').then((response) => {
          this.customers = response.data.data
        })
      },
      initFetchItems() {
        axios.get('getItemsNamePrice').then((response) => {

          this.items = response.data.data
        })
      },
      fetchTrxData(){
        axios.get('getTrxData').then((response) => {
          this.dataItems = response.data
          this.toast.add({
            severity: 'success',
            summary: 'Success',
            detail: 'successfully fetched data',
            life: 3000
          });
        })
      },
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
            let ids = this.selectedItemsT.map(item => item.id);
            axios.delete('deleteTrxs', { data: { id: ids } }).then((response) => {
              if (response.status === 200) {
                this.toast.add({ severity: 'success', summary: 'Success', detail: 'Items has been deleted', life: 3000 });
              }
              this.fetchTrxData()
            }).catch((error) => {
              this.toast.add({ severity: 'error', summary: 'Error', detail: error.response.data.message, life: 3000 });
            });
          },
          reject: () => {
            this.toast.add({ severity: 'error', summary: 'Rejected', detail: 'Operation Cancelled', life: 3000 });
          }
        });
      },
      onDelete(x){
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
            axios.delete('deleteTrx', { data: { id: x } }).then((response) => {
              if (response.status === 200) {
                this.toast.add({ severity: 'success', summary: 'Success', detail: 'Item has been deleted', life: 3000 });
              }
              this.fetchTrxData()
            }).catch((error) => {
              this.toast.add({ severity: 'error', summary: 'Error', detail: error.response.data.message, life: 3000 });
            });
          },
          reject: () => {
            this.toast.add({ severity: 'error', summary: 'Rejected', detail: 'Operation Cancelled', life: 3000 });
          }
        });
      },
      resetForm() {
      },
      onSubmit() {
      },
    },
    mounted() {
      this.fetchTrxData()
      this.initFetchItems()
      this.initFetchCust()
    },
    data() {
      return {
        filters: {
          global: { value: null, matchMode: FilterMatchMode.CONTAINS },
          id: { value: null, matchMode: FilterMatchMode.CONTAINS },
          total: { value: null, matchMode: FilterMatchMode.CONTAINS },
          transaction_date: { value: null, matchMode: FilterMatchMode.CONTAINS },
          pelanggan: { value: null, matchMode: FilterMatchMode.CONTAINS },
        },
        visible: false,
        dataItems: [],
        selectedItems: [],
        selectedItemsT: [],
        selectedCustomer: {},
        items: [],
        customers: [],
        avatarError: false,
        pfpView: null,
        newItemForm: {
          date: new Date(),
        }
      }
    },
  }
</script>

<style scoped>
</style>
