<template>
    <div class="min-h-screen bg-gray-100 p-8 space-y-8">
      
      <Link :href="route('admin.page')" class="flex items-center text-blue-600 hover:text-blue-800 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7" />
          </svg>
          Retour
      </Link>
      <h1 class="text-3xl font-bold text-gray-800 mb-6">👥 Gestion des Utilisateurs & Rôles</h1>
  
      <div class="flex justify-between items-center mb-6">
        <input
          v-model="search"
          @input="filterUsers"
          type="text"
          placeholder="🔍 Rechercher par nom..."
          class="px-4 py-2 border border-gray-300 rounded-full shadow w-full max-w-md focus:outline-none focus:ring focus:ring-blue-300"
        />
      </div>
  
      <div class="bg-white rounded-2xl shadow p-6">
        <table class="w-full text-left">
          <thead>
            <tr class="text-gray-700 border-b">
              <th class="p-3">Nom</th>
              <th class="p-3">Email</th>
              <th class="p-3">Rôle</th>
              <th class="p-3">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users.data" :key="user.id" class="border-t hover:bg-gray-50">
              <td class="p-3">{{ user.name }}</td>
              <td class="p-3">{{ user.email }}</td>
              <td class="p-3 capitalize">{{ user.role }}</td>
              <td class="p-3 flex items-center gap-2">
                <select
                  v-model="selectedRoles[user.id]"
                  class="border rounded px-3 py-1 focus:ring focus:ring-blue-300"
                >
                  <option value="admin">Admin</option>
                  <option value="student">Étudiant</option>
                  <option value="instructor">Instructeur</option>
                  
                </select>
                <button
                  @click="confirmUpdate(user.id)"
                  class="px-3 py-1 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition"
                >
                  Valider
                </button>
              </td>
            </tr>
          </tbody>
        </table>
  
        <div class="flex justify-center mt-6 space-x-2" v-if="users.links && users.links.length">
          <button
            v-for="page in users.links"
            :key="page.label"
            :disabled="!page.url"
            v-html="page.label"
            @click.prevent="goToPage(page.url)"
            :class="[
              'px-3 py-1 rounded',
              page.active ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800',
              !page.url ? 'opacity-50 cursor-not-allowed' : ''
            ]"
          ></button>
        </div>
      </div>
  
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { router, usePage } from '@inertiajs/vue3';
  import Swal from 'sweetalert2';
  import 'sweetalert2/dist/sweetalert2.min.css';
  import toast from 'toastify-js';
  import "toastify-js/src/toastify.css";
  import { Link } from '@inertiajs/vue3'
  
  const props = defineProps({
    users: Object,
    filters: Object,
  });
  
  const search = ref(props.filters.search || '');
  const selectedRoles = ref({});
  props.users.data.forEach(user => {
    selectedRoles.value[user.id] = user.role;
  });
  
  function confirmUpdate(userId) {
    Swal.fire({
      title: 'Confirmer la modification ?',
      text: "Voulez-vous vraiment changer le rôle de cet utilisateur ?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Oui, changer !'
    }).then((result) => {
      if (result.isConfirmed) {
        updateUserRole(userId);
      }
    });
  }
  
  function updateUserRole(userId) {
    router.put(route('admin.users.updateRole', { user: userId }), { role: selectedRoles.value[userId] }, {
      onSuccess: () => {
        toast({
          text: "Rôle mis à jour avec succès !",
          duration: 3000,
          close: true,
          gravity: "top",
          position: "right",
          backgroundColor: "#4CAF50",
        }).showToast();
      }
    });
  }
  
  function filterUsers() {
    router.get(route('admin.users.index'), { search: search.value }, {
      preserveState: true,
      replace: true
    });
  }
  
  function goToPage(url) {
    if (url) {
      router.visit(url);
    }
  }
  </script>
  
  <style scoped>
  body {
    font-family: 'Inter', sans-serif;
  }
  </style>
  