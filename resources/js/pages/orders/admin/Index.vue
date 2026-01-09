<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
import AppLayout from "@/layouts/AppLayout.vue";
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Eye, UserPlus, Filter } from 'lucide-vue-next';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { ref } from 'vue';

interface User {
    id: number;
    name: string;
    email: string;
}

interface Order {
    id: number;
    order_number: string;
    client: {
        id: number;
        name: string;
        email: string;
    };
    service: {
        id: number;
        name: string;
        base_price: string;
    };
    technician?: {
        id: number;
        name: string;
    } | null;
    status: 'submitted' | 'under_review' | 'in_production' | 'quality_check' | 'ready_for_delivery' | 'completed';
    special_instructions: string | null;
    due_date: string;
    created_at: string;
}

interface Props {
    orders: {
        data: Order[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
    technicians: User[];
}

const props = defineProps<Props>();

const dialogOpen = ref(false);
const selectedOrder = ref<Order | null>(null);

const assignForm = useForm({
    technician_id: '',
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'All Orders', href: '/admin/orders' },
];

const statusConfig = {
    submitted: { label: 'Submitted', class: 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' },
    under_review: { label: 'Under Review', class: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' },
    in_production: { label: 'In Production', class: 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400' },
    quality_check: { label: 'Quality Check', class: 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400' },
    ready_for_delivery: { label: 'Ready', class: 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' },
    completed: { label: 'Completed', class: 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400' },
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

const getDaysRemaining = (dueDate: string) => {
    const days = Math.ceil((new Date(dueDate).getTime() - new Date().getTime()) / (1000 * 60 * 60 * 24));
    return days > 0 ? `${days} days` : 'Overdue';
};

const openAssignDialog = (order: Order) => {
    selectedOrder.value = order;
    assignForm.technician_id = order.technician?.id?.toString() || '';
    dialogOpen.value = true;
};

const assignTechnician = () => {
    if (!selectedOrder.value) return;

    assignForm.post(`/admin/orders/${selectedOrder.value.id}/assign-technician`, {
        onSuccess: () => {
            dialogOpen.value = false;
            assignForm.reset();
            selectedOrder.value = null;
        },
    });
};
</script>

<template>
    <Head title="Admin Orders" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-10 overflow-x-auto p-10">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-foreground">All Orders</h1>
                    <p class="text-muted-foreground mt-1">
                        Manage and assign orders to technicians
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <Button variant="outline" class="gap-2">
                        <Filter class="h-4 w-4" />
                        Filter
                    </Button>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="rounded-2xl border border-border bg-card shadow-sm p-4">
                <Table>
                    <TableCaption v-if="orders.data.length === 0">
                        No orders found.
                    </TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[50px]">#</TableHead>
                            <TableHead class="w-[120px]">Order ID</TableHead>
                            <TableHead>Client</TableHead>
                            <TableHead>Service</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead>Technician</TableHead>
                            <TableHead>Due Date</TableHead>
                            <TableHead>Price</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="(order, index) in orders.data"
                            :key="order.id"
                            class="hover:bg-muted/50 transition-colors"
                        >
                            <TableCell class="font-medium text-muted-foreground">
                                {{ index + 1 }}
                            </TableCell>
                            <TableCell class="font-medium font-mono text-sm">
                                {{ order.order_number }}
                            </TableCell>
                            <TableCell>
                                <div>
                                    <div class="font-medium text-foreground text-sm">{{ order.client.name }}</div>
                                    <div class="text-xs text-muted-foreground">{{ order.client.email }}</div>
                                </div>
                            </TableCell>
                            <TableCell>
                                <div>
                                    <div class="font-medium text-foreground text-sm">{{ order.service.name }}</div>
                                    <div v-if="order.special_instructions" class="text-xs text-muted-foreground mt-1 truncate max-w-[200px]" :title="order.special_instructions">
                                        {{ order.special_instructions }}
                                    </div>
                                </div>
                            </TableCell>
                            <TableCell>
                                <Badge :class="statusConfig[order.status].class" variant="secondary" class="text-xs">
                                    {{ statusConfig[order.status].label }}
                                </Badge>
                            </TableCell>
                            <TableCell>
                                <div v-if="order.technician" class="flex items-center gap-2">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30">
                                        <span class="text-xs font-semibold text-blue-700 dark:text-blue-400">
                                            {{ order.technician.name.charAt(0) }}
                                        </span>
                                    </div>
                                    <span class="text-sm">{{ order.technician.name }}</span>
                                </div>
                                <span v-else class="text-sm text-muted-foreground italic">
                                    Not assigned
                                </span>
                            </TableCell>
                            <TableCell>
                                <div class="text-sm">
                                    <div class="font-medium text-foreground">{{ formatDate(order.due_date) }}</div>
                                    <div class="text-xs text-muted-foreground">
                                        {{ getDaysRemaining(order.due_date) }}
                                    </div>
                                </div>
                            </TableCell>
                            <TableCell class="font-semibold text-foreground text-sm">
                                Ksh {{ parseFloat(order.service.base_price).toFixed(2) }}
                            </TableCell>
                            <TableCell class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <Button
                                        variant="default"
                                        size="sm"
                                        class="gap-2"
                                        @click="openAssignDialog(order)"
                                    >
                                        <UserPlus class="h-4 w-4" />
                                        {{ order.technician ? 'Reassign' : 'Assign' }}
                                    </Button>
                                    <Button variant="ghost" size="sm">
                                        <Eye class="h-4 w-4" />
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>

                <!-- Pagination -->
                <div v-if="orders.data.length > 0" class="border-t border-border p-4 flex items-center justify-between text-sm text-muted-foreground">
                    <div>
                        Showing {{ ((orders.current_page - 1) * orders.per_page) + 1 }} to
                        {{ Math.min(orders.current_page * orders.per_page, orders.total) }} of
                        {{ orders.total }} orders
                    </div>
                    <div class="flex items-center gap-2">
                        <Button
                            variant="outline"
                            size="sm"
                            :disabled="orders.current_page === 1"
                        >
                            Previous
                        </Button>
                        <span class="px-3">Page {{ orders.current_page }} of {{ orders.last_page }}</span>
                        <Button
                            variant="outline"
                            size="sm"
                            :disabled="orders.current_page === orders.last_page"
                        >
                            Next
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Assign Technician Dialog -->
            <Dialog v-model:open="dialogOpen">
                <DialogContent class="sm:max-w-[425px]" @interact-outside.prevent>
                    <form @submit.prevent="assignTechnician">
                        <DialogHeader>
                            <DialogTitle>Assign Technician</DialogTitle>
                            <DialogDescription>
                                Select a technician to assign to order {{ selectedOrder?.order_number }}
                            </DialogDescription>
                        </DialogHeader>

                        <div class="grid gap-6 py-4">
                            <!-- Order Details -->
                            <div class="rounded-lg bg-muted p-4">
                                <div class="text-sm space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Client:</span>
                                        <span class="font-medium">{{ selectedOrder?.client.name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Service:</span>
                                        <span class="font-medium">{{ selectedOrder?.service.name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Due Date:</span>
                                        <span class="font-medium">{{ selectedOrder ? formatDate(selectedOrder.due_date) : '' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Technician Selection -->
                            <div class="grid gap-3">
                                <Label for="technician">Technician *</Label>
                                <Select v-model="assignForm.technician_id" required>
                                    <SelectTrigger id="technician">
                                        <SelectValue placeholder="Select a technician" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="tech in technicians"
                                            :key="tech.id"
                                            :value="tech.id.toString()"
                                        >
                                            <div class="flex items-center gap-2">
                                                <div class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30">
                                                    <span class="text-xs font-semibold text-blue-700 dark:text-blue-400">
                                                        {{ tech.name.charAt(0) }}
                                                    </span>
                                                </div>
                                                <span>{{ tech.name }}</span>
                                            </div>
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <p v-if="assignForm.errors.technician_id" class="text-sm text-red-500">
                                    {{ assignForm.errors.technician_id }}
                                </p>
                            </div>
                        </div>

                        <DialogFooter>
                            <DialogClose as-child>
                                <Button variant="outline" type="button">
                                    Cancel
                                </Button>
                            </DialogClose>
                            <Button type="submit" :disabled="assignForm.processing">
                                <UserPlus class="h-4 w-4 mr-2" v-if="!assignForm.processing" />
                                {{ assignForm.processing ? 'Assigning...' : 'Assign Technician' }}
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>
