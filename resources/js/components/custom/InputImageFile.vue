<script setup lang="ts">
import { cn } from '@/lib/utils';
import { ref, type HTMLAttributes } from 'vue';
import { Button } from '../ui/button';

const props = defineProps<{
    class?: HTMLAttributes['class'];
    placeholder?: string;
}>();

const modelValue = defineModel();
const refModelValue = ref<HTMLInputElement | null>(null);
const imagePreview = ref<string | null>(null);

const previewImage = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    if (file) {
        modelValue.value = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const clearImage = () => {
    if (refModelValue.value) {
        refModelValue.value.value = '';
    }
    imagePreview.value = null;
    modelValue.value = null;
};

</script>

<template>
    <input ref="refModelValue" :class="cn(
        'flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm',
        props.class,
    )
        " :placeholder="props.placeholder" accept="image/*" @change="previewImage" type="file" />


    <div v-if="imagePreview"
        class="mt-3 overflow-hidden border rounded-lg shadow-xs border-neutral-200 dark:border-neutral-800">
        <img :src="imagePreview" class="object-cover w-full max-h-[200px]" alt="Project image preview" />
        <div class="flex items-center justify-between p-2 bg-neutral-50 dark:bg-neutral-900">
            <span class="text-xs text-neutral-500">Preview</span>
            <Button variant="ghost" size="sm" @click="clearImage"
                class="text-xs text-destructive hover:bg-destructive/10" type="button">
                Remove
            </Button>
        </div>
    </div>
</template>
