<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

    <div class="min-h-screen flex">
        <!-- Left Section -->
        <div class="w-1/2 bg-gradient-to-br from-blue-700 via-blue-500 to-blue-300 flex items-center justify-center">
            <h1 class="text-5xl text-white font-bold">Welcome Back!</h1>
        </div>

        <!-- Right Section -->
        <div class="w-1/2 flex flex-col justify-center px-16 shadow-lg bg-white">
            <h2 class="text-3xl font-bold mb-2 text-blue-700">Login</h2>
            <p class="mb-6 text-gray-500">Welcome back! Please login to your account.</p>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <InputLabel for="email" value="User Name" class="text-gray-700" />
                    <TextInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div>
                    <InputLabel for="password" value="Password" class="text-gray-700" />
                    <TextInput
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                        required
                        autocomplete="current-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <Checkbox v-model:checked="form.remember" name="remember" />
                        <span class="ms-2 text-sm text-gray-600">Remember Me</span>
                    </label>
                    <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm text-blue-600 hover:underline">
                        Forgot Password?
                    </Link>
                </div>

                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                               class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition ease-in-out duration-150">
                    Login
                </PrimaryButton>

                <p class="mt-4 text-center text-sm text-gray-600">
                    New User? 
                    <Link :href="route('register')" class="text-blue-600 hover:underline">Signup</Link>
                </p>
            </form>
        </div>
    </div>
</template>
