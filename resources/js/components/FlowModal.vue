<template>
    <div class="modal fade" id="flowModal" tabindex="-1" aria-labelledby="flowModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="flowModalLabel">{{ title }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="/flows" method="POST" class="mt-2 container" id="form-add-income">
                        <div class="row mb-3">
                            <div class="col-6">
                                <field-date name="created_at" :itemId="flowId" title="Date"></field-date>
                            </div>
                            <div class="col-6">
                                <field-select name="user_id" :itemId="flowId" title="User" :options="options.users"></field-select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <field-text name="item" :itemId="flowId"></field-text>
                            </div>
                            <div class="col-6">
                                <field-select name="tag_id" :itemId="flowId" title="Tag" :options="options.tags"></field-select>
                            </div>
                        </div>
                        <div class="row  mb-3 align-items-end">
                            <div class="col-6">
                                <field-price name="amount" :itemId="flowId"></field-price>
                            </div>
                            <div class="col-6">
                                <field-select name="currency_id" :itemId="flowId" title="Currency" :options="options.currencies"
                                    :value="currencyId"></field-select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <field-textarea name="comment" :itemId="flowId"></field-textarea>
                            </div>
                        </div>
                        <field-hidden name="type" :itemId="flowId" value="income"></field-hidden>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="addflow">Add</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

export default {
    props: ['title', 'options', 'currencyId', 'flowId'],
    data: () => ({
    }),
    updated() {
        // console.log(this.flowId);
    },
    methods: {
        addflow(e) {
            this.$emit('add-event', e);
        },
    },
};
</script>
