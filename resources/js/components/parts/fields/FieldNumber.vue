<template>
    <field type="number" :name="name" :customAttrs="{step, min, max}"></field>
</template>

<script>
export default {
    props: ['name', 'step', 'min', 'max']
};
</script>

