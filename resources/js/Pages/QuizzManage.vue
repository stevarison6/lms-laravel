<template>
    <div class="min-h-screen bg-gray-100 p-8">
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Gestion des Quizz — {{ courseTitle }}</h1>
        <a
          href="/admin/quizz/create"
          class="bg-blue-600 text-white px-5 py-2 rounded-full shadow hover:bg-blue-700 transition"
        >
          + Ajouter un Quizz
        </a>
      </div>
  
      <div class="bg-white p-6 rounded-2xl shadow-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nom du Quizz</th>
              <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Nombre de Questions</th>
              <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Date de Création</th>
              <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="quizz in quizzList" :key="quizz.id" class="hover:bg-gray-50 transition">
              <td class="px-4 py-3 font-medium text-gray-700">{{ quizz.name }}</td>
              <td class="px-4 py-3 text-center text-gray-700">{{ quizz.questionsCount }}</td>
              <td class="px-4 py-3 text-center text-gray-500">{{ formatDate(quizz.created_at) }}</td>
              <td class="px-4 py-3 text-center space-x-2">
                <a
                  :href="`/admin/quizz/${quizz.id}/edit`"
                  class="bg-yellow-500 text-white px-4 py-2 rounded-full shadow hover:bg-yellow-600 transition"
                >
                  Modifier
                </a>
                <button
                  @click="deleteQuizz(quizz.id)"
                  class="bg-red-500 text-white px-4 py-2 rounded-full shadow hover:bg-red-600 transition"
                >
                  Supprimer
                </button>
              </td>
            </tr>
            <tr v-if="quizzList.length === 0">
              <td colspan="4" class="text-center text-gray-500 py-6">Aucun quizz ajouté pour ce cours.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const courseTitle = 'Laravel pour Débutants';
  
  // Simulation de données
  const quizzList = ref([
    { id: 1, name: 'Introduction à Laravel', questionsCount: 10, created_at: '2025-04-10' },
    { id: 2, name: 'Middleware & Routing', questionsCount: 8, created_at: '2025-04-12' },
    { id: 3, name: 'Eloquent ORM', questionsCount: 15, created_at: '2025-04-14' },
  ]);
  
  function formatDate(date) {
    return new Date(date).toLocaleDateString('fr-FR', { year: 'numeric', month: 'long', day: 'numeric' });
  }
  
  function deleteQuizz(id) {
    if (confirm('Voulez-vous supprimer ce quizz ?')) {
      const index = quizzList.value.findIndex(q => q.id === id);
      if (index !== -1) {
        quizzList.value.splice(index, 1);
        alert('Quizz supprimé avec succès.');
      }
    }
  }
  </script>
  
  <style scoped>
  </style>
  