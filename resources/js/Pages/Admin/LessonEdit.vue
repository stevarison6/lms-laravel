<template>
    <div class="min-h-screen bg-gray-100 p-8">
      <div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-md">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-gray-800">Modifier la Leçon</h1>
          <Link :href="route('lesson.manage')" class="text-blue-600 hover:underline text-sm">
            ← Retour à la gestion des leçons
          </Link>
        </div>
  
        <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-6">
          <div>
            <label class="block text-gray-700 font-medium mb-2">Titre de la leçon</label>
            <input v-model="form.title" type="text"
              class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
              :class="{'border-red-500': errors.title}" />
            <p v-if="errors.title" class="text-red-500 text-sm mt-1">{{ errors.title }}</p>
          </div>
  
          <div>
            <label class="block text-gray-700 font-medium mb-2">Cours associé</label>
            <input
              type="text"
              placeholder="🔍 Rechercher un cours..."
              v-model="courseSearch"
              class="w-full border mb-2 p-3 rounded-lg"
            />
            <select v-model="form.course_id"
              class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
              :class="{'border-red-500': errors.course_id}">
              <option value="">-- Sélectionner un cours --</option>
              <option
                v-for="course in filteredCourses"
                :key="course.id"
                :value="course.id"
              >{{ course.title }}</option>
            </select>
            <p v-if="errors.course_id" class="text-red-500 text-sm mt-1">{{ errors.course_id }}</p>
          </div>
  
          <div>
            <label class="block text-gray-700 font-medium mb-2">Contenu</label>
            <textarea v-model="form.content"
              class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
              rows="4"></textarea>
          </div>
  
          <div>
            <label class="block text-gray-700 font-medium mb-2">Vidéo (laisser vide pour ne pas modifier)</label>
            <input type="file" @change="handleVideoUpload" accept="video/*"
              class="block w-full text-gray-600" />
            <p v-if="errors.video_url" class="text-red-500 text-sm mt-1">{{ errors.video_url }}</p>
          </div>
  
          <div class="flex justify-end">
            <button type="submit"
              class="px-6 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition">
              Enregistrer
            </button>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue'
  import { router, Link } from '@inertiajs/vue3'
  import Swal from 'sweetalert2'
  
  const props = defineProps({
    lesson: Object,
    courses: Array,
  })
  
  const form = ref({
    title: props.lesson.title,
    content: props.lesson.content,
    course_id: props.lesson.course_id,
  })
  
  const courseSearch = ref('')
  const videoFile = ref(null)
  const errors = ref({})
  
  const filteredCourses = computed(() => {
    return props.courses.filter(course =>
      course.title.toLowerCase().includes(courseSearch.value.toLowerCase())
    )
  })
  
  function handleVideoUpload(event) {
    videoFile.value = event.target.files[0]
  }
  
  function submit() {
    errors.value = {}
  
    const formData = new FormData()
    formData.append('title', form.value.title)
    formData.append('content', form.value.content)
    formData.append('course_id', form.value.course_id)
    if (videoFile.value) {
      formData.append('video_url', videoFile.value)
    }
    formData.append('_method', 'PUT')
  
    router.post(route('lesson.update', props.lesson.id), formData, {
      preserveScroll: true,
      onError: (err) => { errors.value = err },
      onSuccess: () => {
        Swal.fire({
          title: 'Succès',
          text: 'Leçon mise à jour avec succès !',
          icon: 'success',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK'
        })
      }
    })
  }
  </script>
  