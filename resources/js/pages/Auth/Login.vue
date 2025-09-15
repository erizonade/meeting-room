<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

import { useForm } from '@inertiajs/vue3';
import { toast, Toaster } from "vue-sonner";
import 'vue-sonner/style.css'

const form = useForm({
    email: '',
    password: '',
})

const submit = () => {
    form.post('/login', {
        preserveScroll: true,
        onError: () => {
            toast.error(form.hasErrors ? 'Email or password incorrect' : 'Something went wrong', { duration: 3000 })
        },
        onSuccess: () => {
            toast.success('Login successful', { duration: 3000 })
        }
    })
}

</script>

<template>
    <div class="min-h-dvh flex justify-center items-center">
        <Form class="w-full" @submit.prevent="submit">
            <Card class="mx-auto flex max-w-sm ">
                <CardHeader>
                    <div class="flex justify-center">
                        <img src="/assets/logo.png" alt="logo" class="h-25" />
                    </div>
                    <CardTitle class="text-center">Sign in</CardTitle>
                    <CardDescription class="text-center">Sign in to your account</CardDescription>
                </CardHeader>
                <CardContent class="space-y-2">
                    <Label>Email</Label>
                    <Input type="email" placeholder="Email" v-model="form.email" />
                    <div v-if="form.errors.email && !form.isDirty" class="text-red-800">{{ form.errors.email }}</div>
                    <Label>Password</Label>
                    <Input type="password" placeholder="Password" v-model="form.password" />
                    <div v-if="form.errors.password && !form.isDirty" class="text-red-800">{{ form.errors.password }}
                    </div>
                    <Button type="submit" class="w-full">Login</Button>
                </CardContent>
                <Toaster :duration="3000" />
            </Card>
        </Form>
    </div>
</template>