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
    <Head title="Technicians" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Technicians</h1>
                    <p class="text-muted-foreground">Manage your technicians and technicians</p>
                </div>

                <Dialog v-model:open="isDialogOpen">
                    <DialogTrigger as-child>
                        <Button>Add Technician</Button>
                    </DialogTrigger>
                    <DialogContent class="sm:max-w-[425px]" @interact-outside.prevent>
                        <form @submit.prevent="submitForm">
                            <DialogHeader>
                                <DialogTitle>Add New Technician</DialogTitle>
                                <DialogDescription>
                                    Create a new technician account. They will receive an email with their login credentials.
                                </DialogDescription>
                            </DialogHeader>

                            <div class="grid gap-4 py-4">
                                <div class="grid gap-2">
                                    <Label for="name">Full Name</Label>
                                    <Input
                                        id="name"
                                        v-model="form.name"
                                        placeholder="John Doe"
                                        :class="{ 'border-red-500': form.errors.name }"
                                    />
                                    <span v-if="form.errors.name" class="text-sm text-red-500">
                    {{ form.errors.name }}
                  </span>
                                </div>

                                <div class="grid gap-2">
                                    <Label for="email">Email</Label>
                                    <Input
                                        id="email"
                                        v-model="form.email"
                                        type="email"
                                        placeholder="john@example.com"
                                        :class="{ 'border-red-500': form.errors.email }"
                                    />
                                    <span v-if="form.errors.email" class="text-sm text-red-500">
                    {{ form.errors.email }}
                  </span>
                                </div>

                                <div class="grid gap-2">
                                    <Label for="password">Password</Label>
                                    <Input
                                        id="password"
                                        v-model="form.password"
                                        type="password"
                                        placeholder="••••••••"
                                        :class="{ 'border-red-500': form.errors.password }"
                                    />
                                    <span v-if="form.errors.password" class="text-sm text-red-500">
                    {{ form.errors.password }}
                  </span>
                                </div>

                                <div class="grid gap-2">
                                    <Label for="password_confirmation">Confirm Password</Label>
                                    <Input
                                        id="password_confirmation"
                                        v-model="form.password_confirmation"
                                        type="password"
                                        placeholder="••••••••"
                                    />
                                </div>
                            </div>

                            <DialogFooter>
                                <DialogClose as-child>
                                    <Button type="button" variant="outline" :disabled="form.processing">
                                        Cancel
                                    </Button>
                                </DialogClose>
                                <Button type="submit" :disabled="form.processing">
                                    {{ form.processing ? 'Creating...' : 'Create Technician' }}
                                </Button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>
            </div>

            <div class="rounded-lg border bg-white">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[50px]">#</TableHead>
                            <TableHead>Name</TableHead>
                            <TableHead>Email</TableHead>
                            <TableHead>Joined</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
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
                            <TableCell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <Button variant="outline" size="sm">Edit</Button>
                                    <Button variant="destructive" size="sm">
                                        Delete</Button>
                                </div>
                            </TableCell>
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
