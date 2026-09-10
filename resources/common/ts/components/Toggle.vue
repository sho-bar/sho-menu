<script setup lang="ts">
import { ref } from 'vue'

const elem = ref(null)

const emit = defineEmits<{
    (e: 'switched', elem: HTMLInputElement | null): void
}>()
</script>

<template>
    <input
        ref="elem"
        @input="emit('switched', elem)"
        type="checkbox"
        id="switch"
    />
    <label for="switch">Toggle</label>
</template>

<style lang="css" scoped>
input[type=checkbox]{
    height: 0;
    width: 0;
    visibility: hidden;
    display: none;
}

label {
	cursor: pointer;
	text-indent: -9999px;
	width: 50px;
	height: 26px;
	background: grey;
	display: block;
	border-radius: 100px;
	position: relative;
    margin: 0;
}

label:after {
	content: '';
	position: absolute;
	top: 3px;
	left: 3px;
	width: 20px;
	height: 20px;
	background: #fff;
	border-radius: 90px;
	transition: 0.3s;
}

input:checked + label {
	background: #9bbf26;
}

input:checked + label:after {
	left: calc(100% - 3px);
	transform: translateX(-100%);
}
</style>
