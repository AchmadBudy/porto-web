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
const imagePreviews = ref<{ file: File; preview: string }[]>([]);

const previewImages = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const files = target.files;

    if (files && files.length > 0) {
        const fileArray = Array.from(files);
        modelValue.value = fileArray;

        // Create preview URLs for each file
        const newPreviews = fileArray.map(file => ({
            file,
            preview: URL.createObjectURL(file)
        }));

        imagePreviews.value = newPreviews;
    }
};

const clearAllImages = () => {
    if (refModelValue.value) {
        refModelValue.value.value = '';
    }
    // Revoke object URLs to prevent memory leaks
    imagePreviews.value.forEach(item => URL.revokeObjectURL(item.preview));
    imagePreviews.value = [];
    modelValue.value = null;
};

const removeImage = (index: number) => {
    if (imagePreviews.value.length === 0) return;

    // Revoke the URL to prevent memory leaks
    URL.revokeObjectURL(imagePreviews.value[index].preview);

    // Remove the image from the previews
    imagePreviews.value.splice(index, 1);

    // Update the model value
    if (imagePreviews.value.length === 0) {
        modelValue.value = null;
        if (refModelValue.value) {
            refModelValue.value.value = '';
        }
    } else {
        modelValue.value = imagePreviews.value.map(item => item.file);
    }
};
</script>

<template>
    <input ref="refModelValue" :class="cn(
        'flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm',
        props.class,
    )
        " :placeholder="props.placeholder" accept="image/*" @change="previewImages" type="file" multiple />

    <div v-if="imagePreviews.length > 0" class="mt-3">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium">Preview ({{ imagePreviews.length }} images)</span>
            <Button variant="ghost" size="sm" @click="clearAllImages"
                class="text-xs text-destructive hover:bg-destructive/10" type="button">
                Remove All
            </Button>
        </div>

        <div class="grid grid-cols-2 gap-3 md:grid-cols-3">
            <div v-for="(item, index) in imagePreviews" :key="index"
                class="overflow-hidden border rounded-lg shadow-xs border-neutral-200 dark:border-neutral-800">
                <img :src="item.preview" class="object-cover w-full h-32" alt="Image preview" />
                <div class="flex items-center justify-between p-2 bg-neutral-50 dark:bg-neutral-900">
                    <span class="text-xs truncate text-neutral-500 max-w-24">{{ item.file.name }}</span>
                    <Button variant="ghost" size="sm" @click="() => removeImage(index)"
                        class="text-xs text-destructive hover:bg-destructive/10" type="button">
                        Remove
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>