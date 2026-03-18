<template>
    <span @click="toggle" class="cursor-pointer inline-block">
    <slot name="button"></slot>
  </span>

    <div v-show="visible" class="transition-all duration-300 ease-in-out mt-2">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: 'ToggleBlock',
    props: {
        initialVisible: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            visible: this.initialVisible,
        };
    },
    methods: {
        toggle() {
            this.visible = !this.visible;
        },
    },
};
</script>
