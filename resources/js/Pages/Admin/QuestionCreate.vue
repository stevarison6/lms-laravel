<template>
    <div class="p-6 max-w-3xl mx-auto">
      <Link :href="route('question.manage')" class="flex items-center text-blue-600 hover:text-blue-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7" />
            </svg>
            Retour
      </Link>
      <h1 class="text-2xl font-bold mb-6">Créer une nouvelle Question</h1>
  
      <form @submit.prevent="submit" class="space-y-4 bg-white p-6 rounded shadow">
        <div>
          <label class="block text-sm font-medium">Texte de la question</label>
          <textarea v-model="form.question_text" class="w-full p-2 border rounded" rows="3" />
          <span class="text-red-500 text-sm" v-if="form.errors.question_text">{{ form.errors.question_text }}</span>
        </div>
  
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium">Option A</label>
            <input v-model="form.option_a" class="w-full p-2 border rounded" />
            <span class="text-red-500 text-sm" v-if="form.errors.option_a">{{ form.errors.option_a }}</span>
          </div>
          <div>
            <label class="block text-sm font-medium">Option B</label>
            <input v-model="form.option_b" class="w-full p-2 border rounded" />
            <span class="text-red-500 text-sm" v-if="form.errors.option_b">{{ form.errors.option_b }}</span>
          </div>
          <div>
            <label class="block text-sm font-medium">Option C</label>
            <input v-model="form.option_c" class="w-full p-2 border rounded" />
            <span class="text-red-500 text-sm" v-if="form.errors.option_c">{{ form.errors.option_c }}</span>
          </div>
          <div>
            <label class="block text-sm font-medium">Option D</label>
            <input v-model="form.option_d" class="w-full p-2 border rounded" />
            <span class="text-red-500 text-sm" v-if="form.errors.option_d">{{ form.errors.option_d }}</span>
          </div>
        </div>
  
        <div>
          <label class="block text-sm font-medium">Bonne réponse</label>
          <select v-model="form.correct_answer" class="w-full p-2 border rounded">
            <option disabled value="">Sélectionnez</option>
            <option value="option_a">Option A</option>
            <option value="option_b">Option B</option>
            <option value="option_c">Option C</option>
            <option value="option_d">Option D</option>
          </select>
          <span class="text-red-500 text-sm" v-if="form.errors.correct_answer">{{ form.errors.correct_answer }}</span>
        </div>
  
        <div>
          <label class="block text-sm font-medium">Quiz associé</label>
          <select v-model="form.quiz_id" class="w-full p-2 border rounded">
            <option disabled value="">Sélectionnez un quiz</option>
            <option v-for="quiz in quizzes" :key="quiz.id" :value="quiz.id">{{ quiz.title }}</option>
          </select>
          <span class="text-red-500 text-sm" v-if="form.errors.quiz_id">{{ form.errors.quiz_id }}</span>
        </div>
  
        <div class="text-right">
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Créer la question
          </button>
        </div>
      </form>
    </div>
  </template>
  
  <script setup>
  import { useForm, router } from '@inertiajs/vue3'
  import Swal from 'sweetalert2'
  import { Link } from '@inertiajs/vue3';
  
  const props = defineProps({
    quizzes: Array,
  })
  
  const form = useForm({
    question_text: '',
    option_a: '',
    option_b: '',
    option_c: '',
    option_d: '',
    correct_answer: '',
    quiz_id: '',
  })
  
  const submit = () => {
    form.post(route('question.store'), {
      onSuccess: () => {
        Swal.fire('Succès', 'Question créée avec succès', 'success')
        form.reset()
      }
    })
  }
  </script>
  