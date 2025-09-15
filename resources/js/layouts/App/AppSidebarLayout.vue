<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppSideBar from '@/components/AppSideBar.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import BreadCrumbs from '@/components/BreadCrumbs.vue';
import { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

</script>

<template>
    <AppShell variant="sidebar">
        <AppSideBar />
        <AppContent variant="sidebar" class="overflow-x-hidden">

            <AppSidebarHeader />
            <main class="p-4">
                <BreadCrumbs :breadcrumbs="breadcrumbs" />
                <slot />
            </main>
        </AppContent>
    </AppShell>
</template>