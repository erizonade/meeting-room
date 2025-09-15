<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { router, useForm } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';

import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    // SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'

const props = defineProps({
    users: Object,
    unit: Object,
})

const form = useForm({
    name: props.users?.name ?? '',
    email: props.users?.email ?? '',
    password: '',
    role: props.users?.role ?? '',
    unit_id: props.users?.unit_id ?? '',
});

const submit = () => {
    if (props.users) {
        form.put(`/meeting/user/${props.users.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                form.clearErrors();
                toast.success('User berhasil disimpan', { duration: 3000 })
                setTimeout(() => router.visit("/meeting/user"), 1000) // redirect manual
            },
            onError: () => {
                toast.error('Something went wrong', { duration: 3000 })
            }
        })

        return;
    }
    form.post('/meeting/user', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            form.clearErrors();
            toast.success('User berhasil disimpan', { duration: 3000 })
            setTimeout(() => router.visit("/meeting/user"), 1000) // redirect manual
        },
        onError: () => {
            toast.error('Something went wrong', { duration: 3000 })
        }
    });
}

</script>

<template>
    <AppLayout>
        <Heading title="Unit" description="Form Unit" />
        <form class="space-y-6" @submit.prevent="submit">
            <div class="grid gap-2">
                <Label>Nama</Label>
                <Input type="text" placeholder="Nama" v-model="form.name" class="mt-1 block w-full" />
                <div v-if="form.errors.name" class="text-red-800">{{ form.errors.name }}</div>
            </div>
            <div class="grid gap-2">
                <Label>Email</Label>
                <Input type="email" placeholder="Email" v-model="form.email" class="mt-1 block w-full" />
                <div v-if="form.errors.email" class="text-red-800">{{ form.errors.email }}</div>
            </div>
            <div class="grid gap-2">
                <Label>Password</Label>
                <Input type="text" placeholder="Password" v-model="form.password" class="mt-1 block w-full" />
                <div v-if="form.errors.password" class="text-red-800">{{ form.errors.password }}</div>
            </div>
            <div class="grid gap-2">
                <Label>Role</Label>
                <Select v-model="form.role">
                    <SelectTrigger class=" w-full">
                        <SelectValue placeholder="Select Role" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectGroup>
                            <SelectItem value="super-admin">Super Admin</SelectItem>
                            <SelectItem value="admin">Admin</SelectItem>
                            <SelectItem value="anggota">Anggota</SelectItem>
                        </SelectGroup>
                    </SelectContent>
                </Select>
                <div v-if="form.errors.role" class="text-red-800">{{ form.errors.role }}</div>
            </div>
            <div class="grid gap-2" v-if="form.role == 'admin'">
                <Label>Nama Unit</Label>
                <Select v-model="form.unit_id">
                    <SelectTrigger class=" w-full">
                        <SelectValue placeholder="Select Unit" />
                    </SelectTrigger>
                    <SelectContent>

                        <SelectItem v-for="value in props.unit" :key="value.id" :value=value.id>{{
                            value.name }}</SelectItem>
                    </SelectContent>
                </Select>
                <div v-if="form.errors.unit_id" class="text-red-800">{{ form.errors.unit_id }}</div>
            </div>
            <Button type="submit">Simpan</Button>
        </form>
    </AppLayout>
</template>
