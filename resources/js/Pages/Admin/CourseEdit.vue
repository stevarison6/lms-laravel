<template>
    <div class="min-h-screen bg-gray-100 p-8">
      <!-- En-tête -->
      <div class="mb-8 flex justify-between items-center">
        <div class="flex items-center space-x-2">
          <Link :href="route('course.manage')" class="flex items-center text-blue-600 hover:text-blue-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7" />
            </svg>
            Retour
          </Link>
          <h1 class="text-3xl font-bold text-gray-800">Modifier le Cours</h1>
        </div>
      </div>
  
      <!-- Formulaire -->
      <div class="bg-white p-8 rounded-2xl shadow-md max-w-3xl mx-auto">
        <form @submit.prevent="updateCourse" class="space-y-6" enctype="multipart/form-data">
          <div>
            <label class="block text-gray-700 font-medium mb-2">Titre du Cours</label>
            <input v-model="course.title" type="text"
                   class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                   required />
          </div>
  
          <div>
            <label class="block text-gray-700 font-medium mb-2">Description du Cours</label>
            <textarea v-model="course.description" rows="4"
                      class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                      required></textarea>
          </div>
  
          <div>
            <label class="block text-gray-700 font-medium mb-2">Image de Couverture</label>
            <input type="file" @change="handleFileUpload" class="block w-full text-gray-600" accept="image/*" />
            <div v-if="course.photo_path_course && !newPhoto" class="mt-2 text-sm text-gray-600">
              Image actuelle : {{ course.photo_path_course }}
            </div>
          </div>
  
          <div>
            <label class="block text-gray-700 font-medium mb-2">Instructeur</label>
            <input type="text" v-model="searchQuery"
                   class="w-full border border-gray-300 rounded-lg p-3 mb-2 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                   placeholder="Chercher un instructeur..." />
            <div class="max-h-40 overflow-auto border rounded-lg">
              <div v-for="instructor in filteredInstructors" :key="instructor.id"
                   @click="selectInstructor(instructor)"
                   :class="['p-2 cursor-pointer', instructor.id === course.instructor_id ? 'bg-blue-100' : 'hover:bg-gray-100']">
                {{ instructor.name }}
              </div>
            </div>
            <div v-if="course.instructor_id" class="mt-2 text-sm text-green-600">
              Instructeur sélectionné : {{ selectedInstructorName }}
            </div>
          </div>
  
          <div class="flex justify-end space-x-4">
            <button type="submit" class="px-6 py-2 bg-blue-600 rounded-full text-white hover:bg-blue-700 transition">
              Mettre à jour
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
  
  const props = defineProps({
    courseData: Object,
    instructors: Array
  })
  
  const course = ref({ ...props.courseData })
  const searchQuery = ref('')
  const newPhoto = ref(null)
  
  const filteredInstructors = computed(() => {
    if (!searchQuery.value) return props.instructors
    return props.instructors.filter(i =>
      i.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  })
  
  const selectedInstructorName = computed(() => {
    const selected = props.instructors.find(i => i.id === course.value.instructor_id)
    return selected ? selected.name : ''
  })
  
  function selectInstructor(instructor) {
    course.value.instructor_id = instructor.id
  }
  
  function handleFileUpload(event) {
    newPhoto.value = event.target.files[0]
  }
  
  function updateCourse() {
    const formData = new FormData()
    formData.append('title', course.value.title)
    formData.append('description', course.value.description)
    if (newPhoto.value) {
      formData.append('photo_path_course', newPhoto.value)
    }
    formData.append('instructor_id', course.value.instructor_id)
    formData.append('_method', 'PUT')
  
    router.post(route('course.update', course.value.id), formData, {
      onSuccess: () => {
        Toastify({
          text: "Modification réussie !",
          duration: 3000,
          gravity: "top",
          position: "right",
          style: { background: "#4CAF50" }
        }).showToast()
        newPhoto.value = null
      }
    })
  }
  </script>
  
  