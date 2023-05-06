<template>
    <div class="form-control-wrapper">
        <label :for="attrs.id" class="form-label">{{ attrs.title }}</label>
        <select v-bind="attrs" aria-label="{{ attrs.title }}">
            <option v-for="option in options" :key="option.value" :value="option.value">
                {{ option.title }}
            </option>
        </select>
    </div>
</template>

<script>
export default {
    props: ['name', 'options', 'value', 'customAttrs'],
    data: () => ({
        attrs: {
            class: 'form-select'
        }
    }),
    mounted() {
        if (this.type !== 'textarea') {
            this.attrs.type = this.type;
        }
        this.attrs.name = this.name;
        this.attrs.value = this.value || '';
        this.attrs.id = 'select-' + this.name;
        this.attrs.title = this.name.charAt(0).toUpperCase() + this.name.slice(1);

        for (const key in this.customAttrs) {
            this.attrs[key] = this.customAttrs[key];
        }
    },
};
</script>
