<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import {
    LayoutDashboard,
    Package,
    Users,
    FileText,
    DollarSign,
    CreditCard,
    Settings,
    BarChart3,
    MessageSquare,
    Bell,
    User,
    Wrench,
    ClipboardList,
    BookOpen,
    Github,
} from 'lucide-vue-next';
import { computed } from 'vue';
import { useAuth } from '@/utils/auth';

const user = useAuth();
const admin_path = 'admin'
const allNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutDashboard,
        requiredPermissions: [],
    },
    {
        title: 'Orders',
        href: '/'+admin_path+'/orders',
        icon: Package,
        requiredRoles: ['admin'],
        requireAnyPermission: true,
    },
    {
        title: 'Orders',
        href: '/orders',
        icon: Package,
        requiredRoles: ['client'],
        requireAnyPermission: true,
    },
    {
        title: 'Services',
        href: '/admin/services',
        icon: Wrench,
        requiredPermissions: ['view-services'],
    },
    {
        title: 'Clients',
        href: '/'+admin_path+'/users/clients',
        icon: Users,
        requiredPermissions: ['view-all-clients'],
    },
    {
        title: 'Technicians',
        href: '/'+admin_path+'/users/technicians',
        icon: User,
        requiredPermissions: ['view-technicians'],
    },
    {
        title: 'SOPs',
        href: '/'+admin_path+'/sops',
        icon: FileText,
        requiredPermissions: ['view-sops'],
    },
    {
        title: 'Invoices',
        href: '/'+admin_path+'/invoices',
        icon: DollarSign,
        requiredPermissions: ['view-all-invoices', 'view-own-invoices'],
        requireAnyPermission: true,
    },
    {
        title: 'Payments',
        href: '/'+admin_path+'/payments',
        icon: CreditCard,
        requiredPermissions: ['view-all-payments', 'view-own-payments'],
        requireAnyPermission: true,
    },
];

const mainNavItems = computed((): NavItem[] => {
    return allNavItems.filter((item) => {
        const requirements = {
            requiredRoles: item.requiredRoles || [],
            requiredPermissions: item.requiredPermissions || [],
            requireAnyRole: item.requireAnyRole || false,
            requireAnyPermission: item.requireAnyPermission || false,
        };
        return user.canAccess(requirements);
    });
});

const footerNavItems: NavItem[] = [
    {
        title: 'Documentation',
        href: '/docs',
        icon: BookOpen,
    },
    {
        title: 'Code',
        href: '#',
        icon: Github,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset" class="border-r border-border/40">
        <SidebarHeader class="border-b border-border/40 bg-gradient-to-r from-background via-background to-muted/20">
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child class="group">
                        <Link :href="dashboard()" class="flex items-center gap-3">
                            <!-- Logo Icon -->
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 shadow-lg shadow-blue-500/30 transition-all group-hover:shadow-xl group-hover:shadow-blue-500/40 group-hover:scale-105">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                            </div>

                            <!-- Logo Text -->
                            <div class="flex flex-col gap-0.5 leading-none">
                                <span class="font-bold text-base bg-gradient-to-r from-blue-600 to-cyan-600 dark:from-blue-400 dark:to-cyan-400 bg-clip-text text-transparent">
                                    DentalLab Pro
                                </span>
                                <span class="text-xs text-muted-foreground">
                                    Lab Management
                                </span>
                            </div>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent class="bg-gradient-to-b from-background via-background to-muted/10">
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter class="border-t border-border/40 bg-gradient-to-r from-background via-background to-muted/20">
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>

<style scoped>
/* Custom scrollbar for sidebar */
:deep(.sidebar-content) {
    scrollbar-width: thin;
    scrollbar-color: hsl(var(--muted)) transparent;
}

:deep(.sidebar-content::-webkit-scrollbar) {
    width: 6px;
}

:deep(.sidebar-content::-webkit-scrollbar-track) {
    background: transparent;
}

:deep(.sidebar-content::-webkit-scrollbar-thumb) {
    background: hsl(var(--muted));
    border-radius: 3px;
}

:deep(.sidebar-content::-webkit-scrollbar-thumb:hover) {
    background: hsl(var(--muted-foreground) / 0.3);
}

/* Smooth transitions for dark mode */
* {
    transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
}
</style>
