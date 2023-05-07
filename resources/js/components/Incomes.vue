<template>
    <div class="card-header">
        <h3>Incomes</h3>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <button class="btn btn-primary mb-4" title="Add new Income" @click="toggleModal">
                <i class="bi bi-plus-lg"></i>
            </button>
        </div>

        <!-- TODO: dates need to be first, than users, than flows of date and user -->
        <div class="mb-3" v-for="user in users" :key="user.id">
            <h2>{{ user.name }}</h2>
            <ul class="nav nav-tabs mb-3" role="tablist" :id="'user-' + user.id + '-yearsTab'">
                <button class="nav-link" v-for="(yearCollection, year) in user.incomes_dated" :key="year"
                    :id="'user-' + user.id + '-' + year + '-tab'" data-bs-toggle="tab"
                    :data-bs-target="'#user-' + user.id + '-' + year + '-tab-pane'" type="button" role="tab"
                    :aria-controls="'user-' + user.id + '-' + year + '-tab-pane'" aria-selected="false">
                    {{ year }}
                </button>
            </ul>
            <div class="tab-content" :id="'user-' + user.id + '-yearsTabContent'">
                <div class="tab-pane fade" v-for="(yearCollection, year) in user.incomes_dated" :key="year"
                    :id="'user-' + user.id + '-' + year + '-tab-pane'" role="tabpanel"
                    :aria-labelledby="'user-' + user.id + '-' + year + '-tab'" tabindex="0">
                    <!-- TODO: months tabs need to be here and only than — flows table -->
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Date: </th>
                                <th>Item: </th>
                                <th>Amount: </th>
                                <th>Comment: </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="flow in user.flows" :key="flow.id">
                                <td>{{ flow.date }}</td>
                                <td>{{ flow.item }}</td>
                                <td>{{ flow.amount }} {{ flow.currency.symbol || flow.currency.code }}</td>
                                <td>{{ flow.comment }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="incomeModal" tabindex="-1" aria-labelledby="incomeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="incomeModalLabel">Add new Income</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="/flows" method="POST" class="mt-2 container" id="form-add-income">
                        <div class="row mb-3">
                            <div class="col">
                                <field-text name="item"></field-text>
                            </div>
                        </div>
                        <div class="row  mb-3 align-items-end">
                            <div class="col-8">
                                <field-price name="amount"></field-price>
                            </div>
                            <div class="col-4">
                                <field-select name="currency_id" :options="currencies"></field-select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <field-textarea name="comment"></field-textarea>
                            </div>
                        </div>
                        <field-hidden name="type" value="income"></field-hidden>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="addIncome">Add</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Modal from 'bootstrap/js/dist/modal';
import Tab from 'bootstrap/js/dist/tab';
import store from '@/store'

export default {
    data: () => ({
        users: null,
        currencies: [],
        addForm: null,
        modal: null
    }),
    mounted() {
        this.getData();

        this.addForm = document.getElementById('form-add-income');

        this.modal = new Modal('#incomeModal');
    },
    methods: {
        async getData() {
            await axios
                .get('/api/incomes')
                .then((response) => {
                    this.users = response.data.data.users;
                    console.log(this.users);

                    // for (const user of this.users) {
                    //     const triggerTabList = document.querySelectorAll('#user' + user.id + '-yearsTab button')
                    //     triggerTabList.forEach(triggerEl => {
                    //         const tabTrigger = new bootstrap.Tab(triggerEl)

                    //         triggerEl.addEventListener('click', event => {
                    //             event.preventDefault()
                    //             tabTrigger.show()
                    //         })
                    //     })
                    // }

                    for (const currency of response.data.data.currencies) {
                        this.currencies.push({
                            value: currency.id,
                            title: currency.code
                        });
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        },
        toggleModal() {
            this.modal.toggle();
        },
        addIncome(e) {
            e.preventDefault();

            const data = new FormData(this.addForm);

            axios
                .post('/api/flows', data)
                .then(response => {
                    this.modal.hide();
                    for (const user of this.users) {
                        if (user.id === store.state.auth.user.id) {
                            user.flows.push(response.data.data);
                        }
                    }
                    console.log(response.data.data);
                })
                .catch(error => {
                    console.error(error);
                });
        }
    },
};
</script>
