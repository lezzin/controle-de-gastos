<script setup lang="ts">
import { computed } from 'vue';
import Pagination from './Pagination.vue';

interface Props {
    headers: string[];
    rows: Record<string, string>[];
    currentPage: number;
    pageSize: number;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    'update:currentPage': [number];
}>();

const paginatedRows = computed(() => {
    const start = (props.currentPage - 1) * props.pageSize;
    const end = start + props.pageSize;
    return props.rows.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(props.rows.length / props.pageSize);
});
</script>

<template>
    <div class="overflow-hidden rounded-lg border shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-accent">
                    <tr>
                        <th v-for="header in headers" :key="header" class="px-6 py-4 text-left text-xs font-medium tracking-wider uppercase">
                            {{ header }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr v-for="(row, rowIndex) in paginatedRows" :key="rowIndex" class="hover:bg-accent">
                        <td v-for="header in headers" :key="header" class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100">
                            {{ row[header] || '-' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination
            v-if="totalPages > 1"
            :current-page="currentPage"
            :total-pages="totalPages"
            :total-rows="rows.length"
            :page-size="pageSize"
            @update:current-page="emit('update:currentPage', $event)"
        />
    </div>
</template>
