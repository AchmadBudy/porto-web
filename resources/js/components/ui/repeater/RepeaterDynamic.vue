<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { PlusCircleIcon, XCircleIcon } from 'lucide-vue-next';
import { computed, type PropType } from 'vue';
import InputError from '@/components/InputError.vue';

// Define interface untuk field configuration
interface FieldConfig {
    name: string;
    label: string;
    placeholder?: string;
    type?: string;
    acceptFile?: string;
}

const props = defineProps({
    modelValue: {
        type: Array as PropType<Array<Record<string, any>>>,
        required: true
    },
    fields: {
        type: Array as PropType<FieldConfig[]>,
        required: true
    },
    disabled: {
        type: Boolean,
        default: false
    },
    errors: {
        type: [Array, Object] as PropType<Array<Record<string, string>> | Record<string, string>>,
        default: () => []
    }
});

// Convert errors object to array format if necessary
const normalizedErrors = computed(() => {
    // If errors is already an array, return it
    if (Array.isArray(props.errors)) {
        return props.errors;
    }

    // Otherwise, assume it's a Laravel/Inertia errors object and convert it
    if (typeof props.errors === 'object' && props.errors !== null) {
        return props.modelValue.map((_, index) => {
            // Create an object to hold errors for this item
            const itemErrors: Record<string, string> = {};

            // For each field, check if there are errors
            props.fields.forEach(field => {
                const errorKey = `socials.${index}.${field.name}`;
                if (!Array.isArray(props.errors) && props.errors[errorKey]) {
                    itemErrors[field.name] = props.errors[errorKey];
                }
            });

            return itemErrors;
        });
    }

    // Fallback to empty array
    return [];
});

const emit = defineEmits(['update:modelValue']);

const addAttribute = () => {
    const newItem = props.fields.reduce((acc: Record<string, any>, field) => {
        acc[field.name] = '';
        return acc;
    }, {});

    const newAttributes = [...props.modelValue, newItem];
    emit('update:modelValue', newAttributes);
};

const removeAttribute = (index: number) => {
    const newAttributes = props.modelValue.filter((_, i) => i !== index);
    emit('update:modelValue', newAttributes);
};


const handleFileInput = (event: Event, index: number, fieldName: string) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        const file = target.files[0];
        const newAttributes = [...props.modelValue];
        newAttributes[index][fieldName] = file;
        emit('update:modelValue', newAttributes);
    }
};
</script>

<template>
    <div class="p-4 space-y-3 border rounded-md border-neutral-200 dark:border-neutral-800">
        <div v-for="(attribute, index) in modelValue" :key="index" class="grid gap-3">
            <div class="flex items-start gap-3">
                <!-- Loop melalui setiap field yang didefinisikan -->
                <div v-for="field in fields" :key="field.name" class="grid flex-1 gap-1">
                    <Label :for="`attribute-${field.name}-${index}`"
                        class="text-xs font-normal text-neutral-600 dark:text-neutral-400">
                        {{ field.label }}
                    </Label>

                    <Input :id="`attribute-${field.name}-${index}`" :type="field.type || 'text'"
                        :accept="field.acceptFile" :placeholder="field.placeholder || `${field.label}...`"
                        v-if="field.type !== 'file'" v-model="attribute[field.name]" />

                    <Input :id="`attribute-${field.name}-${index}`" type="file" :accept="field.acceptFile"
                        @change="(e: Event) => handleFileInput(e, index, field.name)" v-else />
                    <InputError :message="normalizedErrors[index]?.[field.name]" />
                </div>

                <Button type="button" variant="ghost" size="icon" class="mt-5 text-destructive hover:bg-destructive/10"
                    @click="removeAttribute(index)" :disabled="modelValue.length === 1 || disabled">
                    <XCircleIcon class="w-5 h-5" />

                </Button>
            </div>
        </div>

        <Button type="button" variant="outline" size="sm" class="w-full mt-2" @click="addAttribute"
            :disabled="disabled">
            <PlusCircleIcon class="w-4 h-4 mr-2" />
            Add Attribute
        </Button>
    </div>
</template>