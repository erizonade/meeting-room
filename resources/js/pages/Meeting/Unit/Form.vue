<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { router, useForm } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';

const props = defineProps({
    unit: Object
})

const form = useForm({
    name: props.unit?.name ?? '',
});

const submit = () => {
    if (props.unit) {
        form.put(`/meeting/unit/${props.unit.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                form.clearErrors();
                toast.success('Unit berhasil disimpan', { duration: 3000 })
                setTimeout(() => router.visit("/meeting/unit"), 1000) // redirect manual
            },
            onError: () => {
                toast.error('Something went wrong', { duration: 3000 })
            }
        })

        return;
    }
    form.post('/meeting/unit', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            form.clearErrors();
            toast.success('Unit berhasil disimpan', { duration: 3000 })
            setTimeout(() => router.visit("/meeting/unit"), 1000) // redirect manual
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
                <Label>Nama Unit</Label>
                <Input type="text" placeholder="Nama Unit" v-model="form.name" class="mt-1 block w-full" />
                <div v-if="form.errors.name && !form.isDirty" class="text-red-800">{{ form.errors.name }}</div>
            </div>
            <Button type="submit">Simpan</Button>
        </form>
    </AppLayout>
</template>
