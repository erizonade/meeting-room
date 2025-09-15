<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardFooter, CardContent } from '@/components/ui/card';
import Heading from '@/components/Heading.vue';
import { Label } from '@/components/ui/label';
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/components/ui/select';
import { Input } from '@/components/ui/input';
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import { Consumption, MeetinRoom, Units } from '@/types/index';
import { Checkbox } from '@/components/ui/checkbox';
import { Button } from '@/components/ui/button';
import { toast } from 'vue-sonner';

const props = defineProps<{
    room: MeetinRoom[],
    unit: Units[],
    consumption: Consumption[],
    order: any
}>()
const user = usePage().props.auth.user;


const form = useForm<{
    unit_id: number | null,
    room_id?: number | null,
    start?: any | null,
    end?: any | null,
    participants?: any | null,
    type_consumption: number[],
    price?: number | null,
    date: any,
}>({
    unit_id: props.order != null ? props.order.unit_id : user.unit_id ?? null,
    room_id: props.order != null ? props.order.meeting_room_id : null,
    start: props.order != null ? props.order.start : null,
    end: props.order != null ? props.order.end : null,
    participants: props.order != null ? props.order.quantity_member : null,
    type_consumption: [],
    date: props.order != null ? props.order.date : null,
    price: props.order != null ? props.order.price : null,
})

const isUnit = user.unit_id != null ? true : false

const isCapacityValue = computed(() => {
    const capacity = props.room.find((r) => r.id === form.room_id)?.capacity
    console.info('capacity', capacity)
    return capacity ?? 0;
})

const toMinutes = (time: any) => {
    const [h, m] = time.split(":").map(Number)
    const totalMinutes = h * 60 + m;
    return totalMinutes
}

const resultConsumption = computed(() => {
    console.info('start end', form.start, form.end)
    let listConsumption: Consumption[] = []
    if (form.start == null || form.end == null) {
        return []
    }
    if (isTimes.value) {
        listConsumption = [];
        return [];
    }

    const result = props.consumption;

    const start = toMinutes(form.start)
    const end = toMinutes(form.end)
    if (end >= toMinutes('14:00')) {
        listConsumption.push(result.filter((e) => e.id == 3)[0])
    }

    if (start < toMinutes('11:00')) {
        listConsumption.push(result.filter((e) => e.id == 1)[0])
    }

    if (start < toMinutes('14:00') && end >= toMinutes('11:00')) {
        listConsumption.push(result.filter((e) => e.id == 2)[0])
    }
    return listConsumption;
})

const isTimes = computed(() => {
    if (form.start == null || form.end == null) {
        return false
    }
    let time = false;
    if (toMinutes(form.start) > toMinutes(form.end)) {
        time = true
    } else if (toMinutes(form.end) < toMinutes(form.start)) {
        time = true
    }

    return time
})


const isOverloadCapacity = computed(() => {
    return parseInt(form.participants ?? 0) > isCapacityValue.value
})

const priceTotal = computed(() => {

    const total = resultConsumption.value.reduce((a, b) => a + b.price, 0)
    return (total * parseInt(form.participants ?? 0))
})

const submit = () => {
    if (props.order != null) {
        form.put(`/meeting/order-meeting/${props.order.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                form.clearErrors();
                toast.success('Order Meeting berhasil disimpan', { duration: 3000 })
                setTimeout(() => router.visit("/meeting/order-meeting"), 1000) // redirect manual
            },
            onError: (value) => {
                toast.error(value != null ? value[0] : 'Something went wrong', { duration: 3000 })
            }
        })
        return
    }
    form.post('/meeting/order-meeting', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            form.clearErrors();
            toast.success('Order Meeting berhasil disimpan', { duration: 3000 })
            setTimeout(() => router.visit("/meeting/order-meeting"), 1000) // redirect manual
        },
        onError: (value) => {
            toast.error(value != null ? value[0] : 'Something went wrong', { duration: 3000 })
        }
    })
}

watch(resultConsumption, (newVal) => {
    form.type_consumption = newVal.map(v => v.id)
}, { immediate: true })


</script>

<template>
    <AppLayout>
        <Heading title="Ruangan Meeting" description="Form Ruangan Meeting" />
        <form @submit.prevent="submit">
            <Card class="rounded-sm">
                <CardContent class="space-y-2">
                    <h1>Informasi Ruang Meeting</h1>
                    <div class="grid grid-cols-3 space-x-2">
                        <div class="grid gap-2">
                            <Label>Unit</Label>
                            <Select v-model="form.unit_id" :disabled="isUnit">
                                <SelectTrigger class=" w-full">
                                    <SelectValue placeholder="Select Unit" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="value in props.unit" :key="value.id" :value=value.id>{{
                                        value.name }}</SelectItem>
                                </SelectContent>
                            </Select>
                            <div v-if="form.errors.unit_id && !form.unit_id" class="text-red-800">{{ form.errors.unit_id
                                }}</div>
                        </div>
                        <div class="grid gap-2">
                            <Label>Ruangan</Label>
                            <Select v-model="form.room_id">
                                <SelectTrigger class=" w-full">
                                    <SelectValue placeholder="Select Ruangan" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="value in props.room" :key="value.id" :value=value.id>{{
                                        value.name }}</SelectItem>
                                </SelectContent>
                            </Select>
                            <div v-if="form.errors.room_id && !form.room_id" class="text-red-800">{{ form.errors.room_id
                                }}</div>
                        </div>
                        <div class="grid gap-2">
                            <Label>Kapsitas</Label>
                            <Input type="number" disabled placeholder="Kapasitas" v-model="isCapacityValue"
                                class="mt-1 block w-full" />
                        </div>
                    </div>
                    <div class="border-b border-gray-300 my-4"></div>
                    <h1>Informasi Rapat</h1>
                    <div class="flex space-x-2">
                        <div class="gap flex-1">
                            <Label>Tanggal</Label>
                            <Input type="date" placeholder="Tanggal" v-model="form.date" class="mt-1 block w-full" />
                            <div v-if="form.errors.date && !form.date" class="text-red-800">{{ form.errors.date }}</div>
                        </div>
                        <div class="gap flex-1">
                            <Label>Waktu Mulai</Label>
                            <Input type="time" placeholder="Tanggal" v-model="form.start" class="mt-1 block w-full" />
                            <div v-if="form.errors.start && !form.start" class="text-red-800">{{ form.errors.start }}
                            </div>
                        </div>
                        <div class="gap flex-1">
                            <Label>Waktu Selesai</Label>
                            <Input type="time" placeholder="Tanggal" v-model="form.end" class="mt-1 block w-full" />
                            <div v-if="form.errors.end && !form.end" class="text-red-800">{{
                                form.errors.end }}</div>
                        </div>
                    </div>

                    <div class="gap gap-2">
                        <Label>Jumlah Peserta</Label>
                        <Input type="number" min="1" placeholder="Jumlah Peserta" v-model="form.participants"
                            class="mt-1 block w-36" />
                        <div v-if="isOverloadCapacity" class="text-red-800">Kapasitas ruangan tidak mencukupi</div>
                        <div v-if="form.errors.participants && !form.participants" class="text-red-800">{{
                            form.errors.participants }}</div>
                    </div>

                    <div class="gap gap-2" v-if="(priceTotal ?? 0) > 0">
                        <Label>Konsumsi Rapat</Label>
                        <div v-for="value in resultConsumption" :key="value.id">
                            <div class="flex items-center space-x-2">
                                <Checkbox :id="`consumption-${value.id}`" :value="value.id"
                                    :model-value="form.type_consumption.includes(value.id)" @update:model-value="(val) => {
                                        if (val === true && !form.type_consumption.includes(value.id)) {
                                            form.type_consumption.push(value.id);
                                        } else if (val === false) {
                                            form.type_consumption = form.type_consumption.filter(id => id !== value.id)
                                        }
                                    }" />
                                <label :for="`consumption-${value.id}`"
                                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    {{ value.name }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="gap gap-2">
                        <Label>Nominal Konsumsi</Label>
                        <Input type="number" disabled v-model="priceTotal" placeholder="Nominal Konsumsi"
                            class="mt-1 block w-36" />
                    </div>
                </CardContent>
                <CardFooter class="flex justify-end">
                    <Link href="/meeting/order-meeting"><Button type="button" class="bg-red-500/100">Cancel</Button>
                    </Link>

                    <Button class="bg-green-500/100 ml-2" :disabled="isTimes || isOverloadCapacity"
                        type="submit">Simpan</Button>
                </CardFooter>
            </Card>
        </form>
    </AppLayout>
</template>
