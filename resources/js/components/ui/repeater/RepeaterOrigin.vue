<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { PlusCircleIcon, XCircleIcon } from 'lucide-vue-next';

const props = defineProps({
    modelValue: {
        type: Array as () => Array<{ label: string; value: string }>,
        required: true
    },
    disabled: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:modelValue']);

const addAttribute = () => {
    const newAttributes = [...props.modelValue, { label: '', value: '' }];
    emit('update:modelValue', newAttributes);
};

const removeAttribute = (index: number) => {
    const newAttributes = props.modelValue.filter((_, i) => i !== index);
    emit('update:modelValue', newAttributes);
};
</script>

<template>
    <div class="p-4 space-y-3 border rounded-md border-neutral-200 dark:border-neutral-800">
        <div v-for="(attribute, index) in modelValue" :key="index" class="grid gap-3">
            <div class="flex items-start gap-3">
                <div class="grid flex-1 gap-1">
                    <Label :for="`attribute-label-${index}`"
                        class="text-xs font-normal text-neutral-600 dark:text-neutral-400">
                        Label
                    </Label>
                    <Input :id="`attribute-label-${index}`" v-model="attribute.label" placeholder="Attribute name" />
                </div>

                <div class="grid flex-1 gap-1">
                    <Label :for="`attribute-value-${index}`"
                        class="text-xs font-normal text-neutral-600 dark:text-neutral-400">
                        Value
                    </Label>
                    <Input :id="`attribute-value-${index}`" v-model="attribute.value" placeholder="Attribute value" />
                </div>

                <Button type="button" variant="ghost" size="icon" class="mt-5 text-destructive hover:bg-destructive/10"
                    @click="removeAttribute(index)" :disabled="modelValue.length === 1" v-if="!disabled">
                    <XCircleIcon class="w-5 h-5" />
                </Button>
            </div>
        </div>

        <Button type="button" variant="outline" size="sm" class="w-full mt-2" @click="addAttribute" v-if="!disabled">
            <PlusCircleIcon class="w-4 h-4 mr-2" />
            Add Attribute
        </Button>
    </div>
</template>