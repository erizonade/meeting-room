<script setup lang="ts">
import type {
    ColumnDef,
} from "@tanstack/vue-table"
import { h } from "vue"
import AppLayout from "@/layouts/AppLayout.vue"
import { User } from "@/types"
import DataTable from "@/components/DataTable.vue"
import { router } from "@inertiajs/vue3"
import { Button } from "@/components/ui/button"
import { toast } from "vue-sonner"


//props
const props = defineProps<{
    users: {
        data: User[],
        current_page: number
        last_page: number
        per_page: number
        total: number
        links: { url: string | null; label: string; active: boolean }[]
    }
}>();

const data = props.users.data;

const handleEdit = (item: User) => {
    console.log("Editing", item)
    router.visit('user/' + item.id + '/edit')
}

const handleDelete = (item: User) => {
    console.log("Deleting", item)
    router.delete('user/' + item.id, {
        preserveScroll: true,
        preserveState: false, // <--- ini akan muat ulang seluruh halaman
        onSuccess: () => {
            console.log("Deleted", item)
            toast.success('User berhasil dihapus', { duration: 3000 })
            setTimeout(() => router.visit("/meeting/user"), 1000) // redirect manual

        },
        onError: () => {
            console.log("Error deleting", item)
        },
        onFinish: () => {
            console.log("Finished deleting", item)
        }
    })

}


const columns: ColumnDef<User>[] = [
    {
        accessorKey: "id",
        header: "No",
        cell: ({ row }) => h("div", { class: "capitalize" }, (props.users.current_page - 1) * props.users.per_page + row.index + 1),
    },
    {
        accessorKey: "name",
        header: "Name",
        cell: ({ row }) => h("div", { class: "lowercase" }, row.getValue("name")),
    },
    {
        accessorKey: "email",
        header: "Email",
        cell: ({ row }) => h("div", { class: "lowercase" }, row.getValue("email")),
    },
    {
        accessorKey: "role",
        header: "Role",
        cell: ({ row }) => {
            const role = row.getValue("role");
            switch (role) {
                case "super-admin":
                    return h("div", { class: "capitalize" }, 'Super Admin')
                    break;
                default:
                    return h("div", { class: "capitalize" }, row.getValue("role"))
                    break;
            }
        },
    },
    {
        accessorKey: "unit_name",
        header: "Unit",
        cell: ({ row }) => h("div", { class: "lowercase" }, row.getValue("unit_name")),
    },
    {
        id: "actions",
        enableHiding: false,
        cell: (props) => h('div', { class: 'flex items-center gap-2' }, [
            h('button', {
                class:
                    'inline-flex items-center gap-1 rounded-md border border-blue-600 px-2.5 py-1.5 text-sm font-medium text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500',
                type: 'button',
                onClick: () => handleEdit(props.row.original)
            }, 'Edit'),
            h('button', {
                class: 'inline-flex items-center gap-1 rounded-md border border-red-600 px-2.5 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500',
                type: 'button',
                onClick: () => handleDelete(props.row.original)
            }, 'Delete'),
        ]),
    },
]


</script>

<template>
    <AppLayout>
        <Button @click="router.visit('user/create')">Tambah</Button>
        <DataTable :columns="columns" :data="data" :meta="{
            current_page: users.current_page,
            last_page: users.last_page,
            per_page: users.per_page,
            total: users.total
        }" :links="users.links" :globalFilter="true" routeName="user" />
    </AppLayout>
</template>