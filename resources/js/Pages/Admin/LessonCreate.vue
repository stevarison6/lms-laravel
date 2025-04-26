<template> 
    <div class="min-h-screen bg-gray-100 p-8">
      <div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-md">
        <!-- Lien retour vers la gestion des leçons -->
        <div class="mb-4">
          <Link 
            :href="route('lesson.manage')" 
            class="text-blue-600 hover:text-blue-800">
            ← Retour à la gestion des leçons
          </Link>
        </div>
  
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Créer une Nouvelle Leçon</h1>
        
        <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-6">
          <div>
            <label class="block text-gray-700 font-medium mb-2">Titre de la leçon</label>
            <input v-model="form.title" type="text"
              class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
              :class="{'border-red-500': errors.title}" placeholder="Entrez le titre">
            <p v-if="errors.title" class="text-red-500 text-sm mt-1">{{ errors.title }}</p>
          </div>
  
          <div>
            <label class="block text-gray-700 font-medium mb-2">Contenu de la leçon</label>
            <textarea v-model="form.content"
              class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
              rows="4" placeholder="Description ou notes..."></textarea>
          </div>
  
          <div>
            <label class="block text-gray-700 font-medium mb-2">Sélectionner un Cours</label>
            <input v-model="searchQuery" type="text"
              class="w-full border p-3 rounded-lg mb-2 focus:ring-2 focus:ring-blue-400 focus:outline-none"
              placeholder="Rechercher un cours..." />
            <div class="max-h-40 overflow-auto border rounded-lg">
              <div v-for="course in filteredCourses" :key="course.id"
                @click="selectCourse(course)"
                :class="['p-2 cursor-pointer', form.course_id === course.id ? 'bg-blue-100' : 'hover:bg-gray-100']">
                {{ course.title }}
              </div>
            </div>
            <p v-if="errors.course_id" class="text-red-500 text-sm mt-1">{{ errors.course_id }}</p>
          </div>
  
          <div>
            <label class="block text-gray-700 font-medium mb-2">Vidéo de la Leçon</label>
            <input type="file" @change="handleVideoUpload" accept="video/*"
              class="block w-full text-gray-600" />
            <p v-if="errors.video_url" class="text-red-500 text-sm mt-1">{{ errors.video_url }}</p>
          </div>
  
          <div class="flex justify-end">
            <button type="submit"
              class="px-6 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition">
              Enregistrer la Leçon
            </button>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue';
  import { Link } from '@inertiajs/vue3';  // Importer le composant Link
  import Toastify from 'toastify-js';
  import "toastify-js/src/toastify.css";
  
  const props = defineProps({ courses: Array });
  const searchQuery = ref('');
  const videoFile = ref(null);
  
  const form = ref({
    title: '',
    content: '',
    course_id: null,
  });
  
  const errors = ref({});
  
  const filteredCourses = computed(() => {
    return !searchQuery.value
      ? props.courses
      : props.courses.filter(c => c.title.toLowerCase().includes(searchQuery.value.toLowerCase()));
  });
  
  function selectCourse(course) {
    form.value.course_id = course.id;
  }
  
  function handleVideoUpload(event) {
    videoFile.value = event.target.files[0];
  }
  
  function submit() {
    errors.value = {};
    if (!form.value.title) errors.value.title = "Le titre est obligatoire.";
    if (!form.value.course_id) errors.value.course_id = "Veuillez sélectionner un cours.";
    if (!videoFile.value) errors.value.video_url = "Veuillez télécharger une vidéo.";
  
    if (Object.keys(errors.value).length > 0) return;
  
    const formData = new FormData();
    formData.append('title', form.value.title);
    formData.append('content', form.value.content);
    formData.append('course_id', form.value.course_id);
    formData.append('video_url', videoFile.value);
  
    router.post(route('lesson.store'), formData, {
      preserveScroll: true,
      onError: (err) => { errors.value = err; },
      onSuccess: () => {
        form.value = { title: '', content: '', course_id: null };
        videoFile.value = null;
        Toastify({
          text: "Leçon créée avec succès !",
          duration: 3000,
          close: true,
          gravity: "top",
          position: "right",
          backgroundColor: "#10B981",
        }).showToast();
      }
    });
  }
  </script>
  