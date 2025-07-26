<script setup lang="ts">
import ColumnSelector from '@/components/dashboard/ColumnSelector.vue';
import DataTable from '@/components/dashboard/DataTable.vue';
import FileInfo from '@/components/dashboard/FileInfo.vue';
import SearchAndFilters from '@/components/dashboard/SearchAndFilters.vue';
import { computed, ref } from 'vue';

interface Props {
    parsedData: { headers: string[]; rows: Record<string, string>[] };
    file: File | null;
}

const props = defineProps<Props>();

const searchTerm = ref('');
const currentPage = ref(1);
const pageSize = ref(10);
const selectedColumns = ref<string[]>(props.parsedData.headers || []);

const filteredRows = computed(() => {
    if (!searchTerm.value.trim()) return props.parsedData.rows;

    const searchLower = searchTerm.value.toLowerCase();
    return props.parsedData.rows.filter((row) => selectedColumns.value.some((column) => row[column]?.toLowerCase().includes(searchLower)));
});

const visibleHeaders = computed(() => {
    return props.parsedData.headers.filter((header) => selectedColumns.value.includes(header));
});

const total = computed(() => {
    const valorColumn = props.parsedData.headers.find(
        (header) => header.toLowerCase().includes('valor') || header.toLowerCase().includes('price') || header.toLowerCase().includes('amount'),
    );

    if (!valorColumn) return '0,00';

    const sum = filteredRows.value.reduce((sum, row) => {
        const rawValue = row[valorColumn]?.toString() || '';
        const cleanValue = rawValue
            .replace(/[R$\s]/g, '')
            .replace(/\./g, '')
            .replace(',', '.');

        const numericValue = parseFloat(cleanValue);
        return sum + (isNaN(numericValue) ? 0 : numericValue);
    }, 0);

    return sum.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
});

function exportData() {
    const csvContent = [
        visibleHeaders.value.join(','),
        ...filteredRows.value.map((row) => visibleHeaders.value.map((header) => `"${row[header] || ''}"`).join(',')),
    ].join('\n');

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'filtered_data.csv';
    link.click();
}

defineExpose({ exportData });
</script>

<template>
    <div class="space-y-6">
        <FileInfo :file="file" :total-rows="parsedData.rows.length" :total-columns="parsedData.headers.length" :total-value="total" />

        <SearchAndFilters v-model:search-term="searchTerm" v-model:page-size="pageSize" @search-change="currentPage = 1" />

        <ColumnSelector :headers="parsedData.headers" v-model:selected-columns="selectedColumns" />

        <DataTable :headers="visibleHeaders" :rows="filteredRows" v-model:current-page="currentPage" :page-size="pageSize" />
    </div>
</template>
