<template>
    <field type="date" :title="title" :name="name" :value="date" :customAttrs="{min, max}"></field>
</template>

<script>
export default {
    props: ['name', 'value', 'title',  'min', 'max'],
    computed: {
        date() {
            return this.value || this.getCurrentDate();
        }
    },
    methods: {
        getCurrentDate() {
            const date = new Date();

            return date.toISOString().split('T')[0];
        }
    }
};
</script>

