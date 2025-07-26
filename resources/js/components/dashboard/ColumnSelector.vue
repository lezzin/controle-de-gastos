<script setup lang="ts">
import Button from '../ui/button/Button.vue';

interface Props {
    headers: string[];
    selectedColumns: string[];
}

const props = defineProps<Props>();

const emit = defineEmits<{ 'update:selectedColumns': [string[]] }>();

function toggleColumn(header: string) {
    const currentSelected = [...props.selectedColumns];
    const index = currentSelected.indexOf(header);

    if (index > -1) {
        currentSelected.splice(index, 1);
    } else {
        currentSelected.push(header);
    }

    emit('update:selectedColumns', currentSelected);
}

function selectAllColumns() {
    emit('update:selectedColumns', [...props.headers]);
}

function deselectAllColumns() {
    emit('update:selectedColumns', []);
}
</script>

<template>
    <div class="rounded-lg border p-4 shadow-sm">
        <div class="mb-3 flex items-center justify-between">
            <h3 class="font-medium text-gray-900 dark:text-white">Colunas Visíveis</h3>

            <div class="flex gap-2">
                <Button @click="selectAllColumns" variant="outline"> Selecionar Todas </Button>
                <Button variant="outline" @click="deselectAllColumns"> Desmarcar Todas </Button>
            </div>
        </div>

        <div class="flex flex-wrap gap-2">
            <label
                v-for="header in headers"
                :key="header"
                class="inline-flex items-center gap-2 rounded-md border border-secondary bg-sidebar px-3 py-1 text-sm"
            >
                <input
                    type="checkbox"
                    :checked="selectedColumns.includes(header)"
                    @change="toggleColumn(header)"
                    class="rounded border-gray-300 text-blue-600 checked:accent-primary focus:ring-blue-500"
                />

                <span class="text-gray-700 dark:text-gray-300">{{ header }}</span>
            </label>
        </div>
    </div>
</template>
