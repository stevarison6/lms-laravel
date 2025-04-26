<template>
    <div class="p-6 bg-gray-100 min-h-screen">
      <div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow-md">
        <Link :href="route('admin.page')" class="flex items-center text-blue-600 hover:text-blue-800 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7" />
          </svg>
          Retour
        </Link>
        <div class="flex justify-between items-center mt-2 mb-6">
          <h1 class="text-2xl font-bold">Liste des Quizz</h1>
          <Link :href="route('quiz.create')" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            + Créer un Quiz
          </Link>
        </div>
  
        <input
          v-model="search"
          type="text"
          placeholder="🔍 Rechercher un quiz..."
          class="w-full mb-4 p-2 border border-gray-300 rounded focus:outline-none focus:ring"
        />
  
        <div v-if="filteredQuizzes.length">
          <table class="w-full border text-sm">
            <thead>
              <tr class="bg-gray-100 text-left">
                <th class="p-2 border">Titre</th>
                <th class="p-2 border">Cours Associé</th>
                <th class="p-2 border text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="quiz in filteredQuizzes" :key="quiz.id" class="hover:bg-gray-50">
                <td class="p-2 border">{{ quiz.title }}</td>
                <td class="p-2 border">{{ quiz.course?.title || 'Non défini' }}</td>
                <td class="p-2 border text-center space-x-2">
                  <Link :href="route('quiz.edit', quiz.id)" class="text-blue-600 hover:underline">Modifier</Link>
                  <button @click="confirmDelete(quiz.id)" class="text-red-600 hover:underline">Supprimer</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-else class="text-center text-gray-500 mt-8">
          Aucun quiz trouvé.
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue'
  import { Link, router } from '@inertiajs/vue3'
  import Swal from 'sweetalert2'
  
  const props = defineProps({
    quizzes: Array,
    success: String,
  })
  
  const search = ref('')
  
  const filteredQuizzes = computed(() =>
    props.quizzes.filter(q =>
      q.title.toLowerCase().includes(search.value.toLowerCase())
    )
  )
  
  const confirmDelete = (id) => {
    Swal.fire({
      title: 'Supprimer ce quiz ?',
      text: 'Cette action est irréversible.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e3342f',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Oui, supprimer',
      cancelButtonText: 'Annuler',
    }).then((result) => {
      if (result.isConfirmed) {
        router.delete(route('quiz.destroy', id), {
          preserveScroll: true,
          onSuccess: () => {
            Swal.fire('Supprimé !', 'Le quiz a été supprimé.', 'success')
          },
          onError: () => {
            Swal.fire('Erreur', 'La suppression a échoué.', 'error')
          },
        })
      }
    })
  }
  
  if (props.success) {
    Swal.fire('Succès', props.success, 'success')
  }
  </script>
  