<script setup lang="ts">
import type {
    ColumnDef,
} from "@tanstack/vue-table"
import { h } from "vue"
import AppLayout from "@/layouts/AppLayout.vue"
import { MeetinRoom } from "@/types"
import DataTable from "@/components/DataTable.vue"
import { router } from "@inertiajs/vue3"
import { Button } from "@/components/ui/button"
import { toast } from "vue-sonner"

//props
const props = defineProps<{
    room: {
        data: MeetinRoom[],
        current_page: number
        last_page: number
        per_page: number
        total: number
        links: { url: string | null; label: string; active: boolean }[]
    }
}>();

const data = props.room.data;

const handleEdit = (item: MeetinRoom) => {
    console.log("Editing", item)
    router.visit('meeting-room/' + item.id + '/edit')
}

const handleDelete = (item: MeetinRoom) => {
    console.log("Deleting", item)
    router.delete('meeting-room/' + item.id, {
        preserveScroll: true,
        preserveState: false, // <--- ini akan muat ulang seluruh halaman
        onSuccess: () => {
            console.log("Deleted", item)
            toast.success('Meeting Room berhasil dihapus', { duration: 3000 })
        },
        onError: () => {
            console.log("Error deleting", item)
        }
    })

}


const columns: ColumnDef<MeetinRoom>[] = [
    {
        accessorKey: "id",
        header: "No",
        cell: ({ row }) => h("div", { class: "capitalize" }, (props.room.current_page - 1) * props.room.per_page + row.index + 1),
    },
    {
        accessorKey: "name",
        header: "Name",
        cell: ({ row }) => h("div", { class: "lowercase" }, row.getValue("name")),
    },
    {
        accessorKey: "capacity",
        header: "Kapasitas",
        cell: ({ row }) => h("div", { class: "lowercase" }, row.getValue("capacity")),
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
        <Button @click="router.visit('meeting-room/create')">Tambah</Button>
        <DataTable :columns="columns" :data="data" :meta="{
            current_page: room.current_page,
            last_page: room.last_page,
            per_page: room.per_page,
            total: room.total
        }" :links="room.links" :globalFilter="true" routeName="unit" />
    </AppLayout>
</template>
