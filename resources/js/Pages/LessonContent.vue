<template>
  <div class="min-h-screen bg-gray-100 p-8 space-y-8">

    <div class="bg-white p-6 rounded-2xl shadow text-center">
      <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ lesson.title }}</h1>
      <p class="text-gray-600">{{ lesson.description }}</p>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow space-y-6">
      <!-- Player vidéo HTML5 -->
      <div v-if="lesson.videoUrl" class="w-full max-w-4xl mx-auto rounded-lg overflow-hidden">
        <video
          class="w-full rounded-lg"
          controls
          :src="lesson.videoUrl"
        >
          Votre navigateur ne supporte pas la lecture vidéo.
        </video>
      </div>

      <!-- Texte explicatif -->
      <div class="text-gray-700 leading-relaxed">
        <p v-for="(paragraph, index) in lesson.content" :key="index" class="mb-4">
          {{ paragraph }}
        </p>
      </div>
    </div>

    <div class="flex justify-between items-center mt-6">
      <Link :href="route('cours.show',lesson.course_id)" class="text-blue-600 hover:underline">Retour au cours</Link>

      <div class="flex space-x-4">
        <button @click="navigate('prev')" class="px-6 py-2 bg-gray-500 text-white rounded-full shadow hover:bg-gray-600 transition">
          Précédent
        </button>
        <button @click="navigate('next')" class="px-6 py-2 bg-blue-600 text-white rounded-full shadow hover:bg-blue-700 transition">
          Suivant
        </button>
      </div>

      <button @click="markAsCompleted"
        class="px-6 py-3 bg-green-600 text-white rounded-full shadow hover:bg-green-700 transition">
        {{ lesson.completed ? 'Revoir la leçon' : 'Marquer comme terminé' }}
      </button>
    </div>

  </div>
</template>

<script setup>
import { router,Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import Swal from 'sweetalert2';
import Toastify from 'toastify-js';
import "toastify-js/src/toastify.css";

const props = defineProps({
  lesson: Object
});

function markAsCompleted() {
  if (!props.lesson.completed) {
    Swal.fire({
      title: 'Êtes-vous sûr ?',
      text: "Voulez-vous marquer cette leçon comme terminée ?",
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#10B981',
      cancelButtonColor: '#EF4444',
      confirmButtonText: 'Oui, marquer !'
    }).then((result) => {
      if (result.isConfirmed) {
        router.post(`/lesson/${props.lesson.id}/complete`, {}, {
          onSuccess: () => {
            Toastify({
              text: "✅ Leçon marquée comme terminée !",
              duration: 3000,
              gravity: "top",
              position: "right",
              backgroundColor: "#10B981",
            }).showToast();
          }
        });
      }
    });
  } else {
    Swal.fire({
      icon: 'info',
      title: 'Déjà terminée',
      text: 'Vous avez déjà terminé cette leçon.',
      confirmButtonText: 'OK'
    });
  }
}

function navigate(direction) {
  router.get(`/lesson/${props.lesson.id}/navigate/${direction}`);
}
</script>

