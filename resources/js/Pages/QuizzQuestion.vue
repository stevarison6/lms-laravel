<template>
    <div class="p-8 bg-gray-100 min-h-screen space-y-8">
      <!-- Titre -->
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Questions pour : {{ quizzName }}</h1>
        <button
          @click="openModal = true"
          class="bg-blue-600 text-white px-5 py-2 rounded-full shadow hover:bg-blue-700 transition"
        >
          ➕ Ajouter une Question
        </button>
      </div>
  
      <!-- Liste des Questions -->
      <div class="bg-white rounded-2xl shadow p-6 overflow-x-auto">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Liste des Questions</h2>
  
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Question</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Réponses</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bonne Réponse</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="(question, index) in questions" :key="index">
              <td class="px-6 py-4 text-gray-800">{{ question.text }}</td>
              <td class="px-6 py-4 text-gray-600">{{ question.choices.join(', ') }}</td>
              <td class="px-6 py-4 text-green-700 font-bold">{{ question.correct }}</td>
              <td class="px-6 py-4 text-right">
                <button class="text-sm text-blue-600 hover:underline mr-3">Modifier</button>
                <button class="text-sm text-red-600 hover:underline">Supprimer</button>
              </td>
            </tr>
            <tr v-if="questions.length === 0">
              <td colspan="4" class="px-6 py-4 text-center text-gray-500">Aucune question disponible.</td>
            </tr>
          </tbody>
        </table>
      </div>
  
      <!-- Modal Ajouter Question -->
      <div v-if="openModal" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 space-y-4">
          <h2 class="text-xl font-bold text-gray-800">Ajouter une Question</h2>
  
          <div class="space-y-3">
            <label class="block text-sm text-gray-700 font-semibold">Question</label>
            <input type="text" v-model="newQuestion.text"
              class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-300" placeholder="Écrivez la question ici" />
  
            <label class="block text-sm text-gray-700 font-semibold">Réponses possibles (séparées par une virgule)</label>
            <input type="text" v-model="newQuestion.choices"
              class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-300" placeholder="Exemple: Réponse A, Réponse B, Réponse C" />
  
            <label class="block text-sm text-gray-700 font-semibold">Bonne Réponse</label>
            <input type="text" v-model="newQuestion.correct"
              class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-300" placeholder="Entrez la bonne réponse" />
          </div>
  
          <div class="flex justify-between pt-4">
            <button
              @click="saveQuestion"
              class="bg-green-500 text-white px-6 py-2 rounded-full hover:bg-green-600 transition"
            >
              ✅ Enregistrer
            </button>
            <button
              @click="openModal = false"
              class="bg-gray-300 text-gray-700 px-6 py-2 rounded-full hover:bg-gray-400 transition"
            >
              Annuler
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  
  const quizzName = "Quizz Laravel #1";
  
  const questions = ref([
    { text: "Laravel utilise quel moteur de template ?", choices: ["Twig", "Blade", "Smarty"], correct: "Blade" },
    { text: "Quel Artisan permet de créer un contrôleur ?", choices: ["make:model", "make:controller", "serve"], correct: "make:controller" }
  ]);
  
  const openModal = ref(false);
  
  const newQuestion = ref({
    text: '',
    choices: '',
    correct: ''
  });
  
  function saveQuestion() {
    if (newQuestion.value.text && newQuestion.value.choices && newQuestion.value.correct) {
      questions.value.push({
        text: newQuestion.value.text,
        choices: newQuestion.value.choices.split(',').map(choice => choice.trim()),
        correct: newQuestion.value.correct
      });
      openModal.value = false;
      newQuestion.value = { text: '', choices: '', correct: '' };
    } else {
      alert("Veuillez remplir tous les champs.");
    }
  }
  </script>
  
  <style scoped>
  </style>
  