<script setup lang="ts">
import { useResizeObserver, useDebounceFn } from '@vueuse/core'
import { computed, onMounted, ref, watch, nextTick } from 'vue'
// Removed unused router import
import axios from 'axios'
import { Check, ChevronDown } from 'lucide-vue-next' // Adjust import based on your icon library
import { Popover, PopoverContent, PopoverTrigger } from '../popover'
import { Button } from '../button'
import { Badge } from '../badge'
import {
    Command,
    CommandInput,
    CommandList,
    CommandEmpty,
    CommandGroup,
    CommandItem,
    CommandSeparator
} from '../command'

interface Option {
    value: string | number
    label: string
}

// Define model type with TypeScript - Fixed spacing in generic type
const model = defineModel<Array<string | number>>({ default: () => [] })

const props = defineProps({
    title: { type: String, required: true },
    options: { type: Array as () => Option[], default: () => [] },
    optionsUrl: { type: String, default: null },
    translationPrefix: { type: String, default: 'multiselect' }
})

// Helper function for translations (using Laravel translations via Inertia)
const t = (key: string, params: Record<string, any> = {}): string => {
    const translationKey = `${props.translationPrefix}.${key}`
    const translation = (window as any).translations?.[translationKey] || key

    // Replace parameters in translation
    return Object.entries(params).reduce(
        (text, [key, value]) => text.replace(`:${key}`, value),
        translation
    )
}

const q = ref('')
// Fixed spacing in generic type declaration
const selectedValues = ref<Set<string | number>>(new Set(model.value))
const optionsList = ref<Option[]>([])
const triggerRef = ref<HTMLElement | null>(null)
const triggerWidth = ref(0)

const isEmptySelection = computed(() => selectedValues.value.size < 1)
const selectedCount = computed(() => selectedValues.value.size)
const isOverThreeSelections = computed(() => selectedCount.value > 3)
const selectedOptionsList = computed(() =>
    optionsList.value.filter(o => selectedValues.value.has(o.value))
)
const hasSelections = computed(() => selectedCount.value > 0)

const emptyMessage = computed(() =>
    props.optionsUrl && q.value === '' ? t('search_placeholder') : t('no_results')
)

const localFilterFunction = (list: Option[], term: string): Option[] =>
    list.filter(i => i.label.toLowerCase().includes(term.toLowerCase()))

// Fix: Change parameter type to readonly ResizeObserverEntry[]
const updateTriggerWidth = (entries: readonly ResizeObserverEntry[]): void => {
    triggerWidth.value = entries[0]?.borderBoxSize?.[0]?.inlineSize ||
        triggerRef.value?.offsetWidth ||
        0
}

useResizeObserver(triggerRef, updateTriggerWidth)

onMounted(async () => {
    await updateOptionList('')
    await nextTick()

    // Fix: Create a proper ResizeObserverEntry object with all required properties
    if (triggerRef.value) {
        const width = triggerRef.value.offsetWidth || 0
        const mockEntry = {
            target: triggerRef.value,
            contentRect: new DOMRectReadOnly(0, 0, width, 0),
            borderBoxSize: [{ inlineSize: width, blockSize: 0 }],
            contentBoxSize: [{ inlineSize: width, blockSize: 0 }],
            devicePixelContentBoxSize: [{ inlineSize: width, blockSize: 0 }]
        } as ResizeObserverEntry;

        updateTriggerWidth([mockEntry]);
    }
})

const updateOptionList = async (query: string): Promise<void> => {
    if (!props.optionsUrl) {
        optionsList.value = props.options.filter(o =>
            o.label.toLowerCase().includes(query.toLowerCase())
        )
        return
    }

    try {
        const response = await axios.get(props.optionsUrl, {
            params: { q: query }
        })
        optionsList.value = response.data.data
    } catch (error) {
        console.error('Error fetching options:', error)
        optionsList.value = []
    }
}

const debouncedUpdate = useDebounceFn((val: string) => updateOptionList(val), 300)

watch(q, (newVal: string) => debouncedUpdate(newVal))

const toggleSelection = (optionValue: string | number): void => {
    selectedValues.value.has(optionValue)
        ? selectedValues.value.delete(optionValue)
        : selectedValues.value.add(optionValue)
    model.value = [...selectedValues.value]
}

const clearSelections = (): void => {
    selectedValues.value.clear()
    model.value = []
}
</script>

<template>
    <div class="flex flex-col gap-2">
        <Popover>
            <PopoverTrigger as-child>
                <Button ref="triggerRef" variant="outline"
                    class="flex h-[52px] justify-between font-normal text-muted-foreground">
                    <span v-if="isEmptySelection">{{ t('title', { title }) }}</span>
                    <template v-else>
                        <Badge variant="outline" class="px-1 font-normal rounded-sm lg:hidden">
                            {{ t('selected', { count: selectedCount }) }}
                        </Badge>
                        <div class="hidden space-x-1 lg:flex">
                            <Badge v-if="isOverThreeSelections" variant="outline" class="px-1 font-normal rounded-sm">
                                {{ t('selected', { count: selectedCount }) }}
                            </Badge>
                            <template v-else>
                                <Badge v-for="option in selectedOptionsList" :key="option.value" variant="outline"
                                    class="px-1 font-normal rounded-sm">
                                    {{ option.label }}
                                </Badge>
                            </template>
                        </div>
                    </template>
                    <ChevronDown class="w-4 h-4 ml-2" />
                </Button>
            </PopoverTrigger>
            <PopoverContent :style="{ width: `${triggerWidth}px` }" class="p-0" align="start">
                <Command :should-filter="props.optionsUrl == null"
                    :filter-function="props.optionsUrl ? null : localFilterFunction">
                    <CommandInput v-model="q" :placeholder="t('search_placeholder')" />
                    <CommandList>
                        <CommandEmpty>
                            {{ emptyMessage }}
                        </CommandEmpty>
                        <CommandGroup>
                            <CommandItem v-for="option in optionsList" :key="option.value" :value="option"
                                @select="toggleSelection(option.value)">
                                <div :class="[
                                    'mr-2 flex h-4 w-4 items-center justify-center rounded-sm border border-primary',
                                    selectedValues.has(option.value)
                                        ? 'bg-primary text-primary-foreground'
                                        : 'opacity-50 [&_svg]:invisible'
                                ]">
                                    <Check class="w-4 h-4" />
                                </div>
                                <span>{{ option.label }}</span>
                            </CommandItem>
                        </CommandGroup>
                        <template v-if="hasSelections">
                            <CommandSeparator />
                            <CommandGroup>
                                <CommandItem value="clear-selections" class="justify-center text-center"
                                    @select="clearSelections">
                                    Clear Selections
                                </CommandItem>
                            </CommandGroup>
                        </template>
                    </CommandList>
                </Command>
            </PopoverContent>
        </Popover>
    </div>
</template>