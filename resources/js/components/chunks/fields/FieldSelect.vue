<template>
    <div class="form-control-wrapper">
        <label :for="attrs.id" class="form-label">{{ attrs.title }}</label>
        <select v-bind="attrs" :aria-label="attrs.title" :data-value="value">
            <option v-for="option in options" :key="option.value" :value="option.value" :selected="selected == option.value">
                {{ option.title }}
            </option>
        </select>
    </div>
</template>

<script>
import { isProxy, toRaw } from 'vue';

export default {
    props: ['name', 'title', 'options', 'value', 'customAttrs'],
    data: () => ({
        attrs: {
            class: 'form-select'
        }
    }),
    computed: {
        selected() {
            if (this.value && this.options) {
                return this.value || toRaw(this.options)[0].value;
            }
        }
    },
    mounted() {
        this.attrs.name = this.name;
        this.attrs.id = 'select-' + this.name;
        this.attrs.title = this.title || this.name.charAt(0).toUpperCase() + this.name.slice(1);

        for (const key in this.customAttrs) {
            this.attrs[key] = this.customAttrs[key];
        }
    }
};
</script>
