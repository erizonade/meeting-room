<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { router, useForm } from '@inertiajs/vue3';
import { toast, Toaster } from 'vue-sonner';
import 'vue-sonner/style.css'

const props = defineProps({
    room: Object
})

const form = useForm({
    name: props.room?.name ?? '',
    capacity: props.room?.capacity ?? '',
});

const submit = () => {
    if (props.room) {
        form.put(`/meeting/meeting-room/${props.room.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                form.clearErrors();
                toast.success('Meeting Room berhasil disimpan', { duration: 3000 })
                setTimeout(() => router.visit("/meeting/meeting-room"), 1000) // redirect manual
            },
            onError: () => {
                toast.error('Something went wrong', { duration: 3000 })
            }
        })

        return;
    }
    form.post('/meeting/meeting-room', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            form.clearErrors();
            toast.success('Meeting Room berhasil disimpan', { duration: 3000 })
            setTimeout(() => router.visit("/meeting/meeting-room"), 1000) // redirect manual

        },
        onError: () => {
            toast.error('Something went wrong', { duration: 3000 })
        }
    });
}

</script>

<template>
    <AppLayout>
        <Toaster :duration="3000" />
        <Heading title="Ruangan Meeting" description="Form Ruangan Meeting" />
        <form class="space-y-6" @submit.prevent="submit">
            <div class="grid gap-2">
                <Label>Nama Ruangan</Label>
                <Input type="text" placeholder="Nama Ruangan" v-model="form.name" class="mt-1 block w-full" />
                <div v-if="form.errors.name && !form.isDirty" class="text-red-800">{{ form.errors.name }}</div>
            </div>
            <div class="grid gap-2">
                <Label>Kapasitas Ruangan</Label>
                <Input type="number" placeholder="Kapasistas Ruangan" v-model="form.capacity"
                    class="mt-1 block w-full" />
                <div v-if="form.errors.capacity && !form.isDirty" class="text-red-800">{{ form.errors.capacity }}</div>
            </div>
            <Button type="submit">Simpan</Button>
        </form>
    </AppLayout>
</template>
