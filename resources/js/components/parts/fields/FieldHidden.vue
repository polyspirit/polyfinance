<template>
    <field type="hidden" :name="name" :value="value"></field>
</template>

<script>
export default {
    props: ['name', 'value']
};
</script>
