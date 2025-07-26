<script setup lang="ts">
import axios from 'axios';
import { Loader2, Upload } from 'lucide-vue-next';
import { ref } from 'vue';

const emit = defineEmits<{
    fileUploaded: [{ parsedData: any; file: File }];
    uploadError: [string];
}>();

const isLoading = ref(false);
const isDragOver = ref(false);

function handleFileChange(event: Event) {
    const target = event.target as HTMLInputElement;
    if (target.files?.length) {
        processFile(target.files[0]);
    }
}

function handleDrop(event: DragEvent) {
    event.preventDefault();
    isDragOver.value = false;

    const files = event.dataTransfer?.files;
    if (files?.length) {
        processFile(files[0]);
    }
}

function handleDragOver(event: DragEvent) {
    event.preventDefault();
    isDragOver.value = true;
}

function handleDragLeave() {
    isDragOver.value = false;
}

function processFile(selectedFile: File) {
    if (!isValidCSV(selectedFile)) {
        emit('uploadError', 'Por favor, selecione um arquivo CSV válido.');
        return;
    }

    uploadFile(selectedFile);
}

function isValidCSV(file: File): boolean {
    const validTypes = ['text/csv', 'application/csv', 'text/plain'];
    const validExtensions = ['.csv'];

    return validTypes.includes(file.type) || validExtensions.some((ext) => file.name.toLowerCase().endsWith(ext));
}

async function uploadFile(file: File) {
    if (isLoading.value) return;

    const formData = new FormData();
    formData.append('csv_file', file);

    isLoading.value = true;

    try {
        const response = await axios.post('/csv/parse', formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
        });

        emit('fileUploaded', {
            parsedData: response.data,
            file: file,
        });
    } catch (err: any) {
        console.error('Erro ao enviar o CSV:', err);
        const errorMessage = err.response?.data?.message || 'Erro ao processar o arquivo CSV. Tente novamente.';
        emit('uploadError', errorMessage);
    } finally {
        isLoading.value = false;
    }
}
</script>

<template>
    <div
        :class="[
            'relative rounded-xl border-2 border-dashed p-8 text-center transition-colors',
            isDragOver && 'border-blue-400 bg-blue-50 dark:border-blue-500 dark:bg-blue-900/20',
        ]"
        @drop="handleDrop"
        @dragover="handleDragOver"
        @dragleave="handleDragLeave"
    >
        <label for="dropzone-file" class="flex cursor-pointer flex-col items-center justify-center">
            <div v-if="isLoading" class="flex flex-col items-center">
                <Loader2 class="mb-4 h-8 w-8 animate-spin text-blue-500" />
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Processando arquivo...</p>
            </div>
            <div v-else class="flex flex-col items-center">
                <Upload class="mb-4 h-10 w-10 text-gray-400" />
                <p class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-300">Faça upload de um arquivo CSV</p>
                <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-semibold">Clique para selecionar</span> ou arraste o arquivo aqui
                </p>
                <p class="text-xs text-gray-400">Apenas arquivos .csv são aceitos</p>
            </div>
        </label>

        <input
            id="dropzone-file"
            type="file"
            class="hidden"
            accept=".csv,text/csv,application/csv"
            @change="handleFileChange"
            :disabled="isLoading"
        />
    </div>
</template>
