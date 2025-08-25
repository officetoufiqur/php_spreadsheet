<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

interface Props {
  sheets: string[];
  columns: string[];
  data: string[];
}

const props = defineProps<Props>();

// Step forms
const uploadForm = useForm<{ excelFile: File | null }>({ excelFile: null });
const sheetForm = useForm<{ selectedSheet: string }>({ selectedSheet: '' });
const columnForm = useForm<{ selectedColumn: string }>({ selectedColumn: '' });

// File change
function handleFileChange(event: Event) {
  const target = event.target as HTMLInputElement;
  if (target.files?.length) uploadForm.excelFile = target.files[0];
}

// Upload Excel
function uploadExcel() {
  uploadForm.post(route('excel.handle'), { forceFormData: true, preserveScroll: true });
}

// Select Sheet
function selectSheet() {
  sheetForm.post(route('excel.handle'), { preserveScroll: true });
}

// Select Column
function selectColumn() {
  columnForm.post(route('excel.handle'), { preserveScroll: true });
}

// Save to DB
function saveData() {
  columnForm.post(route('excel.save'), { preserveScroll: true });
}
</script>

<template>
  <div class="max-w-xl mx-auto p-6 space-y-6 bg-white shadow rounded-lg">
    <!-- Upload -->
    <div class="space-y-2">
      <label>Upload Excel File</label>
      <input type="file" accept=".xls,.xlsx" @change="handleFileChange" class="w-full border rounded-md py-2 px-3 text-black" />
      <button :disabled="!uploadForm.excelFile || uploadForm.processing" @click="uploadExcel"
        class="bg-blue-600 text-white px-4 py-2 rounded-md">
        {{ uploadForm.processing ? 'Uploading...' : 'Upload File' }}
      </button>
    </div>

    <!-- Select Sheet -->
    <div v-if="props.sheets.length" class="space-y-2">
      <label>Select Sheet</label>
      <select v-model="sheetForm.selectedSheet" class="w-full text-black border rounded-md py-2 px-3">
        <option value="">Select a sheet</option>
        <option v-for="sheet in props.sheets" :key="sheet" :value="sheet">{{ sheet }}</option>
      </select>
      <button :disabled="!sheetForm.selectedSheet || sheetForm.processing" @click="selectSheet"
        class="bg-green-600 text-white px-4 py-2 rounded-md">
        {{ sheetForm.processing ? 'Loading...' : 'Load Columns' }}
      </button>
    </div>

    <!-- Select Column -->
    <div v-if="props.columns.length" class="space-y-2">
      <label>Select Column</label>
      <select v-model="columnForm.selectedColumn" class="w-full text-black border rounded-md py-2 px-3">
        <option value="">Select a column</option>
        <option v-for="col in props.columns" :key="col" :value="col">{{ col }}</option>
      </select>
      <button :disabled="!columnForm.selectedColumn || columnForm.processing" @click="selectColumn"
        class="bg-purple-600 text-white px-4 py-2 rounded-md">
        {{ columnForm.processing ? 'Loading...' : 'Load Data' }}
      </button>
    </div>

    <!-- Preview Data -->
    <div v-if="props.data.length" class="border-t pt-4">
      <h3 class="font-semibold mb-2">Preview Data</h3>
      <ul class="list-disc pl-6 max-h-64 text-black overflow-auto">
        <li v-for="(item, index) in props.data" :key="index">{{ item }}</li>
      </ul>
      <button @click="saveData" class="bg-indigo-600 text-white px-4 py-2 mt-3 rounded-md">
        Save to Database
      </button>
    </div>
  </div>
</template>
