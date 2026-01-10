<script setup lang="ts">
import { defineComponent } from "vue";
import { Head, router } from "@inertiajs/vue3";
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
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
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
    { name: 'Services', href: '/admin/services' }
];

const isCreateDialogOpen = ref(false);
const isEditDialogOpen = ref(false);
const isDeleteDialogOpen = ref(false);
const serviceToDelete = ref<Service | null>(null);
const serviceToEdit = ref<Service | null>(null);

const createForm = useForm({
    name: '',
    description: '',
    base_price: '',
    turnaround_days: ''
});

const editForm = useForm({
    name: '',
    description: '',
    base_price: '',
    turnaround_days: ''
});

const submitCreateForm = () => {
    createForm.post('/admin/services', {
        onSuccess: () => {
            createForm.reset();
            isCreateDialogOpen.value = false;
        }
    });
};

const openEditDialog = (service: Service) => {
    serviceToEdit.value = service;
    editForm.name = service.name;
    editForm.description = service.description;
    editForm.base_price = service.base_price.replace(/,/g, '');
    editForm.turnaround_days = service.turnaround_days;
    isEditDialogOpen.value = true;
};

const submitEditForm = () => {
    if (!serviceToEdit.value) return;

    editForm.put(`/admin/services/${serviceToEdit.value.id}`, {
        onSuccess: () => {
            editForm.reset();
            isEditDialogOpen.value = false;
            serviceToEdit.value = null;
        }
    });
};

const openDeleteDialog = (service: Service) => {
    serviceToDelete.value = service;
    isDeleteDialogOpen.value = true;
};

const confirmDelete = () => {
    if (!serviceToDelete.value) return;

    router.delete(`/admin/services/${serviceToDelete.value.id}`, {
        onSuccess: () => {
            isDeleteDialogOpen.value = false;
            serviceToDelete.value = null;
        }
    });
};
</script>

<template>
    <Head title="Services" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Services</h1>
                    <p class="text-muted-foreground">Available Services</p>
                </div>

                <!-- Create Dialog -->
                <Dialog v-model:open="isCreateDialogOpen">
                    <DialogTrigger as-child>
                        <Button>Add Service</Button>
                    </DialogTrigger>
                    <DialogContent class="sm:max-w-[500px]" @interact-outside="(e) => e.preventDefault()" @escape-key-down="(e) => e.preventDefault()">
                        <form @submit.prevent="submitCreateForm">
                            <DialogHeader>
                                <DialogTitle>Add New Service</DialogTitle>
                                <DialogDescription>
                                    Create a new service for your business.
                                </DialogDescription>
                            </DialogHeader>

                            <div class="grid gap-4 py-4">
                                <div class="grid gap-2">
                                    <Label for="create-name">Service Name</Label>
                                    <Input
                                        id="create-name"
                                        v-model="createForm.name"
                                        placeholder="e.g., Dental Crown"
                                        :class="{ 'border-red-500': createForm.errors.name }"
                                    />
                                    <span v-if="createForm.errors.name" class="text-sm text-red-500">
                                        {{ createForm.errors.name }}
                                    </span>
                                </div>

                                <div class="grid gap-2">
                                    <Label for="create-description">Description</Label>
                                    <Textarea
                                        id="create-description"
                                        v-model="createForm.description"
                                        placeholder="Describe the service..."
                                        :class="{ 'border-red-500': createForm.errors.description }"
                                    />
                                    <span v-if="createForm.errors.description" class="text-sm text-red-500">
                                        {{ createForm.errors.description }}
                                    </span>
                                </div>

                                <div class="grid gap-2">
                                    <Label for="create-price">Base Price (Ksh)</Label>
                                    <Input
                                        id="create-price"
                                        v-model="createForm.base_price"
                                        type="number"
                                        step="0.01"
                                        placeholder="0.00"
                                        :class="{ 'border-red-500': createForm.errors.base_price }"
                                    />
                                    <span v-if="createForm.errors.base_price" class="text-sm text-red-500">
                                        {{ createForm.errors.base_price }}
                                    </span>
                                </div>

                                <div class="grid gap-2">
                                    <Label for="create-turnaround">Turnaround Days</Label>
                                    <Input
                                        id="create-turnaround"
                                        v-model="createForm.turnaround_days"
                                        type="number"
                                        placeholder="e.g., 7"
                                        :class="{ 'border-red-500': createForm.errors.turnaround_days }"
                                    />
                                    <span v-if="createForm.errors.turnaround_days" class="text-sm text-red-500">
                                        {{ createForm.errors.turnaround_days }}
                                    </span>
                                </div>
                            </div>

                            <DialogFooter>
                                <DialogClose as-child>
                                    <Button type="button" variant="outline" :disabled="createForm.processing">
                                        Cancel
                                    </Button>
                                </DialogClose>
                                <Button type="submit" :disabled="createForm.processing">
                                    {{ createForm.processing ? 'Creating...' : 'Create Service' }}
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
                            <TableHead>Description</TableHead>
                            <TableHead>Base Price</TableHead>
                            <TableHead>Turnaround Days</TableHead>
                            <TableHead>Created</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(service, index) in props.services" :key="service.id">
                            <TableCell class="font-medium">{{ index + 1 }}</TableCell>
                            <TableCell class="font-medium">{{ service.name }}</TableCell>
                            <TableCell class="max-w-xs truncate">{{ service.description }}</TableCell>
                            <TableCell>Ksh {{ service.base_price }}</TableCell>
                            <TableCell>{{ service.turnaround_days }} days</TableCell>
                            <TableCell>{{ service.created_at }}</TableCell>
                            <TableCell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <Button
                                        variant="outline"
                                        size="sm"
                                        @click="openEditDialog(service)"
                                    >
                                        Edit
                                    </Button>
                                    <Button
                                        variant="destructive"
                                        size="sm"
                                        @click="openDeleteDialog(service)"
                                    >
                                        Delete
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>

                <div v-if="props.services.length === 0" class="py-12 text-center text-muted-foreground">
                    No services found.
                </div>
            </div>
        </div>

        <!-- Edit Dialog -->
        <Dialog v-model:open="isEditDialogOpen">
            <DialogContent class="sm:max-w-[500px]" @interact-outside="(e) => e.preventDefault()" @escape-key-down="(e) => e.preventDefault()">
                <form @submit.prevent="submitEditForm">
                    <DialogHeader>
                        <DialogTitle>Edit Service</DialogTitle>
                        <DialogDescription>
                            Update the service details.
                        </DialogDescription>
                    </DialogHeader>

                    <div class="grid gap-4 py-4">
                        <div class="grid gap-2">
                            <Label for="edit-name">Service Name</Label>
                            <Input
                                id="edit-name"
                                v-model="editForm.name"
                                :class="{ 'border-red-500': editForm.errors.name }"
                            />
                            <span v-if="editForm.errors.name" class="text-sm text-red-500">
                                {{ editForm.errors.name }}
                            </span>
                        </div>

                        <div class="grid gap-2">
                            <Label for="edit-description">Description</Label>
                            <Textarea
                                id="edit-description"
                                v-model="editForm.description"
                                :class="{ 'border-red-500': editForm.errors.description }"
                            />
                            <span v-if="editForm.errors.description" class="text-sm text-red-500">
                                {{ editForm.errors.description }}
                            </span>
                        </div>

                        <div class="grid gap-2">
                            <Label for="edit-price">Base Price (Ksh)</Label>
                            <Input
                                id="edit-price"
                                v-model="editForm.base_price"
                                type="number"
                                step="0.01"
                                :class="{ 'border-red-500': editForm.errors.base_price }"
                            />
                            <span v-if="editForm.errors.base_price" class="text-sm text-red-500">
                                {{ editForm.errors.base_price }}
                            </span>
                        </div>

                        <div class="grid gap-2">
                            <Label for="edit-turnaround">Turnaround Days</Label>
                            <Input
                                id="edit-turnaround"
                                v-model="editForm.turnaround_days"
                                type="number"
                                :class="{ 'border-red-500': editForm.errors.turnaround_days }"
                            />
                            <span v-if="editForm.errors.turnaround_days" class="text-sm text-red-500">
                                {{ editForm.errors.turnaround_days }}
                            </span>
                        </div>
                    </div>

                    <DialogFooter>
                        <DialogClose as-child>
                            <Button type="button" variant="outline" :disabled="editForm.processing">
                                Cancel
                            </Button>
                        </DialogClose>
                        <Button type="submit" :disabled="editForm.processing">
                            {{ editForm.processing ? 'Updating...' : 'Update Service' }}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Delete Confirmation Dialog -->
        <AlertDialog v-model:open="isDeleteDialogOpen">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>Are you sure?</AlertDialogTitle>
                    <AlertDialogDescription>
                        This will permanently delete the service "{{ serviceToDelete?.name }}".
                        This action cannot be undone.
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel>Cancel</AlertDialogCancel>
                    <AlertDialogAction
                        @click="confirmDelete"
                        class="bg-destructive text-destructive-foreground hover:bg-destructive/90"
                    >
                        Delete
                    </AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </AppLayout>
</template>
