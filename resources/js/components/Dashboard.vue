<template>
    <div class="card-header">
        <h3>dashboard</h3>
    </div>
    <div class="card-body">
        <p class="mb-0">
            You are logged in as <b>{{ user.email }}</b>
        </p>
    </div>
</template>

<script>
export default {
    name: "dashboard",
    data() {
        return {
            user: this.$store.state.auth.user,
        };
    },
};
</script>
