<template>
    <div class="min-h-screen bg-gray-100 p-8">
      <div class="flex items-center gap-4">
        <Link :href="route('admin.page')" class="flex items-center text-blue-600 hover:text-blue-800 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7" />
          </svg>
          Retour
        </Link>
        <h1 class="text-3xl font-bold mb-6 text-gray-800">⚙️ Paramètres Générales</h1>
      </div>
  
      <form @submit.prevent="submit" class="bg-white rounded-2xl shadow p-6 space-y-4 max-w-2xl">
        <div>
          <label for="site_name" class="block font-semibold text-gray-700">Nom du site</label>
          <input id="site_name" v-model="form.site_name" type="text"
                 class="mt-1 p-2 w-full border rounded focus:ring focus:ring-blue-300"
                 placeholder="Entrez le nom du site" />
        </div>
  
        <div>
          <label for="site_description" class="block font-semibold text-gray-700">Description</label>
          <textarea id="site_description" v-model="form.site_description"
                    class="mt-1 p-2 w-full border rounded focus:ring focus:ring-blue-300"
                    placeholder="Entrez une description..."></textarea>
        </div>
  
        <div>
          <label for="logo" class="block font-semibold text-gray-700">Logo du site</label>
          <input id="logo" type="file" @change="onFileChange"
                 class="mt-1 block w-full text-gray-700 border rounded cursor-pointer" />
          <div v-if="setting.logo_path" class="mt-2 ">
            <img :src="`/storage/logos/${setting.logo_path}`" alt="Logo actuel" class="h-16 rounded-full shadow" />
          </div>
        </div>
  
        <div>
          <label for="items_per_page" class="block font-semibold text-gray-700">Éléments par page</label>
          <input id="items_per_page" v-model="form.items_per_page" type="number" min="1"
                 class="mt-1 p-2 w-32 border rounded focus:ring focus:ring-blue-300" />
        </div>
  
        <button type="submit"
                :disabled="form.processing"
                class="px-6 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
          💾 {{ form.processing ? 'Enregistrement...' : 'Enregistrer' }}
        </button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { useForm } from '@inertiajs/vue3'
  import Swal from 'sweetalert2'
  import toast from 'toastify-js'
  import "sweetalert2/dist/sweetalert2.min.css"
  import "toastify-js/src/toastify.css"
  import { Link } from '@inertiajs/vue3';
  
  const props = defineProps({
    setting: Object
  })
  
  const form = useForm({
    site_name: props.setting.site_name || '',
    site_description: props.setting.site_description || '',
    items_per_page: props.setting.items_per_page || 10, // Valeur par défaut si vide
    logo: null
  })
  
  function onFileChange(event) {
    form.logo = event.target.files[0]
  }
  
  function submit() {
    console.log(form);
    // Vérification si les champs sont vides avant soumission
    if (!form.site_name || !form.items_per_page) {
      toast({
        text: "❌ Les champs sont requis !",
        duration: 3000,
        close: true,
        gravity: "top",
        position: "right",
        backgroundColor: "#f44336"
      }).showToast()
      return
    }
  
    Swal.fire({
      title: 'Confirmer la modification ?',
      text: "Les paramètres vont être sauvegardés.",
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Oui, enregistrer !'
    }).then(result => {
      if (result.isConfirmed) {
        form.post(route('admin.settings.update', props.setting.id), {
          preserveScroll: true,
          forceFormData: true,
          onSuccess: () => {
            toast({
              text: "✅ Paramètres mis à jour avec succès !",
              duration: 3000,
              close: true,
              gravity: "top",
              position: "right",
              backgroundColor: "#4CAF50"
            }).showToast()
          },
          onError: (errors) => {
            console.error("Erreurs de validation : ", errors);
            toast({
              text: "❌ Erreur lors de l'enregistrement.",
              duration: 3000,
              close: true,
              gravity: "top",
              position: "right",
              backgroundColor: "#f44336"
            }).showToast()
          }
        })
      }
    })
  }
  </script>
  
  <style scoped>
  body {
    font-family: 'Inter', sans-serif;
  }
  </style>
  