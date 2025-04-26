<template>
  <div class="min-h-screen bg-gray-50 flex flex-col items-center justify-start py-12 px-4 md:px-8">
    <!-- Titre -->
    <div class="max-w-2xl w-full text-center mb-10">
      <h1 class="text-4xl font-extrabold text-gray-800 mb-2">📝 Quiz : {{ quiz.title }}</h1>
      <p class="text-gray-600 text-lg">Testez vos connaissances !</p>
    </div>

    <!-- Questions -->
    <div class="max-w-2xl w-full space-y-8">
      <div
        v-for="(question, index) in questions"
        :key="question.id"
        class="bg-white rounded-2xl shadow p-6 text-center hover:shadow-lg transition"
      >
        <h2 class="font-semibold text-lg text-gray-800 mb-6">
          {{ index + 1 }}. {{ question.question_text }}
        </h2>

        <div class="flex flex-col gap-4 w-full md:w-3/4 mx-auto">
          <label
            v-for="optionKey in ['option_a', 'option_b', 'option_c', 'option_d']"
            :key="optionKey"
            class="flex items-center gap-3 bg-gray-100 rounded-lg py-3 px-4 cursor-pointer hover:bg-blue-50 transition"
          >
            <input
              type="radio"
              :name="'question_' + index"
              :value="question[optionKey]"
              v-model="answers[question.id]"
              class="accent-blue-600 w-4 h-4"
            />
            <span class="text-gray-700">{{ question[optionKey] }}</span>
          </label>
        </div>
      </div>
    </div>

    <!-- Boutons -->
    <div class="flex flex-col md:flex-row gap-4 mt-12">
      <button
        @click="submitQuiz"
        class="bg-blue-600 text-white px-8 py-3 rounded-full font-semibold shadow hover:bg-blue-700 transition"
      >
        Soumettre mes réponses
      </button>

      <button
        @click="resetAnswers"
        class="bg-gray-300 text-gray-800 px-8 py-3 rounded-full font-semibold shadow hover:bg-gray-400 transition"
      >
        Réinitialiser
      </button>

      <button
        v-if="nextQuizId"
        @click="goToNextQuiz"
        class="bg-green-500 text-white px-8 py-3 rounded-full font-semibold shadow hover:bg-green-600 transition"
      >
        Quiz Suivant →
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import axios from 'axios'

const props = defineProps({
  quiz: Object,
  questions: Array,
  nextQuizId: Number
})

const answers = ref({})

const submitQuiz = async () => {
  try {
    const response = await axios.post(`/quiz/${props.quiz.id}/submit`, {
      answers: answers.value
    })

    Swal.fire({
      title: 'Résultat',
      text: `Vous avez obtenu ${response.data.score} sur ${response.data.total} (${response.data.percentage.toFixed(2)}%)`,
      icon: 'success'
    })
  } catch (error) {
    Swal.fire({
      title: 'Erreur',
      text: 'Une erreur est survenue lors de la soumission.',
      icon: 'error'
    })
  }
}

const resetAnswers = () => {
  answers.value = {}
}

const goToNextQuiz = () => {
  router.visit(`/quiz/${props.nextQuizId}`)
}
</script>
