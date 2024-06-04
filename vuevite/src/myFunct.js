
//this is a vue plugin custom made by me
// name: "myFunct"
// version: "1.0.0"
export default {
    install: (app) => {
        const darkClass = 'dark';
        const isDarkMode = useStorage(darkClass, false);
        if (isDarkMode.value) {
            document.documentElement.classList.add(darkClass);
        } else {
            document.documentElement.classList.remove(darkClass);
        }
        app.mixin({
            setup() {
            },
            data() {
                const main = "http://127.0.0.1:8000";
                return {
                    mf: {
                        app: {
                            title: "Kasir App",
                            desc: "This is a description of Kasir App",
                            version: "1.0.0",
                            urls: {
                            }
                        },
                        backend: {
                            main: main,
                            api: main + "/api/",
                            storage: main + "/storage/",
                        },
                    },
                    datas: {
                        sidebar: {
                            items: [
                                { icon: 'pi pi-table', label: 'Dashboard', to: 'dashboard' },
                                { icon: 'pi pi-user', label: 'Profile', to: 'profile' },
                                { icon: 'pi pi-shopping-cart', label: 'Items', to: 'dbitems' },
                                // transaction
                                { icon: 'pi pi-money-bill', label: 'Transactions', to: 'transactions'},
                                // customer
                                { icon: 'pi pi-users', label: 'Customers', to: 'customers'},
                                
                            ]
                        }
                    }
                }
            },
            methods: {

                capitalizeFirstLetter(string) {
                    return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
                },
                toNumberFormat(n) {
                    const number = n;
                    const formattedNumber = number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    return formattedNumber;
                },
                noUnderScore(string) {
                    return string.replace(/_/g, ' ').replace(/ - /g, ' ').replace(/- /g, ' ');
                },
                numberFormat(n) {
                    const number = n;
                    const formattedNumber = number.slice(0, 2) + '-' + number.slice(2, 5) + '-' + number.slice(5, 9) + '-' + number.slice(9);
                    return formattedNumber;
                },
                mf_enFormatDate(date) {
                    
                    const x = new Date(date).toLocaleDateString('en-US');
                    if (x === "Invalid Date") {
                        return;
                    }
                    return x

                },
                mf_now() {
                    return new Date()
                },
                // format rupiah
                mf_rpFormat(n) {
                    return "Rp " + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                },
                mf_test() {
                    console.log("myFunct plugin is working")
                },
                mf_dark() {
                    isDarkMode.value = !isDarkMode.value;
                    document.documentElement.classList.toggle(darkClass, isDarkMode.value);
                    document.body.classList.toggle(darkClass, isDarkMode.value);
                },
                mf_isDark() {
                    return isDarkMode.value;
                },
                ...mapActions({
                    mf_register: 'auth/register',
                    mf_login: 'auth/login',
                    mf_attempt: 'auth/attempt',
                    mf_logout: 'auth/logout',
                    mf_update: 'auth/update',
                }),
                mf_onSignOut() {
                    this.mf_logout();
                    this.$router.push({ name: 'login' });
                },
            },
            computed: {
                ...mapGetters({
                    mf_auth: 'auth/authenticated',
                    mf_user: 'auth/user',
                }),
                mf_isRole(y) {
                    return this.mf_user.role === y;
                },
            },
        });
    }
}
