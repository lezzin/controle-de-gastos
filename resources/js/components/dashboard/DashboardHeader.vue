<script setup lang="ts">
import { Download, X } from 'lucide-vue-next';
import Button from '../ui/button/Button.vue';

interface Props {
    hasData: boolean;
}

defineProps<Props>();

const emit = defineEmits<{
    clearData: [];
    exportData: [];
}>();
</script>

<template>
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Análise de CSV</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">Faça upload e analise seus arquivos CSV</p>
        </div>

        <div v-if="hasData" class="flex gap-2">
            <Button @click="emit('exportData')" variant="outline">
                <Download class="h-4 w-4" />
                Exportar
            </Button>

            <Button @click="emit('clearData')" variant="secondary">
                <X class="h-4 w-4" />
                Limpar
            </Button>
        </div>
    </div>
</template>
