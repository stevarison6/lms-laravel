<template>
  <div class="flex gap-2 mb-4">
    <button
      v-for="tab in tabs"
      :key="tab.value"
      @click="$emit('change-tab', tab.value)"
      :class="[
        'px-4 py-2 rounded',
        tab.value === activeTab ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300'
      ]"
    >
      {{ tab.label }}
    </button>
  </div>
</template>

<script setup>
defineProps({
  tabs: Array,
  activeTab: String
})
</script>
