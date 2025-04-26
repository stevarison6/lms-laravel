<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { debounce } from 'lodash';
import {Link } from '@inertiajs/vue3';

const props = defineProps({
  courses: Object,
  filters: Object
});

const search = ref(props.filters.search || '');

watch(search, debounce((value) => {
  router.get(route('cours.liste'), { search: value }, { preserveState: true, replace: true });
}, 300));
</script>

<template>
  <DashboardLayout>
    <template #content>
      <div class="bg-white rounded-2xl shadow p-6 text-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Tous les Cours</h1>
        <p class="text-gray-600">Explorez et commencez une nouvelle formation dès aujourd'hui !</p>
      </div>

      <!-- Barre de recherche -->
      <div class="flex justify-center mb-8">
        <input
          v-model="search"
          type="text"
          placeholder="Rechercher un cours..."
          class="w-full md:w-1/3 px-4 py-2 rounded-2xl border shadow focus:ring focus:ring-blue-200 focus:outline-none"
        />
      </div>

      <!-- Grille des cours -->
      <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
        <div
          v-for="course in courses.data"
          :key="course.id"
          class="bg-white rounded-2xl shadow hover:shadow-lg transition flex flex-col"
        >
          <img :src="course.photo" alt="Image du cours" class="rounded-t-2xl object-cover h-40 w-full">
          <div class="p-5 flex-1 flex flex-col justify-between">
            <div>
              <h2 class="text-lg font-semibold text-gray-800 mb-2">{{ course.title }}</h2>
              <p class="text-gray-600 mb-4">{{ course.description }}</p>
            </div>
            <div class="flex justify-between items-center mt-auto">
              <span
                :class="[
                  'text-xs px-3 py-1 rounded-full font-semibold',
                  course.enrolled ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600'
                ]"
              >
                {{ course.enrolled ? 'En cours' : 'Non commencé' }}
              </span>
              <Link
                :href="course.enrolled
                  ? `/lesson/${course.last_lesson_id}`
                  : `/subscribe/${course.id}`"
                class="bg-blue-500 text-white px-4 py-2 rounded-full shadow hover:bg-blue-600 transition"
              >
                {{ course.enrolled ? 'Continuer' : 'Commencer' }}
            </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div class="flex justify-center mt-8">
        <button
          v-if="courses.prev_page_url"
          @click="router.get(courses.prev_page_url)"
          class="mx-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-gray-300 transition"
        >
          Précédent
        </button>
        <button
          v-if="courses.next_page_url"
          @click="router.get(courses.next_page_url)"
          class="mx-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-gray-300 transition"
        >
          Suivant
        </button>
      </div>
    </template>
  </DashboardLayout>
</template>
