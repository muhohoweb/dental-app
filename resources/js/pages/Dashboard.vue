<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import {
    Package,
    Users,
    DollarSign,
    Clock,
    TrendingUp,
    AlertCircle,
    CheckCircle2,
    XCircle,
    Timer,
    ArrowUpRight,
    ArrowDownRight,
    MoreVertical
} from 'lucide-vue-next';
import { useAuth } from '@/utils/auth';

const auth = useAuth();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

// Mock data - replace with real data from props or API
const stats = [
    {
        title: 'Active Orders',
        value: '24',
        change: '+12%',
        trend: 'up',
        icon: Package,
        color: 'blue',
    },
    {
        title: 'Total Revenue',
        value: 'Ksh 45,231',
        change: '+8%',
        trend: 'up',
        icon: DollarSign,
        color: 'green',
    },
    {
        title: 'Avg. Turnaround',
        value: '3.2 days',
        change: '-0.5 days',
        trend: 'up',
        icon: Clock,
        color: 'purple',
    },
    {
        title: 'Active Clients',
        value: '156',
        change: '+5%',
        trend: 'up',
        icon: Users,
        color: 'cyan',
    },
];

const recentOrders = [
    {
        id: 'ORD-1234',
        client: 'Dr. Sarah Johnson',
        service: 'Zirconia Crown',
        status: 'in-progress',
        dueDate: '2024-01-12',
        priority: 'high',
    },
    {
        id: 'ORD-1235',
        client: 'Smile Dental Clinic',
        service: 'Full Denture',
        status: 'quality-check',
        dueDate: '2024-01-13',
        priority: 'medium',
    },
    {
        id: 'ORD-1236',
        client: 'Dr. Michael Chen',
        service: 'Partial Denture',
        status: 'ready',
        dueDate: '2024-01-11',
        priority: 'low',
    },
    {
        id: 'ORD-1237',
        client: 'Central Dental',
        service: 'Implant Crown',
        status: 'submitted',
        dueDate: '2024-01-15',
        priority: 'high',
    },
];

const statusConfig = {
    'submitted': { label: 'Submitted', color: 'bg-blue-500', icon: AlertCircle },
    'in-progress': { label: 'In Progress', color: 'bg-amber-500', icon: Timer },
    'quality-check': { label: 'Quality Check', color: 'bg-purple-500', icon: CheckCircle2 },
    'ready': { label: 'Ready', color: 'bg-green-500', icon: CheckCircle2 },
    'delayed': { label: 'Delayed', color: 'bg-red-500', icon: XCircle },
};

const priorityConfig = {
    'high': { label: 'High', color: 'text-red-600 bg-red-50 dark:text-red-400 dark:bg-red-950/30' },
    'medium': { label: 'Medium', color: 'text-amber-600 bg-amber-50 dark:text-amber-400 dark:bg-amber-950/30' },
    'low': { label: 'Low', color: 'text-green-600 bg-green-50 dark:text-green-400 dark:bg-green-950/30' },
};

const getColorClasses = (color: string) => {
    const colors = {
        blue: 'from-blue-500 to-cyan-500',
        green: 'from-green-500 to-emerald-500',
        purple: 'from-purple-500 to-pink-500',
        cyan: 'from-cyan-500 to-blue-500',
    };
    return colors[color as keyof typeof colors] || colors.blue;
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-6">
            <!-- Welcome Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-foreground">
                        Welcome back, {{ auth.user?.name || 'User' }}
                    </h1>
                    <p class="text-muted-foreground mt-1">
                        Here's what's happening in your lab today
                    </p>
                </div>
                <div class="text-sm text-muted-foreground">
                    {{ new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <div
                    v-for="stat in stats"
                    :key="stat.title"
                    class="group relative overflow-hidden rounded-2xl border border-border bg-card p-6 shadow-sm transition-all hover:shadow-lg"
                >
                    <!-- Background Gradient -->
                    <div :class="['absolute inset-0 bg-gradient-to-br opacity-5 transition-opacity group-hover:opacity-10', getColorClasses(stat.color)]"></div>

                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div :class="['flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br shadow-lg', getColorClasses(stat.color)]">
                                <component :is="stat.icon" class="h-6 w-6 text-white" />
                            </div>
                            <div :class="['flex items-center gap-1 text-sm font-semibold', stat.trend === 'up' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400']">
                                <component :is="stat.trend === 'up' ? ArrowUpRight : ArrowDownRight" class="h-4 w-4" />
                                {{ stat.change }}
                            </div>
                        </div>

                        <div>
                            <p class="text-sm text-muted-foreground mb-1">{{ stat.title }}</p>
                            <p class="text-3xl font-bold text-foreground">{{ stat.value }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Recent Orders -->
                <div class="lg:col-span-2">
                    <div class="rounded-2xl border border-border bg-card shadow-sm">
                        <div class="flex items-center justify-between border-b border-border p-6">
                            <div>
                                <h2 class="text-xl font-bold text-foreground">Recent Orders</h2>
                                <p class="text-sm text-muted-foreground mt-1">Track your latest cases</p>
                            </div>
                            <button class="flex h-9 w-9 items-center justify-center rounded-lg hover:bg-muted transition-colors">
                                <MoreVertical class="h-5 w-5 text-muted-foreground" />
                            </button>
                        </div>

                        <div class="divide-y divide-border">
                            <div
                                v-for="order in recentOrders"
                                :key="order.id"
                                class="group flex items-center gap-4 p-6 hover:bg-muted/50 transition-colors cursor-pointer"
                            >
                                <!-- Status Icon -->
                                <div :class="['flex h-10 w-10 items-center justify-center rounded-lg', statusConfig[order.status as keyof typeof statusConfig].color]">
                                    <component :is="statusConfig[order.status as keyof typeof statusConfig].icon" class="h-5 w-5 text-white" />
                                </div>

                                <!-- Order Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <p class="font-semibold text-foreground">{{ order.id }}</p>
                                        <span :class="['text-xs font-semibold px-2 py-0.5 rounded-full', priorityConfig[order.priority as keyof typeof priorityConfig].color]">
                                            {{ priorityConfig[order.priority as keyof typeof priorityConfig].label }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-muted-foreground">{{ order.client }} • {{ order.service }}</p>
                                </div>

                                <!-- Due Date & Status -->
                                <div class="text-right">
                                    <p class="text-sm font-medium text-foreground mb-1">Due {{ order.dueDate }}</p>
                                    <p class="text-xs text-muted-foreground">{{ statusConfig[order.status as keyof typeof statusConfig].label }}</p>
                                </div>

                                <!-- Arrow -->
                                <ArrowUpRight class="h-5 w-5 text-muted-foreground opacity-0 group-hover:opacity-100 transition-opacity" />
                            </div>
                        </div>

                        <div class="border-t border-border p-4">
                            <button class="w-full rounded-lg bg-muted py-2.5 text-sm font-semibold text-foreground hover:bg-muted/80 transition-colors">
                                View All Orders
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Side Panel -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="rounded-2xl border border-border bg-card p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-foreground mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <button class="w-full flex items-center gap-3 rounded-xl bg-gradient-to-r from-blue-500 to-cyan-500 p-4 text-left text-white shadow-lg hover:shadow-xl transition-all">
                                <Package class="h-5 w-5" />
                                <span class="font-semibold">New Order</span>
                            </button>
                            <button class="w-full flex items-center gap-3 rounded-xl bg-muted p-4 text-left hover:bg-muted/80 transition-colors">
                                <Users class="h-5 w-5 text-muted-foreground" />
                                <span class="font-semibold text-foreground">Add Client</span>
                            </button>
                            <button class="w-full flex items-center gap-3 rounded-xl bg-muted p-4 text-left hover:bg-muted/80 transition-colors">
                                <DollarSign class="h-5 w-5 text-muted-foreground" />
                                <span class="font-semibold text-foreground">Generate Invoice</span>
                            </button>
                        </div>
                    </div>

                    <!-- Production Status -->
                    <div class="rounded-2xl border border-border bg-card p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-foreground mb-4">Production Status</h3>
                        <div class="space-y-4">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-foreground">In Progress</span>
                                    <span class="text-sm font-bold text-foreground">8 cases</span>
                                </div>
                                <div class="h-2 rounded-full bg-muted overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-amber-500 to-orange-500" style="width: 60%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-foreground">Quality Check</span>
                                    <span class="text-sm font-bold text-foreground">5 cases</span>
                                </div>
                                <div class="h-2 rounded-full bg-muted overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-purple-500 to-pink-500" style="width: 40%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-foreground">Ready for Delivery</span>
                                    <span class="text-sm font-bold text-foreground">11 cases</span>
                                </div>
                                <div class="h-2 rounded-full bg-muted overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-green-500 to-emerald-500" style="width: 85%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alerts -->
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 shadow-sm dark:border-amber-900/30 dark:bg-amber-950/20">
                        <div class="flex items-start gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-500">
                                <AlertCircle class="h-4 w-4 text-white" />
                            </div>
                            <div>
                                <h4 class="font-semibold text-amber-900 dark:text-amber-200 mb-1">Attention Needed</h4>
                                <p class="text-sm text-amber-800 dark:text-amber-300">3 orders are approaching their due date</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Smooth transitions */
* {
    transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}
</style>
