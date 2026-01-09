<script setup lang="ts">
import { defineComponent } from "vue";
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/layouts/AppLayout.vue";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
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
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Service {
    id: number;
    name: string;
    description: string;
    base_price: string;
    turnaround_days: string;
    created_at: string;
}

interface Props {
    services: Service[];
}

const props = defineProps<Props>();

const breadcrumbs = [
    { name: 'Dashboard', href: '/dashboard' },
    { name: 'Services', href: '/Services' }
];

const isDialogOpen = ref(false);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
});

const submitForm = () => {
    form.post('/admin/users', {
        onSuccess: () => {
            form.reset();
            isDialogOpen.value = false;
        },
        onError: () => {
            // Handle errors if needed
        }
    });
};
</script>

<template>
    <Head title="Clients" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Services</h1>
                    <p class="text-muted-foreground">Available Services</p>
                </div>
            </div>

            <div class="rounded-lg border bg-white">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[50px]">#</TableHead>
                            <TableHead>Name</TableHead>
                            <TableHead>Description</TableHead>
                            <TableHead>Base Price</TableHead>
                            <TableHead>Turn Around days</TableHead>
                            <TableHead>Created</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(service, index) in props.services" :key="service.id">
                            <TableCell class="font-medium">{{ index + 1 }}</TableCell>
                            <TableCell>
                                {{ service.name }}
                            </TableCell>
                            <TableCell>
                                {{ service.description }}
                            </TableCell>
                            <TableCell>
                                {{ service.base_price }}
                            </TableCell>
                            <TableCell>
                                {{ service.turnaround_days }}
                            </TableCell>
                            <TableCell>
                                {{ service.created_at }}
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>

                <div v-if="props.services.length === 0" class="py-12 text-center text-muted-foreground">
                    No services found.
                </div>
            </div>
        </div>
    </AppLayout>
</template>
