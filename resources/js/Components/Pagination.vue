<template>
  <div class="flex justify-center mt-6">
    <nav class="flex space-x-1">
      <template v-for="(link, index) in links" :key="index">
        <span v-if="!link.url" class="px-3 py-1 text-gray-400" v-html="link.label" />
        <Link
          v-else
          :href="link.url"
          class="px-3 py-1 rounded hover:bg-blue-200"
          :class="{ 'bg-blue-500 text-white': link.active }"
          v-html="link.label"
        />
      </template>
    </nav>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({ links: Array })
</script>
