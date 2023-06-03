<template>
    <div class="card-header">
        <h3>Incomes</h3>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <button class="btn btn-primary mb-4 js-add-flow" id="" @click="toggleModal">
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
                        {{ monthCollection.month }}
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
                                        <th>Date</th>
                                        <th>Item</th>
                                        <th>Amount</th>
                                        <th>Comment</th>
                                        <th style="width: 110px; text-align: right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="flow in userInfo.flows" :key="flow.id" :id="'flow-' + flow.id">
                                        <td>{{ flow.date.split(' ')[0] }}</td>
                                        <td>{{ flow.item }}</td>
                                        <td>{{ flow.amount }} {{ flow.currency.symbol || flow.currency.code }}</td>
                                        <td>{{ flow.comment }}</td>
                                        <td class="d-flex justify-content-end">
                                            <button type="button" class="btn btn-primary btn-sm me-2 js-update-flow"
                                                :data-id="flow.id" @click="toggleModal">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" :data-id="flow.id"
                                                @click="deleteFlow">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td>Total:</td>
                                        <td>
                                            <div v-for="(total, code) in userInfo.total" :key="code">
                                                {{ total.summ + ' ' + total.currency.symbol }}
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <flow-modal :title="modalTitle" :options="options" :currencyId="currencyId" :flowId="flowId"
        @add-event="addflow"></flow-modal>
</template>

<script>
import Modal from 'bootstrap/js/dist/modal';
import store from '@/store'

export default {
    props: ['key', 'rerender'],
    data: () => ({
        dates: null,
        options: {
            users: [],
            currencies: [],
            tags: []
        },
        currencyId: null,
        flowId: null,
        addForm: null,
        modal: null,
        modalTitle: 'Add new Flow'
    }),
    mounted() {
        const _this = this;

        this.getData();

        this.addForm = document.getElementById('form-add-income');

        this.modal = new Modal('#flowModal');
        document.getElementById('flowModal').addEventListener('hidden.bs.modal', event => {
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
                    this.currencyId = data.defaultCurrencyId;

                    this.setupSumms();

                    // make data for selects
                    for (const currency of data.currencies) {
                        this.options.currencies.push({
                            value: currency.id,
                            title: currency.code + ' - ' + currency.symbol
                        });
                    }
                    for (const dataUser of data.users) {
                        this.options.users.push({
                            value: dataUser.id,
                            title: dataUser.name
                        });
                    }
                    for (const tag of data.tags) {
                        this.options.tags.push({
                            value: tag.id,
                            title: tag.name
                        });
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        },
        toggleModal(e) {
            const button = this.getButton(e.target);
            if (button.classList.contains('js-add-flow')) {
                this.modalTitle = 'Add new Flow';
            } else if (button.classList.contains('js-update-flow')) {
                // TODO: push flowId to fields id attribute
                this.flowId = button.dataset.id;
                this.modalTitle = 'Update Flow';
            }
            this.modal.toggle();
        },

        addflow(e) {
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
        deleteFlow(e) {
            e.preventDefault();

            const button = this.getButton(e.target);
            const flowId = button.dataset.id;

            if (flowId != undefined) {
                axios
                    .delete('/api/flows/' + flowId)
                    .then(response => {
                        document.getElementById('flow-' + flowId).remove();
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        },

        getButton(target) {
            let button = target;
            if (button.tagName.toUpperCase() !== 'BUTTON') {
                button = button.closest('button');
            }

            return button;
        },
        isLast(key, obj) {
            const lastKeyIndex = Object.keys(obj).length - 1;

            return Object.keys(obj)[lastKeyIndex] === key;
        },
        setupSumms() {
            for (const year in this.dates) {
                const months = this.dates[year];
                for (const month in months) {
                    const monthData = months[month];
                    for (const userId in monthData.users) {

                        const user = monthData.users[userId];
                        user.total = {};
                        for (const flow of user.flows) {
                            if (user.total[flow.currency.code]) {
                                user.total[flow.currency.code].summ += flow.amount;
                            } else {
                                user.total[flow.currency.code] = { summ: flow.amount, currency: flow.currency };
                            }
                        }
                    }
                }
            }
        }
    },
};
</script>
