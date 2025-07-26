<script setup lang="ts">
import { Search } from 'lucide-vue-next';

interface Props {
    searchTerm: string;
    pageSize: number;
}

defineProps<Props>();

const emit = defineEmits<{
    'update:searchTerm': [string];
    'update:pageSize': [number];
    searchChange: [];
}>();

const PAGE_SIZE_OPTIONS = [10, 25, 50, 100];

function updateSearchTerm(value: string) {
    emit('update:searchTerm', value);
    emit('searchChange');
}
</script>

<template>
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="relative max-w-md flex-1">
            <Search class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-gray-400" />
            <input
                :value="searchTerm"
                @input="updateSearchTerm(($event.target as HTMLInputElement).value)"
                type="text"
                autocomplete="off"
                placeholder="Buscar nos dados..."
                class="w-full rounded-lg border py-2 pr-4 pl-10 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none dark:text-white"
            />
        </div>

        <div class="flex items-center gap-4">
            <select
                :value="pageSize"
                @change="emit('update:pageSize', parseInt(($event.target as HTMLSelectElement).value))"
                class="rounded-lg border px-3 py-2 text-sm"
            >
                <option v-for="size in PAGE_SIZE_OPTIONS" :key="size" :value="size" class="dark:text-black">{{ size }} por página</option>
            </select>
        </div>
    </div>
</template>
