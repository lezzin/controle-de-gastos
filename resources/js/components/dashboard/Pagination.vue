<script setup lang="ts">
import { computed } from 'vue';
import Button from '../ui/button/Button.vue';

interface Props {
    currentPage: number;
    totalPages: number;
    totalRows: number;
    pageSize: number;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    'update:currentPage': [number];
}>();

const paginationInfo = computed(() => {
    const start = Math.min((props.currentPage - 1) * props.pageSize + 1, props.totalRows);
    const end = Math.min(props.currentPage * props.pageSize, props.totalRows);
    return `${start}-${end} de ${props.totalRows} registros`;
});
</script>

<template>
    <div class="border-t px-4 py-2">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700 dark:text-gray-300">
                {{ paginationInfo }}
            </div>
            <div class="flex items-center gap-2">
                <Button variant="ghost" size="sm" :disabled="currentPage === 1" @click="emit('update:currentPage', currentPage - 1)">
                    Anterior
                </Button>

                <span class="text-sm text-gray-700 dark:text-gray-300"> Página {{ currentPage }} de {{ totalPages }} </span>

                <Button variant="ghost" size="sm" :disabled="currentPage === totalPages" @click="emit('update:currentPage', currentPage + 1)">
                    Próxima
                </Button>
            </div>
        </div>
    </div>
</template>
