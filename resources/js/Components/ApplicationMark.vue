<template>
    <img :src="logoUrl" alt="Logo" class="h-10 w-auto" />
</template>
<script setup>
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue';

const page = usePage()

const logoUrl = computed(() => {
  const path = page.props.settings?.logo_path
  return path ? `/storage/logos/${path}` : '/storage/logos/default-lms.png'
})
</script>
