<template>
  <div class="min-h-screen bg-gray-100 p-8">
    <!-- En-tête -->
    <div class="mb-8 flex justify-between items-center">
      <div class="flex items-center space-x-2">
        <Link :href="route('admin.page')" class="flex items-center text-blue-600 hover:text-blue-800 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7" />
          </svg>
          Retour
        </Link>
        <h1 class="text-3xl font-bold text-gray-800">Créer / Gérer un Cours</h1>
      </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-white p-8 rounded-2xl shadow-md max-w-3xl mx-auto">
      <form @submit.prevent="submitCourse" class="space-y-6" enctype="multipart/form-data">
        <!-- Titre -->
        <div>
          <label class="block text-gray-700 font-medium mb-2">Titre du Cours</label>
          <input
            v-model="course.title"
            type="text"
            class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-400 focus:outline-none"
            placeholder="Entrez le titre du cours"
          />
          <p v-if="errors.title" class="text-red-600 text-sm mt-1">{{ errors.title }}</p>
        </div>

        <!-- Description -->
        <div>
          <label class="block text-gray-700 font-medium mb-2">Description du Cours</label>
          <textarea
            v-model="course.description"
            rows="4"
            class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-400 focus:outline-none"
            placeholder="Décrivez le cours en quelques lignes"
          ></textarea>
          <p v-if="errors.description" class="text-red-600 text-sm mt-1">{{ errors.description }}</p>
        </div>

        <!-- Photo -->
        <div>
          <label class="block text-gray-700 font-medium mb-2">Image de Couverture</label>
          <input
            type="file"
            @change="handleFileUpload"
            class="block w-full text-gray-600"
            accept="image/*"
          />
          <p v-if="errors.photo" class="text-red-600 text-sm mt-1">{{ errors.photo }}</p>
        </div>

        <!-- Sélection Instructeur avec filtre -->
        <div>
          <label class="block text-gray-700 font-medium mb-2">Instructeur</label>
          <input
            type="text"
            v-model="searchQuery"
            class="w-full border border-gray-300 rounded-lg p-3 mb-2 focus:ring-2 focus:ring-blue-400 focus:outline-none"
            placeholder="Chercher un instructeur..."
          />
          <div class="max-h-40 overflow-auto border rounded-lg">
            <div
              v-for="instructor in filteredInstructors"
              :key="instructor.id"
              @click="selectInstructor(instructor)"
              :class="['p-2 cursor-pointer', instructor.id === course.instructor_id ? 'bg-blue-100' : 'hover:bg-gray-100']"
            >
              {{ instructor.name }}
            </div>
          </div>
          <div v-if="course.instructor_id" class="mt-2 text-sm text-green-600">
            Instructeur sélectionné : {{ selectedInstructorName }}
          </div>
          <p v-if="errors.instructor_id" class="text-red-600 text-sm mt-1">{{ errors.instructor_id }}</p>
        </div>

        <!-- Boutons -->
        <div class="flex justify-end space-x-4">
          <button type="reset" class="px-6 py-2 bg-gray-300 rounded-full hover:bg-gray-400 text-gray-800">
            Réinitialiser
          </button>
          <button type="submit" class="px-6 py-2 bg-blue-600 rounded-full text-white hover:bg-blue-700 transition">
            Enregistrer
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import Toastify from 'toastify-js'
import "toastify-js/src/toastify.css"

const course = ref({
  title: '',
  description: '',
  photo_path_course: null,
  instructor_id: ''
})

const errors = ref({
  title: '',
  description: '',
  photo: '',
  instructor_id: ''
})

const instructors = ref(usePage().props.instructors || [])
const searchQuery = ref('')

const filteredInstructors = computed(() => {
  if (!searchQuery.value) return instructors.value
  return instructors.value.filter(i => i.name.toLowerCase().includes(searchQuery.value.toLowerCase()))
})

const selectedInstructorName = computed(() => {
  const selected = instructors.value.find(i => i.id === course.value.instructor_id)
  return selected ? selected.name : ''
})

function selectInstructor(instructor) {
  course.value.instructor_id = instructor.id
}

function handleFileUpload(event) {
  course.value.photo_path_course = event.target.files[0]
}

function submitCourse() {
  // Reset errors
  errors.value = { title: '', description: '', photo: '', instructor_id: '' }

  let hasError = false

  if (!course.value.title) {
    errors.value.title = "Le titre du cours est requis."
    hasError = true
  }
  if (!course.value.description) {
    errors.value.description = "La description est requise."
    hasError = true
  }
  if (!course.value.photo_path_course) {
    errors.value.photo = "L'image de couverture est requise."
    hasError = true
  }
  if (!course.value.instructor_id) {
    errors.value.instructor_id = "Veuillez sélectionner un instructeur."
    hasError = true
  }

  if (hasError) return

  const formData = new FormData()
  formData.append('title', course.value.title)
  formData.append('description', course.value.description)
  formData.append('photo_path_course', course.value.photo_path_course)
  formData.append('instructor_id', course.value.instructor_id)

  router.post(route('course.store'), formData, {
    onSuccess: () => {
      Toastify({
        text: "Cours créé avec succès !",
        duration: 3000,
        gravity: "top",
        position: "right",
        style: { background: "#4CAF50" }
      }).showToast()

      course.value = { title: '', description: '', photo_path_course: null, instructor_id: '' }
      searchQuery.value = ''
    }
  })
}
</script>

<style scoped>
</style>
