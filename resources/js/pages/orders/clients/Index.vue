<script setup lang="ts">
import { Head, Link, useForm } from "@inertiajs/vue3";
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
import { Plus, Eye } from 'lucide-vue-next';
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
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { ref } from 'vue';

interface Service {
    id: number;
    name: string;
    description: string;
    base_price: string;
    turnaround_days: number;
}

interface Order {
    id: number;
    order_number: string;
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
    services: Service[];
}

const props = defineProps<Props>();

const dialogOpen = ref(false);

const form = useForm({
    service_id: '',
    special_instructions: '',
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Client Orders', href: '/orders' },
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

const submitOrder = () => {
    form.post('/orders', {
        onSuccess: () => {
            dialogOpen.value = false;
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Client Orders" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-10 overflow-x-auto p-10">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-foreground">Client Orders</h1>
                    <p class="text-muted-foreground mt-1">
                        Track and manage your laboratory orders
                    </p>
                </div>

                <!-- Create Order Dialog -->
                <Dialog v-model:open="dialogOpen">
                    <DialogTrigger as-child>
                        <Button class="gap-2">
                            <Plus class="h-4 w-4" />
                            New Order
                        </Button>
                    </DialogTrigger>
                    <DialogContent class="sm:max-w-[500px]" @interact-outside.prevent>
                        <form @submit.prevent="submitOrder">
                            <DialogHeader>
                                <DialogTitle>Create New Order</DialogTitle>
                                <DialogDescription>
                                    Select a service and add any special instructions for your order.
                                </DialogDescription>
                            </DialogHeader>

                            <div class="grid gap-6 py-4">
                                <!-- Service Selection -->
                                <div class="grid gap-3">
                                    <Label for="service">Service *</Label>
                                    <Select v-model="form.service_id" required>
                                        <SelectTrigger id="service">
                                            <SelectValue placeholder="Select a service" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem
                                                v-for="service in services"
                                                :key="service.id"
                                                :value="service.id.toString()"
                                            >
                                                <div class="flex items-center justify-between w-full">
                                                    <span>{{ service.name }}</span>
                                                    <span class="text-muted-foreground ml-4">
                                                        Ksh {{ parseFloat(service.base_price).toFixed(2) }}
                                                    </span>
                                                </div>
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <p v-if="form.errors.service_id" class="text-sm text-red-500">
                                        {{ form.errors.service_id }}
                                    </p>
                                </div>

                                <!-- Special Instructions -->
                                <div class="grid gap-3">
                                    <Label for="instructions">Special Instructions</Label>
                                    <Textarea
                                        id="instructions"
                                        v-model="form.special_instructions"
                                        placeholder="e.g., Shade A2, margin chamfer, rush order..."
                                        class="min-h-[100px]"
                                    />
                                    <p v-if="form.errors.special_instructions" class="text-sm text-red-500">
                                        {{ form.errors.special_instructions }}
                                    </p>
                                </div>
                            </div>

                            <DialogFooter>
                                <DialogClose as-child>
                                    <Button variant="outline" type="button">
                                        Cancel
                                    </Button>
                                </DialogClose>
                                <Button type="submit" :disabled="form.processing">
                                    <Plus class="h-4 w-4 mr-2" v-if="!form.processing" />
                                    {{ form.processing ? 'Creating...' : 'Create Order' }}
                                </Button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>
            </div>

            <!-- Orders Table -->
            <div class="rounded-2xl border border-border bg-card shadow-sm p-4">
                <Table>
                    <TableCaption v-if="orders.data.length === 0">
                        No orders found. Create your first order to get started.
                    </TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[140px]">#</TableHead>
                            <TableHead class="w-[140px]">Order Number</TableHead>
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
                            <TableCell class="font-medium font-mono">
                                {{ index + 1 }}
                            </TableCell>
                            <TableCell class="font-medium font-mono">
                                {{ order.order_number }}
                            </TableCell>
                            <TableCell>
                                <div>
                                    <div class="font-medium text-foreground">{{ order.service.name }}</div>
                                    <div v-if="order.special_instructions" class="text-xs text-muted-foreground mt-1 truncate max-w-[200px]" :title="order.special_instructions">
                                        {{ order.special_instructions }}
                                    </div>
                                </div>
                            </TableCell>
                            <TableCell>
                                <Badge :class="statusConfig[order.status].class" variant="secondary">
                                    {{ statusConfig[order.status].label }}
                                </Badge>
                            </TableCell>
                            <TableCell>
                                <span v-if="order.technician" class="text-sm text-foreground">
                                    {{ order.technician.name }}
                                </span>
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
                            <TableCell class="font-semibold text-foreground">
                                Ksh {{ parseFloat(order.service.base_price).toFixed(2) }}
                            </TableCell>
                            <TableCell class="text-right">
                                <Button variant="ghost" size="sm" class="gap-2">
                                    <Eye class="h-4 w-4" />
                                    View
                                </Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>

                <!-- Pagination Info -->
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

            <!-- Empty State -->
            <div
                v-if="orders.data.length === 0"
                class="flex flex-col items-center justify-center py-16 text-center"
            >
                <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-muted">
                    <Plus class="h-10 w-10 text-muted-foreground" />
                </div>
                <h3 class="text-xl font-semibold text-foreground mb-2">No orders yet</h3>
                <p class="text-muted-foreground mb-6 max-w-sm">
                    Create your first order to start tracking your dental laboratory work.
                </p>
            </div>
        </div>
    </AppLayout>
</template>
