<script setup>
defineProps({
    text: { type: String, required: true },
});
</script>

<template>
    <span class="text-sm text-red-600">{{ text }}</span>
</template>
