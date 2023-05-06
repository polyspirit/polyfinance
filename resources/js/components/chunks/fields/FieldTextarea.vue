<template>
    <field type="textarea" :name="name" :value="value"></field>
</template>

<script>
export default {
    props: ['name', 'value']
};
</script>
