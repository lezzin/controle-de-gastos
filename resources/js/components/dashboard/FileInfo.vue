<script setup lang="ts">
import { FileSpreadsheet } from 'lucide-vue-next';

interface Props {
    file: File | null;
    totalRows: number;
    totalColumns: number;
    totalValue: string;
}

defineProps<Props>();
</script>

<template>
    <div class="rounded-lg border p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <FileSpreadsheet class="h-5 w-5 text-primary" />

                <div>
                    <p class="font-medium text-gray-900 dark:text-white">{{ file?.name }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ totalRows }} registros, {{ totalColumns }} colunas</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-lg font-bold text-green-700 dark:text-green-400">Total: R$ {{ totalValue }}</p>
            </div>
        </div>
    </div>
</template>
