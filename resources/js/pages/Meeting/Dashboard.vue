<script setup lang="ts">
import { Card } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/meeting',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Card class=""></Card>
    </AppLayout>
</template>