<template>
    <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
      <div class="text-2xl font-bold text-blue-600">🎯 AdminPanel</div>
      <div class="flex items-center gap-4">
        <span class="text-gray-700 font-medium">Admin</span>
        <Dropdown>
        <template #trigger >
        <img
          :src="userProfilePhoto "
          :alt="page.props.auth.user.name"
          class="w-10 h-10 rounded-full object-cover cursor-pointer"
        />
          </template>
          <template #content>
            <DropdownLink :href="route('profile.show')">
              Profile
            </DropdownLink>
            <DropdownLink :href="route('dashboard')">
              dashboard
            </DropdownLink>

          </template>
        </Dropdown>
      </div>
    </nav>
    </template>
    <script setup>
    // import { ref } from 'vue';
    import {usePage } from '@inertiajs/vue3';
    import DropdownLink from '@/Components/DropdownLink.vue';
    import Dropdown from '@/Components/Dropdown.vue';
    const page = usePage()
    const userProfilePhoto = page.props.auth.user.profile_photo_url
    

    </script>