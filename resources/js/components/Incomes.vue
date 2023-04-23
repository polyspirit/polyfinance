<template>
    <div class="card-header">
        <h3>Incomes</h3>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <button class="btn btn-primary mb-4" title="Add new Income" data-bs-toggle="modal"
                data-bs-target="#exampleModal">
                <i class="bi bi-plus-lg"></i>
            </button>
        </div>

        <div v-for="flow in flows" :key="flow.id">
            {{ flow.id }}: {{ flow.item }}
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add new Income</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="/flows" method="POST" class="mt-2" id="form-add-income">
                        <field-text name="item"></field-text>
                        <field-price name="amount"></field-price>
                        <field-textarea name="comment"></field-textarea>
                        <field-hidden name="income" value="1"></field-hidden>
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
export default {
    data: () => ({
        flows: null,
        addForm: null
    }),
    mounted() {
        this.getFlows();

        this.addForm = document.getElementById('form-add-income');
    },
    methods: {
        async getFlows() {
            axios
                .get('/api/flows/')
                .then((response) => {
                    this.flows = response.data.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        addIncome(e) {
            e.preventDefault();

            const data = new FormData(this.addForm);

            axios
                .post('/api/flows/', data)
                .then(response => {
                    console.log(response.data.data);
                })
                .catch(error => {
                    console.error(error);
                });
        }
    },
};
</script>
