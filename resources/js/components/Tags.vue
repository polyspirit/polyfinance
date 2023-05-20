<template>
    <div class="card-header">
        <h3>Tags</h3>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <button class="btn btn-primary mb-4" title="Add new Income" @click="toggleModal">
                <i class="bi bi-plus-lg"></i>
            </button>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Name: </th>
                    <th>Color: </th>
                    <th>Comment: </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="tag in tags" :key="tag.id">
                    <td>{{ tag.name }}</td>
                    <td :style="'color: ' + tag.color + ';'">{{ tag.color }}</td>
                    <td>{{ tag.comment }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="tagModal" tabindex="-1" aria-labelledby="tagModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="tagModalLabel">Add new Tag</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="/tags" method="POST" class="mt-2 container" id="form-add-tag">
                        <div class="row mb-3">
                            <div class="col">
                                <field-text name="name"></field-text>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <field name="color" type="color" title="Color" value="#ffffff"></field>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <field-textarea name="comment"></field-textarea>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="addTag">Add</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Modal from 'bootstrap/js/dist/modal';

export default {
    props: ['key', 'rerender'],
    data: () => ({
        tags: [],
        addForm: null,
        modal: null
    }),
    mounted() {
        const _this = this;

        this.getData();

        this.addForm = document.getElementById('form-add-tag');

        this.modal = new Modal('#tagModal');
        document.getElementById('tagModal').addEventListener('hidden.bs.modal', event => {
            _this.rerender();
        });
    },
    methods: {
        async getData() {
            await axios
                .get('/api/tags')
                .then((response) => {
                    const data = response.data.data;
                    this.tags = data.tags;
                    console.log(this.tags);
                })
                .catch(error => {
                    console.error(error);
                });
        },
        toggleModal() {
            this.modal.toggle();
        },
        addTag(e) {
            e.preventDefault();

            const data = new FormData(this.addForm);

            axios
                .post('/api/tags', data)
                .then(response => {
                    this.modal.hide();
                    console.log(response.data.data);
                })
                .catch(error => {
                    console.error(error);
                });
        }
    },
};
</script>
