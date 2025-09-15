<script setup lang="ts">
import type {
    ColumnDef,
    ColumnFiltersState,
    ExpandedState,
    SortingState,
    VisibilityState,
} from "@tanstack/vue-table"
import {
    FlexRender,
    getCoreRowModel,
    getExpandedRowModel,
    getFilteredRowModel,
    getPaginationRowModel,
    getSortedRowModel,
    useVueTable,
} from "@tanstack/vue-table"
import { ChevronDown } from "lucide-vue-next"
import { computed, ref } from "vue"
import { valueUpdater } from "@/lib/utils"

import { Button } from "@/components/ui/button"
import {
    DropdownMenu,
    DropdownMenuCheckboxItem,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu"
import { Input } from "@/components/ui/input"
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table"
import { router } from '@inertiajs/vue3'
// import route from 'ziggy-js'

type TableLink = { url: string | null; label: string; active: boolean }

const props = defineProps<{
    data: any[]
    columns: ColumnDef<any>[]
    links?: TableLink[]
    filterColumn?: string       // filter per kolom
    globalFilter?: boolean      // true kalau mau global search
    routeName: string,
    meta?: {                  // meta pagination dari Laravel
        current_page: number
        last_page: number
        per_page: number
        total: number
    }
}>()

// state
const sorting = ref<SortingState>([])
const columnFilters = ref<ColumnFiltersState>([])
const columnVisibility = ref<VisibilityState>({})
const rowSelection = ref({})
const expanded = ref<ExpandedState>({})
const pagination = ref({ pageIndex: (props.meta?.current_page ?? 1) - 1, pageSize: (props.meta?.per_page ?? 10) })

const globalFilterValue = ref("")

const table = useVueTable({
    data: computed(() => props.data),
    columns: props.columns,
    manualPagination: true,
    pageCount: props.meta?.last_page ?? 0,
    getCoreRowModel: getCoreRowModel(),
    getSortedRowModel: getSortedRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getExpandedRowModel: getExpandedRowModel(),
    onPaginationChange: (updater) => {
        pagination.value =
            typeof updater === "function" ? updater(pagination.value) : updater
        fetdata()
    },
    onSortingChange: (updater) => valueUpdater(updater, sorting),
    onColumnFiltersChange: (updater) => valueUpdater(updater, columnFilters),
    onColumnVisibilityChange: (updater) => valueUpdater(updater, columnVisibility),
    onRowSelectionChange: (updater) => valueUpdater(updater, rowSelection),
    onExpandedChange: (updater) => valueUpdater(updater, expanded),
    onGlobalFilterChange: (val) => {
        globalFilterValue.value = val
    },
    state: {
        get sorting() {
            return sorting.value
        },
        get columnFilters() {
            return columnFilters.value
        },
        get columnVisibility() {
            return columnVisibility.value
        },
        get rowSelection() {
            return rowSelection.value
        },
        get expanded() {
            return expanded.value
        },
        get pagination() {
            return pagination.value
        },
        get globalFilter() {
            return globalFilterValue.value
        },
    },
})

const fetdata = () => {
    router.get(props.routeName, {
        page: pagination.value.pageIndex + 1,
        per_page: pagination.value.pageSize,
        sort_field: sorting.value[0]?.id,
        sort_direction: sorting.value[0]?.desc ? 'desc' : 'asc',
    }, {
        preserveState: false, preserveScroll: true, replace: true
    })
}
</script>

<template>
    <div class="w-full">
        <!-- Filter & Columns -->
        <div class="flex items-center py-4 gap-2">
            <!-- Filter per kolom -->
            <Input v-if="props.filterColumn" class="max-w-sm" placeholder="Search..."
                :model-value="table.getColumn(props.filterColumn)?.getFilterValue() as string" @update:model-value="
                    (val) => table.getColumn(props.filterColumn)?.setFilterValue(val)
                " />

            <!-- Global Filter -->
            <Input v-else-if="props.globalFilter" class="max-w-sm" placeholder="Search all..."
                :model-value="globalFilterValue" @update:model-value="(val) => (globalFilterValue = val)" />

            <!-- Columns toggle -->
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <Button variant="outline" class="ml-auto">
                        Columns
                        <ChevronDown class="ml-2 h-4 w-4" />
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end">
                    <DropdownMenuCheckboxItem v-for="column in table.getAllColumns().filter((c) => c.getCanHide())"
                        :key="column.id" class="capitalize" :model-value="column.getIsVisible()"
                        @update:model-value="(val) => column.toggleVisibility(!!val)">
                        {{ column.id }}
                    </DropdownMenuCheckboxItem>
                </DropdownMenuContent>
            </DropdownMenu>
        </div>

        <!-- Table -->
        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                        <TableHead v-for="header in headerGroup.headers" :key="header.id">
                            <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header"
                                :props="header.getContext()" />
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <template v-if="table.getRowModel().rows?.length">
                        <template v-for="row in table.getRowModel().rows" :key="row.id">
                            <TableRow :data-state="row.getIsSelected() && 'selected'">
                                <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                                    <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                                </TableCell>
                            </TableRow>
                        </template>
                    </template>
                    <TableRow v-else>
                        <TableCell :colspan="columns.length" class="h-24 text-center">
                            No results.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-end space-x-2 py-4">
            <div class="flex-1 text-sm text-muted-foreground">
                Showing
                {{ ((props.meta?.total ?? 0) > 0) ? (table.getState().pagination.pageIndex *
                    table.getState().pagination.pageSize
                    + 1) :
                0 }}
                -
                {{ Math.min(
                    table.getState().pagination.pageIndex * table.getState().pagination.pageSize +
                    table.getState().pagination.pageSize,
                    table.getRowModel().rows.length
                ) }}
                {{ props.meta?.total ? `of ${props.meta.total}` : '' }}
            </div>
            <div class="space-x-2">
                <Button variant="outline" size="sm" :disabled="!table.getCanPreviousPage()"
                    @click="table.previousPage()">
                    Previous
                </Button>
                <Button variant="outline" size="sm" :disabled="!table.getCanNextPage()" @click="table.nextPage()">
                    Next
                </Button>
            </div>
        </div>
    </div>
</template>
