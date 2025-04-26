<template>
  <div class="min-h-screen bg-gray-100 flex items-center justify-center p-6">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-2xl">
      <Link :href="route('quizz.manage')" class="flex items-center text-blue-600 hover:text-blue-800 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7" />
          </svg>
          Retour
        </Link>
      <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 text-center">
        Créer un Nouveau Quizz
      </h1>

      <form @submit.prevent="confirmCreation" class="space-y-5">
        <div>
          <label class="block text-sm font-semibold text-gray-600 mb-2">Titre du Quizz</label>
          <input
            v-model="form.title"
            type="text"
            placeholder="Titre du quizz"
            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none"
            required
          />
          <div v-if="form.errors.title" class="text-sm text-red-600 mt-1">
            {{ form.errors.title }}
          </div>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-600 mb-2">Cours associé</label>
          <select
            v-model="form.course_id"
            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none"
            required
          >
            <option value="" disabled>Sélectionnez un cours</option>
            <option
              v-for="course in courses"
              :key="course.id"
              :value="course.id"
            >
              {{ course.title }}
            </option>
          </select>
          <div v-if="form.errors.course_id" class="text-sm text-red-600 mt-1">
            {{ form.errors.course_id }}
          </div>
        </div>

        <div class="flex justify-between mt-6">
          <button
            type="submit"
            class="bg-blue-600 text-white px-6 py-3 rounded-full shadow hover:bg-blue-700 transition"
          >
            Créer
          </button>
          <button
            type="button"
            @click="form.reset"
            class="bg-gray-300 text-gray-800 px-6 py-3 rounded-full shadow hover:bg-gray-400 transition"
          >
            Réinitialiser
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  courses: Array
})

const form = useForm({
  title: '',
  course_id: '',
})

function confirmCreation() {
  Swal.fire({
    title: 'Confirmer la création',
    text: "Voulez-vous vraiment créer ce quiz ?",
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#2563eb',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Oui, créer'
  }).then((result) => {
    if (result.isConfirmed) {
      form.post(route('quiz.store'), {
        onSuccess: () => {
          Swal.fire({
            title: 'Succès !',
            text: 'Le quiz a été créé avec succès.',
            icon: 'success',
            confirmButtonColor: '#2563eb'
          })
        }
      })
    }
  })
}
</script>
