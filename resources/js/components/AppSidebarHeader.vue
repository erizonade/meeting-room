<script setup lang="ts">
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger, DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { usePage, Link } from '@inertiajs/vue3';
import { LogOutIcon } from 'lucide-vue-next';

const user = usePage().props.auth.user;
</script>

<template>
    <header
        class="flex h-16 shrink-0 items-center justify-between gap-2 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4">
        <div class="flex items-center gap-2">
            <SidebarTrigger class="-ml-1" />
        </div>
        <div v-if="user">
            <SidebarMenu>
                <SidebarMenuItem>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <SidebarMenuButton>
                                <span>👋 Hi, {{ user.name }}</span>
                            </SidebarMenuButton>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="w-[--bits-dropdown-menu-anchor-width]">
                            <DropdownMenuItem as-child>
                                <Link href="/meeting/logout" class="block w-full" as="button">
                                <component :is="LogOutIcon"></component>
                                <span>Logout</span>
                                </Link>
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </SidebarMenuItem>
            </SidebarMenu>
        </div>
    </header>
</template>
