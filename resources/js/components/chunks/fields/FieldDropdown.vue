<template>
    <div class="form-control-wrapper">
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                {{ attrs.title }}
            </button>
            <ul v-bind="attrs">
                <li v-for="(link, index) in options" :key="index">
                    <a class="dropdown-item" href="{{ link }}">{{ index }}</a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    props: ['name', 'options', 'value', 'customAttrs'],
    data: () => ({
        attrs: {
            class: 'dropdown-menu dropdown-menu-dark'
        }
    }),
    mounted() {
        if (this.type !== 'textarea') {
            this.attrs.type = this.type;
        }
        this.attrs.name = this.name;
        this.attrs.value = this.value || '';
        this.attrs.id = 'dropdown-' + this.name;
        this.attrs.title = this.name.charAt(0).toUpperCase() + this.name.slice(1);

        for (const key in this.customAttrs) {
            this.attrs[key] = this.customAttrs[key];
        }
    },
};
</script>
