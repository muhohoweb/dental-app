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

interface User {
    id: number;
    name: string;
    email: string;
    created_at: string;
}

interface Props {
    users: User[];
}

const props = defineProps<Props>();

const breadcrumbs = [
    { name: 'Dashboard', href: '/dashboard' },
    { name: 'Users', href: '/users' }
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
                    <h1 class="text-3xl font-bold tracking-tight">Clients</h1>
                    <p class="text-muted-foreground">Registered clients</p>
                </div>
            </div>

            <div class="rounded-lg border bg-white">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[50px]">#</TableHead>
                            <TableHead>Name</TableHead>
                            <TableHead>Email</TableHead>
                            <TableHead>Joined</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(user, index) in props.users" :key="user.id">
                            <TableCell class="font-medium">{{ index + 1 }}</TableCell>
                            <TableCell>
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary text-primary-foreground">
                                        {{ user.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <span class="font-medium">{{ user.name }}</span>
                                </div>
                            </TableCell>
                            <TableCell>{{ user.email }}</TableCell>
                            <TableCell>{{ user.created_at }}</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>

                <div v-if="props.users.length === 0" class="py-12 text-center text-muted-foreground">
                    No users found.
                </div>
            </div>
        </div>
    </AppLayout>
</template>
