<template>
    <div>
        <form method="POST" action="/account/customer/change" ref="changeCustomer" class="m-0">

            <div v-if="user.admin">
                <div class="relative customer-switch">
                    <input type="text" class="form-control" name="customer" autocomplete="off" v-model="customerSearch" @keyup="onKeyUp(customerSearch)" required>
                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-700 hover:opacity-50">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 icon opacity-75">
                            <path class="primary"
                                  d="M12 13a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v3a1 1 0 0 1-1 1h-8a1 1 0 0 1-1-1 1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-3a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3zM7 9a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm10 0a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"></path>
                            <path class="secondary"
                                  d="M12 13a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm-3 1h6a3 3 0 0 1 3 3v3a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-3a3 3 0 0 1 3-3z"></path>
                        </svg>
                    </button>

                    <div class="absolute -mt-2 w-full z-50">
                        <ul class="bg-gray-100 rounded-b-lg w-full border-l border-r border-b" v-show="resultItems.length > 0">
                            <li class="cursor-pointer hover:bg-gray-200 p-2" v-for="item in resultItems"
                                @click="onSelected(item.code)">
                                {{ item.code + ' - ' + item.name }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div v-else>
                <div class="relative mb-2">
                    <select name="customer" class="w-full p-2 rounded border text-gray-600 appearance-none"
                            autocomplete="off">
                        <option :value="user.customer_code">
                            {{ user.customer_code }} - Default
                        </option>

                        <option v-for="customer in user.customers" :value="customer.customer_code">
                            {{ customer.customer_code }} - {{ customer.details.post_code.toUpperCase() }}
                        </option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                        </svg>
                    </div>
                </div>

                <button class="button button-primary button-block">Change Customer</button>
            </div>

        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                customerSearch: '',
                resultItems: []
            }
        },
        props: {
            user: {},
        },
        watch: {
            resultItems(resultItems) {
                if (resultItems.length > 0) {
                    document.addEventListener('click', this.closeIfClickedOutside);
                }
            }
        },
        methods: {
            onKeyUp(customerSearch) {
                this.resultItems = [];

                if (customerSearch.length > 2) {
                    axios.post('/customer/autocomplete/', {
                        customer: customerSearch
                    })
                        .then(response => {
                            var newData = [];

                            response.data.forEach(function (item) {
                                if (item.code.toLowerCase().indexOf(customerSearch.toLowerCase()) >= 0) {
                                    newData.push(item);
                                }
                            });

                            this.resultItems = newData;
                        })
                        .catch(e => {
                            this.resultItems = [];
                        })
                } else {
                    this.resultItems = [];
                }
            },
            onSelected(name) {
                this.customerSearch = name;
                this.resultItems = [];
            },
            closeIfClickedOutside(event) {
                if (!event.target.closest('.customer-switch')) {
                    this.resultItems = [];
                    document.removeEventListener('click', this.closeIfClickedOutside);
                }
            }
        },
    }
</script>
