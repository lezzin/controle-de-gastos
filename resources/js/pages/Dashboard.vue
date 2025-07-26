<script setup lang="ts">
import DashboardHeader from '@/components/dashboard/DashboardHeader.vue';
import DataAnalysis from '@/components/dashboard/DataAnalysis.vue';
import ErrorAlert from '@/components/dashboard/ErrorAlert.vue';
import FileUpload from '@/components/dashboard/FileUpload.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ComponentPublicInstance, ref } from 'vue';

interface DataAnalysisRef {
    exportData: () => void;
}

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Dashboard', href: '/dashboard' }];
const dataAnalysis = ref<ComponentPublicInstance & DataAnalysisRef>();

const parsedData = ref<{ headers: string[]; rows: Record<string, string>[] } | null>(null);
const file = ref<File | null>(null);
const error = ref<string | null>(null);

function handleFileUploaded(data: { parsedData: any; file: File }) {
    parsedData.value = data.parsedData;
    file.value = data.file;
    error.value = null;
}

function handleUploadError(errorMessage: string) {
    error.value = errorMessage;
    parsedData.value = null;
    file.value = null;
}

function handleClearData() {
    parsedData.value = null;
    file.value = null;
    error.value = null;
}

function onExport() {
    dataAnalysis.value?.exportData();
}
</script>

<template>
    <Head title="Dashboard - Análise de CSV" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto flex w-full max-w-7xl flex-col gap-6 p-4">
            <DashboardHeader :has-data="!!parsedData" @clear-data="handleClearData" @export-data="onExport" />

            <ErrorAlert v-if="error" :message="error" />

            <FileUpload v-if="!parsedData" @file-uploaded="handleFileUploaded" @upload-error="handleUploadError" />

            <DataAnalysis v-if="parsedData" ref="dataAnalysis" :parsed-data="parsedData" :file="file" />
        </div>
    </AppLayout>
</template>
