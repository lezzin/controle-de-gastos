<script setup lang="ts">
import { AlertCircle } from 'lucide-vue-next';

interface Props {
    message: string;
}

defineProps<Props>();
</script>

<template>
    <div class="rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-900/20">
        <div class="flex items-center gap-2">
            <AlertCircle class="h-5 w-5 text-red-600 dark:text-red-400" />
            <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ message }}</p>
        </div>
    </div>
</template>