<template>
    <div class="max-w-2xl mx-auto p-6 bg-white rounded-2xl shadow-md mt-10">
      <Link :href="route('quizz.manage')" class="flex items-center text-blue-600 hover:text-blue-800 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7" />
          </svg>
          Retour
        </Link>
      <h2 class="text-2xl font-bold mb-6 text-gray-800 mt-2">Modifier le Quiz</h2>
      
      <form @submit.prevent="submit">
        <div class="mb-4">
          <label for="title" class="block text-sm font-medium text-gray-700">Titre du quiz</label>
          <input
            v-model="form.title"
            type="text"
            id="title"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
          />
          <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</div>
        </div>
  
        <div class="mb-4">
          <label for="course_id" class="block text-sm font-medium text-gray-700">Cours associé</label>
          <select
            v-model="form.course_id"
            id="course_id"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
          >
            <option value="" disabled>Sélectionnez un cours</option>
            <option v-for="course in courses" :key="course.id" :value="course.id">
              {{ course.title }}
            </option>
          </select>
          <div v-if="form.errors.course_id" class="text-red-500 text-sm mt-1">{{ form.errors.course_id }}</div>
        </div>
  
        <div class="flex justify-end">
          <button
            type="submit"
            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-150"
          >
            Enregistrer
          </button>
        </div>
      </form>
    </div>
  </template>
  
  <script setup>
  import { useForm } from '@inertiajs/vue3'
  import Swal from 'sweetalert2'
  import { Link } from '@inertiajs/vue3'
  
  const props = defineProps({
    quiz: Object,
    courses: Array,
  })
  
  const form = useForm({
    title: props.quiz.title,
    course_id: props.quiz.course_id,
  })
  
  const submit = () => {
    form.put(route('quiz.update', props.quiz.id), {
      onSuccess: () => {
        Swal.fire({
          icon: 'success',
          title: 'Succès',
          text: 'Le quiz a été modifié avec succès.',
        })
      },
      onError: () => {
        Swal.fire({
          icon: 'error',
          title: 'Erreur',
          text: 'Veuillez vérifier les champs du formulaire.',
        })
      }
    })
  }
  </script>
  