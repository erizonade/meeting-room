<script setup lang="ts">
import type {
    ColumnDef,
} from "@tanstack/vue-table"
import { h } from "vue"
import AppLayout from "@/layouts/AppLayout.vue"
import { Units } from "@/types"
import DataTable from "@/components/DataTable.vue"
import { router } from "@inertiajs/vue3"
import { Button } from "@/components/ui/button"
import { toast } from "vue-sonner"


//props
const props = defineProps<{
    units: {
        data: Units[],
        current_page: number
        last_page: number
        per_page: number
        total: number
        links: { url: string | null; label: string; active: boolean }[]
    }
}>();

const data = props.units.data;

const handleEdit = (item: Units) => {
    console.log("Editing", item)
    router.visit('unit/' + item.id + '/edit')
}

const handleDelete = (item: Units) => {
    console.log("Deleting", item)
    router.delete('unit/' + item.id, {
        preserveScroll: true,
        preserveState: false, // <--- ini akan muat ulang seluruh halaman
        onSuccess: () => {
            console.log("Deleted", item)
            toast.success('Unit berhasil dihapus', { duration: 3000 })
            setTimeout(() => router.visit("/meeting/unit"), 1000) // redirect manual

        },
        onError: () => {
            console.log("Error deleting", item)
        },
        onFinish: () => {
            console.log("Finished deleting", item)
        }
    })

}


const columns: ColumnDef<Units>[] = [
    {
        accessorKey: "id",
        header: "No",
        cell: ({ row }) => h("div", { class: "capitalize" }, (props.units.current_page - 1) * props.units.per_page + row.index + 1),
    },
    {
        accessorKey: "name",
        header: "Name",
        cell: ({ row }) => h("div", { class: "lowercase" }, row.getValue("name")),
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
        <Button @click="router.visit('unit/create')">Tambah</Button>
        <DataTable :columns="columns" :data="data" :meta="{
            current_page: units.current_page,
            last_page: units.last_page,
            per_page: units.per_page,
            total: units.total
        }" :links="units.links" :globalFilter="true" routeName="unit" />
    </AppLayout>
</template>