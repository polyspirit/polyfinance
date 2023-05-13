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

        <ul class="nav nav-tabs mb-3" role="tablist" id="yearsTab">
            <button v-for="(yearCollection, year) in dates" :key="year"
                :class="isLast(year, dates) ? 'nav-link active' : 'nav-link'" :id="'year-' + year + '-tab'"
                data-bs-toggle="tab" :data-bs-target="'#year-' + year + '-tab-pane'" type="button" role="tab"
                :aria-controls="'year-' + year + '-tab-pane'" aria-selected="false">
                {{ year }}
            </button>
        </ul>
        <div class="tab-content" id="yearsTabContent">
            <div v-for="(yearCollection, year) in  dates " :key="year"
                :class="isLast(year, dates) ? 'tab-pane fade active show' : 'tab-pane fade'"
                :id="'year-' + year + '-tab-pane'" role="tabpanel" :aria-labelledby="'year-' + year + '-tab'" tabindex="0">

                <ul class="nav nav-tabs mb-3" role="tablist" :id="'year-' + year + '-Tab'">
                    <button v-for="(monthCollection, month) in yearCollection" :key="month"
                        :class="isLast(month, yearCollection) ? 'nav-link active' : 'nav-link'"
                        :id="'month-' + year + '-' + month + '-tab'" data-bs-toggle="tab"
                        :data-bs-target="'#month-' + year + '-' + month + '-tab-pane'" type="button" role="tab"
                        :aria-controls="'month-' + year + '-' + month + '-tab-pane'" aria-selected="false">
                        {{ month }}
                    </button>
                </ul>
                <div class="tab-content" :id="'year-' + year + '-TabContent'">
                    <div v-for="(monthCollection, month) in  yearCollection" :key="month"
                        :class="isLast(month, yearCollection) ? 'tab-pane fade active show' : 'tab-pane fade'"
                        :id="'month-' + year + '-' + month + '-tab-pane'" role="tabpanel"
                        :aria-labelledby="'month-' + year + '-' + month + '-tab'" tabindex="0">

                        <div class="mt-2" v-for="(userInfo, userId) in  monthCollection.users" :key="userId">
                            <h2>{{ userInfo.name }}</h2>
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
                                    <tr v-for="flow in userInfo.flows" :key="flow.id">
                                        <td>{{ flow.date.split(' ')[0] }}</td>
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
                                <field-date name="created_at" title="Date"></field-date>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <field-select name="user_id" title="User" :options="users"></field-select>
                            </div>
                        </div>
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
                                <field-select name="currency_id" title="Currency" :options="currencies"
                                    :value="defaultCurrencyId"></field-select>
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
    props: ['key', 'rerender'],
    data: () => ({
        dates: null,
        users: [],
        currencies: [],
        defaultCurrencyId: null,
        addForm: null,
        modal: null
    }),
    mounted() {
        const _this = this;

        this.getData();

        this.addForm = document.getElementById('form-add-income');

        this.modal = new Modal('#incomeModal');
        document.getElementById('incomeModal').addEventListener('hidden.bs.modal', event => {
            _this.rerender();
        });
    },
    methods: {
        async getData() {
            await axios
                .get('/api/incomes')
                .then((response) => {
                    const data = response.data.data;
                    this.dates = data.dates;
                    this.defaultCurrencyId = data.defaultCurrencyId;

                    for (const currency of data.currencies) {
                        this.currencies.push({
                            value: currency.id,
                            title: currency.code
                        });
                    }
                    for (const dataUser of data.users) {
                        this.users.push({
                            value: dataUser.id,
                            title: dataUser.name
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
                    console.log(response.data.data);
                })
                .catch(error => {
                    console.error(error);
                });
        },
        isLast(key, obj) {
            const lastKeyIndex = Object.keys(obj).length - 1;

            return Object.keys(obj)[lastKeyIndex] === key;
        }
    },
};
</script>
