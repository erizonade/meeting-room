<script setup lang="ts">
import AppLayout from '@/layouts/App/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { Toaster } from "vue-sonner"
import 'vue-sonner/style.css'


interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <Toaster :duration="3000" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>
</template>