<template>
    <div class="form-control-wrapper">
        <label v-if="type !== 'hidden'" :for="attrs.id" class="form-label">{{ attrs.title }}</label>
        <textarea v-if="type === 'textarea'" v-bind="attrs"></textarea>
        <input v-else v-bind="attrs">
    </div>
</template>

<script>
export default {
    props: ['name', 'title', 'type', 'value', 'itemId', 'customAttrs'],
    data: () => ({
        attrs: {
            class: 'form-control'
        }
    }),
    mounted() {
        if (this.type !== 'textarea') {
            this.attrs.type = this.type;
        }
        this.attrs.name = this.name;
        this.attrs.value = this.value || '';
        this.attrs.title = this.title || this.name.charAt(0).toUpperCase() + this.name.slice(1);
        this.attrs.placeholder = this.attrs.title;
        this.attrs.id = this.type + '-' + this.name;

        console.log(this.itemId);
        if (this.itemId) {
            this.attrs.id += '-' + this.itemId;
        }

        for (const key in this.customAttrs) {
            this.attrs[key] = this.customAttrs[key];
        }
    },
};
</script>
