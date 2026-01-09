<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
import { Check, ArrowRight, Zap, Shield, BarChart3, Users, Package, Clock } from 'lucide-vue-next';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head title="DentalLab Pro - Professional Laboratory Management">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-cyan-50 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
        <!-- Navigation -->
        <nav class="relative z-50 border-b border-slate-200/50 bg-white/80 backdrop-blur-xl dark:border-slate-800/50 dark:bg-slate-900/80">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <!-- Logo -->
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 shadow-lg shadow-blue-500/30">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent dark:from-blue-400 dark:to-cyan-400">
                            DentalLab Pro
                        </span>
                    </div>

                    <!-- Auth Links -->
                    <div class="flex items-center gap-4">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="dashboard()"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-6 py-2.5 text-sm font-semibold text-slate-900 shadow-sm hover:bg-slate-50 transition-all dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700"
                        >
                            Dashboard
                            <ArrowRight class="h-4 w-4" />
                        </Link>
                        <template v-else>
                            <Link
                                :href="login()"
                                class="inline-flex items-center gap-2 rounded-xl px-6 py-2.5 text-sm font-semibold text-slate-700 hover:text-slate-900 transition-colors dark:text-slate-300 dark:hover:text-white"
                            >
                                Sign In
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="register()"
                                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-cyan-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 transition-all"
                            >
                                Get Started
                                <ArrowRight class="h-4 w-4" />
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative overflow-hidden px-6 py-24 lg:px-8 lg:py-32">
            <!-- Background decoration -->
            <div class="absolute inset-0">
                <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-500/10 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
                <div class="absolute top-0 right-1/4 w-96 h-96 bg-cyan-500/10 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"></div>
                <div class="absolute bottom-0 left-1/2 w-96 h-96 bg-indigo-500/10 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000"></div>
            </div>

            <div class="relative mx-auto max-w-7xl">
                <div class="grid gap-16 lg:grid-cols-2 lg:gap-24 items-center">
                    <!-- Left Column - Content -->
                    <div class="space-y-8">
                        <div class="inline-flex items-center gap-2 rounded-full bg-blue-500/10 px-4 py-2 text-sm font-semibold text-blue-700 dark:bg-blue-500/20 dark:text-blue-300">
                            <Zap class="h-4 w-4" />
                            AI-Powered Laboratory Management
                        </div>

                        <h1 class="text-5xl font-bold tracking-tight text-slate-900 sm:text-6xl lg:text-7xl dark:text-white">
                            Transform Your
                            <span class="block mt-2 bg-gradient-to-r from-blue-600 via-cyan-600 to-blue-600 bg-clip-text text-transparent">
                                Dental Laboratory
                            </span>
                        </h1>

                        <p class="text-xl leading-relaxed text-slate-600 dark:text-slate-300">
                            Streamline workflows, track production in real-time, and deliver exceptional results faster with our intelligent lab management platform.
                        </p>

                        <div class="flex flex-col gap-4 sm:flex-row">
                            <Link
                                :href="register()"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-cyan-600 px-8 py-4 text-base font-semibold text-white shadow-xl shadow-blue-500/30 hover:shadow-2xl hover:shadow-blue-500/40 transition-all"
                            >
                                Start Free Trial
                                <ArrowRight class="h-5 w-5" />
                            </Link>
                            <Link
                                :href="login()"
                                class="inline-flex items-center justify-center gap-2 rounded-xl border-2 border-slate-300 bg-white px-8 py-4 text-base font-semibold text-slate-900 hover:bg-slate-50 transition-all dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700"
                            >
                                Watch Demo
                            </Link>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-3 gap-8 pt-8 border-t border-slate-200 dark:border-slate-800">
                            <div>
                                <div class="text-3xl font-bold text-slate-900 dark:text-white">500+</div>
                                <div class="text-sm text-slate-600 dark:text-slate-400">Labs Worldwide</div>
                            </div>
                            <div>
                                <div class="text-3xl font-bold text-slate-900 dark:text-white">50k+</div>
                                <div class="text-sm text-slate-600 dark:text-slate-400">Cases Managed</div>
                            </div>
                            <div>
                                <div class="text-3xl font-bold text-slate-900 dark:text-white">98%</div>
                                <div class="text-sm text-slate-600 dark:text-slate-400">Satisfaction</div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Visual -->
                    <div class="relative">
                        <div class="relative rounded-3xl bg-gradient-to-br from-blue-600 via-cyan-600 to-blue-700 p-1 shadow-2xl">
                            <div class="rounded-3xl bg-white p-8 dark:bg-slate-900">
                                <div class="space-y-6">
                                    <!-- Mock Dashboard -->
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500"></div>
                                        <div class="flex-1">
                                            <div class="h-3 w-32 rounded bg-slate-200 dark:bg-slate-700"></div>
                                            <div class="mt-2 h-2 w-24 rounded bg-slate-100 dark:bg-slate-800"></div>
                                        </div>
                                    </div>

                                    <div class="space-y-3">
                                        <div class="flex items-center gap-3 rounded-xl bg-blue-50 p-4 dark:bg-blue-950/30">
                                            <Check class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                                            <div class="flex-1">
                                                <div class="h-2 w-40 rounded bg-blue-200 dark:bg-blue-800"></div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 rounded-xl bg-slate-50 p-4 dark:bg-slate-800">
                                            <div class="h-5 w-5 rounded bg-slate-200 dark:bg-slate-700"></div>
                                            <div class="flex-1">
                                                <div class="h-2 w-32 rounded bg-slate-200 dark:bg-slate-700"></div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 rounded-xl bg-slate-50 p-4 dark:bg-slate-800">
                                            <div class="h-5 w-5 rounded bg-slate-200 dark:bg-slate-700"></div>
                                            <div class="flex-1">
                                                <div class="h-2 w-36 rounded bg-slate-200 dark:bg-slate-700"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="rounded-xl bg-gradient-to-br from-blue-50 to-cyan-50 p-4 dark:from-blue-950/30 dark:to-cyan-950/30">
                                            <div class="h-2 w-16 rounded bg-blue-300 dark:bg-blue-700"></div>
                                            <div class="mt-4 h-16 rounded bg-blue-200/50 dark:bg-blue-800/50"></div>
                                        </div>
                                        <div class="rounded-xl bg-gradient-to-br from-cyan-50 to-blue-50 p-4 dark:from-cyan-950/30 dark:to-blue-950/30">
                                            <div class="h-2 w-20 rounded bg-cyan-300 dark:bg-cyan-700"></div>
                                            <div class="mt-4 h-16 rounded bg-cyan-200/50 dark:bg-cyan-800/50"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Floating badges -->
                        <div class="absolute -top-6 -right-6 rounded-2xl bg-white p-4 shadow-xl dark:bg-slate-800">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-500">
                                    <Check class="h-6 w-6 text-white" />
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-slate-900 dark:text-white">24 Cases</div>
                                    <div class="text-xs text-slate-600 dark:text-slate-400">Completed Today</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="relative px-6 py-24 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-slate-900 dark:text-white mb-4">
                        Everything You Need to Run Your Lab
                    </h2>
                    <p class="text-xl text-slate-600 dark:text-slate-300">
                        Powerful features designed for modern dental laboratories
                    </p>
                </div>

                <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Feature 1 -->
                    <div class="group relative rounded-2xl bg-white p-8 shadow-lg hover:shadow-2xl transition-all dark:bg-slate-900">
                        <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                            <Package class="h-7 w-7 text-white" />
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3 dark:text-white">Order Management</h3>
                        <p class="text-slate-600 dark:text-slate-400">Track cases from intake to delivery with real-time status updates and automated workflows.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="group relative rounded-2xl bg-white p-8 shadow-lg hover:shadow-2xl transition-all dark:bg-slate-900">
                        <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform">
                            <Users class="h-7 w-7 text-white" />
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3 dark:text-white">Team Collaboration</h3>
                        <p class="text-slate-600 dark:text-slate-400">Coordinate technicians, assign tasks, and monitor workload across your entire team.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="group relative rounded-2xl bg-white p-8 shadow-lg hover:shadow-2xl transition-all dark:bg-slate-900">
                        <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-green-500 to-emerald-500 shadow-lg shadow-green-500/30 group-hover:scale-110 transition-transform">
                            <BarChart3 class="h-7 w-7 text-white" />
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3 dark:text-white">Analytics & Reports</h3>
                        <p class="text-slate-600 dark:text-slate-400">Gain insights into performance, revenue, and turnaround times with detailed analytics.</p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="group relative rounded-2xl bg-white p-8 shadow-lg hover:shadow-2xl transition-all dark:bg-slate-900">
                        <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform">
                            <Clock class="h-7 w-7 text-white" />
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3 dark:text-white">Time Tracking</h3>
                        <p class="text-slate-600 dark:text-slate-400">Monitor production time and optimize workflows for maximum efficiency.</p>
                    </div>

                    <!-- Feature 5 -->
                    <div class="group relative rounded-2xl bg-white p-8 shadow-lg hover:shadow-2xl transition-all dark:bg-slate-900">
                        <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-red-500 to-pink-500 shadow-lg shadow-red-500/30 group-hover:scale-110 transition-transform">
                            <Shield class="h-7 w-7 text-white" />
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3 dark:text-white">Secure & Compliant</h3>
                        <p class="text-slate-600 dark:text-slate-400">Bank-level security with full data encryption and HIPAA compliance.</p>
                    </div>

                    <!-- Feature 6 -->
                    <div class="group relative rounded-2xl bg-white p-8 shadow-lg hover:shadow-2xl transition-all dark:bg-slate-900">
                        <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-cyan-500 to-blue-500 shadow-lg shadow-cyan-500/30 group-hover:scale-110 transition-transform">
                            <Zap class="h-7 w-7 text-white" />
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3 dark:text-white">AI-Powered</h3>
                        <p class="text-slate-600 dark:text-slate-400">Intelligent automation and smart recommendations to streamline your workflow.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="relative px-6 py-24 lg:px-8">
            <div class="mx-auto max-w-4xl">
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-600 via-cyan-600 to-blue-700 p-12 shadow-2xl">
                    <div class="relative z-10 text-center">
                        <h2 class="text-4xl font-bold text-white mb-4">
                            Ready to Transform Your Laboratory?
                        </h2>
                        <p class="text-xl text-blue-100 mb-8">
                            Join hundreds of dental labs already using DentalLab Pro
                        </p>
                        <div class="flex flex-col gap-4 sm:flex-row sm:justify-center">
                            <Link
                                :href="register()"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-8 py-4 text-base font-semibold text-blue-600 shadow-xl hover:shadow-2xl transition-all"
                            >
                                Start Free Trial
                                <ArrowRight class="h-5 w-5" />
                            </Link>
                            <Link
                                :href="login()"
                                class="inline-flex items-center justify-center gap-2 rounded-xl border-2 border-white/30 bg-white/10 backdrop-blur px-8 py-4 text-base font-semibold text-white hover:bg-white/20 transition-all"
                            >
                                Contact Sales
                            </Link>
                        </div>
                    </div>

                    <!-- Decorative elements -->
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-64 h-64 bg-cyan-400/10 rounded-full blur-3xl"></div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-slate-200 bg-white/50 backdrop-blur px-6 py-12 dark:border-slate-800 dark:bg-slate-900/50">
            <div class="mx-auto max-w-7xl">
                <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </div>
                        <span class="text-sm text-slate-600 dark:text-slate-400">
                            © 2024 DentalLab Pro. All rights reserved.
                        </span>
                    </div>
                    <div class="flex gap-6 text-sm text-slate-600 dark:text-slate-400">
                        <a href="#" class="hover:text-slate-900 dark:hover:text-white transition-colors">Privacy</a>
                        <a href="#" class="hover:text-slate-900 dark:hover:text-white transition-colors">Terms</a>
                        <a href="#" class="hover:text-slate-900 dark:hover:text-white transition-colors">Support</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
@keyframes blob {
    0%, 100% {
        transform: translate(0px, 0px) scale(1);
    }
    33% {
        transform: translate(30px, -50px) scale(1.1);
    }
    66% {
        transform: translate(-20px, 20px) scale(0.9);
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
