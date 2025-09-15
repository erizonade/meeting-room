<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import { ColumnDef } from '@tanstack/vue-table';
import { h } from 'vue';
import DataTable from '@/components/DataTable.vue';

//props
const props = defineProps<{
    order: {
        data: any[],
        current_page: number
        last_page: number
        per_page: number
        total: number
        links: { url: string | null; label: string; active: boolean }[]
    }
}>();

const data = props.order.data;

console.info(data);

const handleEdit = (item: any) => {
    console.log("Editing", item)
    router.visit('order-meeting/' + item.id + '/edit')
}

const handleDelete = (item: any) => {
    console.log("Deleting", item)
    router.delete('order-meeting/' + item.id, {
        preserveScroll: true,
        preserveState: false, // <--- ini akan muat ulang seluruh halaman
        onSuccess: () => {
            console.log("Deleted", item)
            toast.success('Unit berhasil dihapus', { duration: 3000 })
            setTimeout(() => router.visit("/meeting/order-meeting"), 1000) // redirect manual

        },
        onError: () => {
            console.log("Error deleting", item)
        },
        onFinish: () => {
            console.log("Finished deleting", item)
        }
    })

}


const columns: ColumnDef<any>[] = [
    {
        accessorKey: "id",
        header: "No",
        cell: ({ row }) => h("div", { class: "capitalize" }, (props.order.current_page - 1) * props.order.per_page + row.index + 1),
    },
    {
        accessorKey: "unit_name",
        header: "Unit",
        cell: ({ row }) => h("div", { class: "lowercase" }, row.getValue("unit_name")),
    },
    {
        accessorKey: "room_name",
        header: "Ruangan Meeting",
        cell: ({ row }) => h("div", { class: "lowercase" }, row.getValue("room_name")),
    },
    {
        accessorKey: "user_name",
        header: "Yang mengajukan",
        cell: ({ row }) => h("div", { class: "lowercase" }, row.getValue("user_name")),
    },
    {
        accessorKey: "date",
        header: "Tanggal Pemakaian",
        cell: ({ row }) => h("div", { class: "lowercase" }, `${row.getValue("date")}`),
    },
    {
        accessorKey: "duration",
        header: "Waktu Pemakaian",
        cell: ({ row }) => h("div", { class: "lowercase" }, `${row.getValue("duration")}`),
    },
    {
        accessorKey: "quantity_member",
        header: "Total Peserta",
        cell: ({ row }) => h("div", { class: "lowercase" }, `${row.getValue("quantity_member")}`),
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
        <Button @click="router.visit('order-meeting/create')">Tambah</Button>
        <DataTable :columns="columns" :data="data" :meta="{
            current_page: order.current_page,
            last_page: order.last_page,
            per_page: order.per_page,
            total: order.total
        }" :links="order.links" :globalFilter="true" routeName="unit" />
    </AppLayout>
</template>