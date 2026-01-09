<script setup lang="ts">
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { store } from '@/routes/login';
import { request } from '@/routes/password';
import { Form, Head } from '@inertiajs/vue3';
import {register} from "@/routes";

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();

const showPassword = ref(false);
</script>

<template>
    <div class="min-h-screen flex">
        <Head title="Sign In - DentalLab Pro" />

        <!-- Left Side - Brand & Visual -->
        <div class="hidden lg:flex lg:w-[45%] bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 relative overflow-hidden">
            <!-- Animated background elements -->
            <div class="absolute inset-0">
                <div class="absolute top-0 left-0 w-96 h-96 bg-blue-500/30 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
                <div class="absolute top-0 right-0 w-96 h-96 bg-cyan-500/30 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"></div>
                <div class="absolute bottom-0 left-1/2 w-96 h-96 bg-indigo-500/30 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000"></div>
            </div>

            <!-- Grid pattern overlay -->
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 40px 40px;"></div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-between p-16 text-white w-full">
                <!-- Logo -->
                <div>
                    <div class="inline-flex items-center gap-3 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-2xl border border-white/20">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold">DentalLab Pro</h1>
                        </div>
                    </div>
                </div>

                <!-- Main content -->
                <div class="max-w-lg">
                    <h2 class="text-5xl font-bold mb-6 leading-tight">
                        Precision. <br/>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-400">
                            Excellence.
                        </span> <br/>
                        Delivered.
                    </h2>
                    <p class="text-xl text-blue-100 mb-12 leading-relaxed">
                        Transform your dental laboratory workflow with intelligent case management, seamless collaboration, and real-time insights.
                    </p>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-8">
                        <div>
                            <div class="text-3xl font-bold text-cyan-400">500+</div>
                            <div class="text-sm text-blue-200 mt-1">Labs Trust Us</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-cyan-400">50k+</div>
                            <div class="text-sm text-blue-200 mt-1">Cases Managed</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-cyan-400">98%</div>
                            <div class="text-sm text-blue-200 mt-1">Satisfaction</div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-sm text-blue-300">
                    © 2024 DentalLab Pro. All rights reserved.
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="flex-1 flex items-center justify-center bg-gray-50 px-6 py-12">
            <div class="w-full max-w-md">
                <!-- Mobile logo -->
                <div class="lg:hidden mb-8 text-center">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </div>
                        <span class="font-bold text-gray-900">DentalLab Pro</span>
                    </div>
                </div>

                <!-- Card -->
                <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 p-10">
                    <!-- Header -->
                    <div class="text-center mb-10">
                        <h2 class="text-3xl font-bold text-gray-900 mb-3">Welcome Back</h2>
                        <p class="text-gray-600">Enter your credentials to access your dashboard</p>
                    </div>

                    <!-- Status Alert -->
                    <div
                        v-if="status"
                        class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-lg"
                    >
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <p class="text-sm font-medium text-emerald-800">{{ status }}</p>
                        </div>
                    </div>

                    <!-- Form -->
                    <Form
                        v-bind="store.form()"
                        :reset-on-success="['password']"
                        v-slot="{ errors, processing }"
                    >
                        <div class="space-y-5">
                            <!-- Email -->
                            <div>
                                <Label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email Address
                                </Label>
                                <div class="relative">
                                    <Input
                                        id="email"
                                        type="email"
                                        name="email"
                                        required
                                        autofocus
                                        :tabindex="1"
                                        autocomplete="email"
                                        placeholder="you@company.com"
                                        class="h-12 pl-12 text-base border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl"
                                    />
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                        </svg>
                                    </div>
                                </div>
                                <InputError :message="errors.email" class="mt-2" />
                            </div>

                            <!-- Password -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <Label for="password" class="block text-sm font-semibold text-gray-700">
                                        Password
                                    </Label>
                                    <TextLink
                                        v-if="canResetPassword"
                                        :href="request()"
                                        class="text-sm font-medium text-blue-600 hover:text-blue-700"
                                        :tabindex="5"
                                    >
                                        Forgot?
                                    </TextLink>
                                </div>
                                <div class="relative">
                                    <Input
                                        id="password"
                                        :type="showPassword ? 'text' : 'password'"
                                        name="password"
                                        required
                                        :tabindex="2"
                                        autocomplete="current-password"
                                        placeholder="Enter your password"
                                        class="h-12 pl-12 pr-12 text-base border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl"
                                    />
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <button
                                        type="button"
                                        @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                                        :tabindex="6"
                                    >
                                        <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                                <InputError :message="errors.password" class="mt-2" />
                            </div>

                            <!-- Remember Me -->
                            <div class="flex items-center pt-2">
                                <Checkbox
                                    id="remember"
                                    name="remember"
                                    :tabindex="3"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                />
                                <Label for="remember" class="ml-3 text-sm text-gray-700 cursor-pointer">
                                    Keep me signed in
                                </Label>
                            </div>

                            <!-- Submit -->
                            <Button
                                type="submit"
                                class="w-full h-12 text-base font-semibold bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200"
                                :tabindex="4"
                                :disabled="processing"
                            >
                                <Spinner v-if="processing" class="mr-2 w-5 h-5" />
                                <span v-if="!processing">Sign In</span>
                                <span v-else>Signing in...</span>
                            </Button>
                        </div>
                    </Form>

                    <!-- Divider -->
                    <div class="relative my-8">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500 font-medium">Demo Accounts</span>
                        </div>
                    </div>

                    <!-- Register Link -->
                    <div v-if="canRegister" class="mt-8 text-center">
                        <p class="text-sm text-gray-600">
                            Don't have an account?
                            <TextLink
                                :href="register()"
                                class="font-semibold text-blue-600 hover:text-blue-700"
                                :tabindex="7"
                            >
                                Create one now
                            </TextLink>
                        </p>
                    </div>
                </div>

                <!-- Security Badge -->
                <div class="mt-6 flex items-center justify-center gap-2 text-xs text-gray-500">
                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>Secured with 256-bit SSL encryption</span>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes blob {
    0% {
        transform: translate(0px, 0px) scale(1);
    }
    33% {
        transform: translate(30px, -50px) scale(1.1);
    }
    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }
    100% {
        transform: translate(0px, 0px) scale(1);
    }
}

.animate-blob {
    animation: blob 7s infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}
</style>
