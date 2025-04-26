<template>
    <div class="p-4 space-y-6">
      <Link :href="route('admin.page')" class="flex items-center text-blue-600 hover:text-blue-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7" />
            </svg>
            Retour
      </Link>
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Gestion des Questions</h1>
        <Link :href="route('question.create')" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Créer une Question
        </Link>
      </div>
  
      <input
        v-model="search"
        type="text"
        placeholder="Rechercher une question..."
        class="w-full p-2 border border-gray-300 rounded"
      />
  
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div
          v-for="question in filteredQuestions"
          :key="question.id"
          class="bg-white p-4 rounded shadow space-y-2"
        >
          <h2 class="font-semibold">{{ question.question_text }}</h2>
          <p class="text-sm text-gray-600">Quiz : {{ question.quiz_title }}</p>
          <div class="flex justify-end gap-2 text-sm">
            <Link :href="route('question.edit', question.id)" class="text-blue-600 hover:underline">Modifier</Link>
            <button @click="confirmDelete(question.id)" class="text-red-600 hover:underline">Supprimer</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue'
  import { router, Link } from '@inertiajs/vue3'
  import Swal from 'sweetalert2'
  
  const props = defineProps({
    questions: Array
  })
  
  const search = ref('')
  
  const filteredQuestions = computed(() =>
    props.questions.filter(q =>
      q.question_text.toLowerCase().includes(search.value.toLowerCase())
    )
  )
  
  const confirmDelete = (id) => {
    Swal.fire({
      title: 'Confirmer la suppression',
      text: 'Voulez-vous vraiment supprimer cette question ?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Oui, supprimer',
      cancelButtonText: 'Annuler'
    }).then((result) => {
      if (result.isConfirmed) {
        router.delete(route('question.destroy', id), {
          onSuccess: () => {
            Swal.fire('Supprimé', 'La question a été supprimée.', 'success')
          }
        })
      }
    })
  }
  </script>
  