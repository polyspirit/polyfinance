<template>
    <div class="card-header">
        <h3>Incomes</h3>
    </div>
    <div class="card-body">
        <div v-for="flow in flows" :key="flow.id">
            {{ flow.id }}: {{ flow.item }}
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        flows: null,
    }),
    mounted() {
        this.getFlows();
    },
    methods: {
        async getFlows(callback) {
            axios
                .get("/api/flows/")
                .then((response) => {
                    this.flows = response.data.data;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
    },
};
</script>
