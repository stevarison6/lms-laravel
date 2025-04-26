<script setup>
import { AcademicCapIcon, CheckCircleIcon, ChartBarIcon,ClockIcon } from '@heroicons/vue/24/solid';
import {Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';


const page = usePage();
const countCourse=page.props.countCourse;
const avgProgress = page.props.averageProgress;
const completedCourses = page.props.countCompletedCourses;
const courses = computed(() => page.props.courses);
</script>

<template>
  <DashboardLayout>
    <template #content>
    <div class="p-8 space-y-8 flex-1">
      <!-- Bienvenue -->
      <div class="bg-white rounded-2xl shadow p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Bonjour, {{$page.props.auth.user.name }} 👋</h1>
        <p class="text-gray-600">Bienvenue sur votre espace d'apprentissage. Continuez vos progrès ou explorez de nouveaux cours !</p>
      </div>

      <!-- Résumé Rapide -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-5 rounded-2xl shadow flex items-center justify-between">
          <div>
            <h2 class="text-lg text-gray-700 font-semibold">Total Cours</h2>
            <p class="text-2xl font-bold text-blue-600">{{ countCourse[0].enrolled_courses_count}}</p>
          </div>
          <AcademicCapIcon class="w-10 h-10 text-blue-400" />
        </div>
        <div class="bg-white p-5 rounded-2xl shadow flex items-center justify-between">
          <div>
            <h2 class="text-lg text-gray-700 font-semibold">Cours Terminés</h2>
            <p class="text-2xl font-bold text-green-600">{{ completedCourses }}</p>
          </div>
          <CheckCircleIcon class="w-10 h-10 text-green-400" />
        </div>
        <div class="bg-white p-5 rounded-2xl shadow flex items-center justify-between">
          <div>
            <h2 class="text-lg text-gray-700 font-semibold">Progrès Moyen</h2>
            <p class="text-2xl font-bold text-yellow-600">{{ avgProgress }}%</p>
          </div>
          <ChartBarIcon class="w-10 h-10 text-yellow-400" />
        </div>
      </div>

      <!-- Bouton Tous les Cours -->
      <div class="flex justify-end">
        <Link :href="route('cours.liste')" class="bg-green-500 text-white px-6 py-2 rounded-full shadow hover:bg-green-600 transition">
          Voir Tous les Cours
        </Link>
      </div>

      <!-- Tableau des Cours Actuels -->
      <div class="bg-white p-6 rounded-2xl shadow">
  <h2 class="text-2xl font-bold text-gray-800 mb-6">Mes Cours Actuels</h2>
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Cours</th>
          <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Progrès</th>
          <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Statut</th>
          <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Action</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-100">
        <tr v-for="course in courses" :key="course.id" class="hover:bg-gray-50 transition">
          <td class="px-6 py-4 font-medium text-gray-800">{{ course.title }}</td>

          <td class="px-6 py-4 text-gray-600 w-48">
            <div class="flex items-center space-x-2">
              <span class="text-sm font-semibold">{{ course.progress_percentage }}%</span>
              <div class="w-full bg-gray-300 rounded-full h-2 overflow-hidden">
                <div
                  class="bg-green-500 h-2 rounded-full transition-all duration-500 ease-in-out"
                  :style="{ width: course.progress_percentage + '%' }"
                ></div>
              </div>
            </div>
          </td>
          <td class="px-6 py-4">
            <span
              :class="[
                'inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold',
                course.completed_lessons_count === course.lessons_count
                  ? 'bg-green-100 text-green-700'
                  : 'bg-yellow-100 text-yellow-700'
              ]"
            >
              <component
                :is="course.completed_lessons_count === course.lessons_count ? CheckCircleIcon : ClockIcon"
                class="h-4 w-4"
              />
              {{ course.completed_lessons_count === course.lessons_count ? 'Finished' : 'En cours' }}
            </span>
          </td>

          <td class="px-6 py-4">
            <Link
              :href="route('cours.show',course.id)" 
              class="inline-block bg-blue-600 text-white px-4 py-2 rounded-full shadow hover:bg-blue-700 transition"
            >
              Continuer
            </Link>
          </td>
        </tr>
        <tr v-if="courses.length === 0">
          <td colspan="4" class="px-6 py-4 text-center text-gray-500">Aucun cours en cours.</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

    </div>
  <!-- </div> -->
   </template>
  </DashboardLayout>
</template>


