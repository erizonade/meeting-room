<script setup lang="ts">
import {
    SidebarGroup,
    //   SidebarGroupContent,
    //   SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { Link, usePage } from '@inertiajs/vue3';
import { BookUserIcon, Building2, Home, LucideIcon, User2 } from 'lucide-vue-next';


const user = usePage().props.auth;
const menus: Record<string, { name: string; href: string; icon: LucideIcon }[]> = {
    'super-admin': [
        {
            name: 'Dashboard',
            href: '/meeting',
            icon: Home,
        },
        {
            name: 'Unit',
            href: '/meeting/unit',
            icon: BookUserIcon,
        },
        {
            name: 'Ruang Meeting',
            href: '/meeting/meeting-room',
            icon: Building2,
        },
        {
            name: 'User',
            href: '/meeting/user',
            icon: User2,
        },
        {
            name: 'Pengajuan R.Meeting',
            href: '/meeting/order-meeting',
            icon: Building2,
        }
    ],
    'admin': [
        {
            name: 'Dashboard',
            href: '/meeting',
            icon: Home,
        },
        {
            name: 'Pengajuan R.Meeting',
            href: '/meeting/order-meeting',
            icon: Building2,
        }
    ],
    'anggota': [
        {
            name: 'Dashboard',
            href: '/meeting',
            icon: Home,
        },
        {
            name: 'Pengajuan R.Meeting',
            href: '/meeting/order-meeting',
            icon: Building2,
        }
    ],
};
console.log('this role', user.user.role)

const isRole = user.user.role;

// const isRole = computed(() => user.role);

</script>

<template>
    <SidebarGroup>
        <SidebarMenu>
            <SidebarMenuItem v-for="value in menus[isRole]" :key="value.name">
                <SidebarMenuButton as-child>
                    <Link :href="value.href" class="text-sm">
                    <component :is="value.icon"></component>
                    <span>{{ value.name }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
