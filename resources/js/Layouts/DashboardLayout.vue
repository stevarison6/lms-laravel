<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { Link,router,usePage  } from '@inertiajs/vue3';

defineProps({
    userProfil: String,
    userName: String,
    photoLogo: String,
})

const dropdownOpen = ref(false);
const page = usePage();
let dropdownRef = null;

const handleClickOutside = (event) => {
    if (dropdownRef && !dropdownRef.contains(event.target)) {
        dropdownOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});
const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex flex-col">
    
    <nav class="bg-white shadow p-4 flex justify-between items-center">
      <div class="flex items-center space-x-2">
        <img :src="page.props.settings.logo_path??'/storage/logos/default-lms.png'" alt="Logo" class="h-10">
        <span class="font-bold text-xl text-gray-700">MyLMS</span>
      </div>
      <div class="relative" ref="dropdownRef">
        <img 
          :src="$page.props.auth.user.profile_photo_url"
          :alt="$page.props.auth.user.name"
          class="w-10 h-10 rounded-full cursor-pointer"
          @click="dropdownOpen = !dropdownOpen"
        >
        <div v-if="dropdownOpen" class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-md py-1 z-50">
          <Link :href="route('profile.show')" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profil</Link>
          <Link :href="route('admin.page')" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Admin</Link>
          
            <button @click="logout" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
              Se déconnecter
            </button>
          
        </div>
      </div>
    </nav>

    <div class="p-8 space-y-8 flex-1">
      <slot name="content" />
    </div>
  </div>
</template>