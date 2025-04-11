<template>
    <div ref="dropdownRef" class="relative">
        <!-- Input Trigger -->
        <div class="flex flex-wrap gap-1 p-2 transition-colors border rounded-md cursor-pointer hover:border-primary-500 dark:hover:border-primary-400"
            :class="[isOpen ? 'border-primary-500 ring-1 ring-primary-500 dark:border-primary-400 dark:ring-primary-400' : 'border-gray-300 dark:border-gray-600']"
            @click="toggleDropdown">
            <!-- Selected Items -->
            <template v-if="selectedItems.length > 0">
                <div v-for="(item, index) in selectedItems" :key="index"
                    class="flex items-center px-2 py-1 text-sm rounded-full bg-primary/10 dark:bg-primary/20 dark:text-primary-100">
                    {{ getItemLabel(item) }}
                    <button type="button" @click.stop="removeItem(item)"
                        class="ml-1 text-primary-600 hover:text-primary-800 dark:text-primary-300 dark:hover:text-primary-100">
                        &times;
                    </button>
                </div>
            </template>
            <span v-else class="text-gray-400 dark:text-gray-300">{{ placeholder }}</span>
        </div>

        <!-- Dropdown -->
        <div v-show="isOpen"
            class="absolute z-50 w-full mt-1 overflow-auto bg-white border border-gray-200 rounded-md shadow-lg max-h-60 dark:bg-gray-700 dark:border-gray-600">
            <!-- Search Input -->
            <input v-model="searchQuery" type="text" placeholder="Search..."
                class="w-full p-2 border-b border-gray-200 focus-visible:outline-none focus-visible:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus-visible:border-primary-400"
                @keydown.esc="closeDropdown" @keydown.down.prevent="highlightNext" @keydown.up.prevent="highlightPrev"
                @keydown.enter.prevent="selectHighlighted">

            <!-- Select All -->
            <div v-if="mode === 'multiple'"
                class="px-3 py-1 text-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-100"
                @click="toggleSelectAll">
                {{ isAllSelected ? 'Deselect All' : 'Select All' }}
            </div>

            <!-- Options -->
            <ul class="py-1">
                <li v-for="(option, index) in filteredOptions" :key="index"
                    class="px-3 py-2 text-sm cursor-pointer hover:bg-primary/5 dark:hover:bg-gray-600" :class="{
                        'bg-primary/5 dark:bg-gray-600': highlightedIndex === index,
                        'font-semibold dark:text-gray-100': isSelected(option.value)
                    }" @click="toggleSelection(option.value)" @mouseover="highlightedIndex = index">
                    <input type="checkbox" :checked="isSelected(option.value)"
                        class="mr-2 rounded text-primary-600 focus-visible:ring-primary-500 dark:text-primary-400 dark:accent-primary-500"
                        :class="{ 'opacity-50 dark:opacity-30': option.disabled }" :disabled="option.disabled">
                    <span class="dark:text-gray-200">{{ option.label }}</span>
                </li>

                <li v-if="filteredOptions.length === 0" class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">
                    No options found
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, nextTick, onMounted, onUnmounted } from 'vue'

const props = defineProps({
    options: {
        type: Array as () => Array<{ value: any; label: string; disabled?: boolean }>,
        required: true
    },
    modelValue: {
        type: [Array, String, Number],
        default: () => []
    },
    placeholder: {
        type: String,
        default: 'Select...'
    },
    mode: {
        type: String as () => 'multiple' | 'single',
        default: 'multiple'
    }
})

const emit = defineEmits(['update:modelValue'])

const isOpen = ref(false)
const searchQuery = ref('')
const highlightedIndex = ref(-1)
const dropdownRef = ref<HTMLElement | null>(null)

// Selected values
const selectedItems = computed({
    get: () => Array.isArray(props.modelValue) ? props.modelValue : [props.modelValue],
    set: (value) => emit('update:modelValue', props.mode === 'multiple' ? value : value[0])
})

// Filtered options
const filteredOptions = computed(() => {
    return props.options.filter(option =>
        option.label.toLowerCase().includes(searchQuery.value.toLowerCase()) &&
        !option.disabled
    )
})

// Check if all options are selected
const isAllSelected = computed(() => {
    return filteredOptions.value.every(option =>
        selectedItems.value.includes(option.value)
    )
})

// Handle click outside
const handleClickOutside = (event: MouseEvent) => {
    if (
        dropdownRef.value &&
        !dropdownRef.value.contains(event.target as Node) &&
        isOpen.value
    ) {
        closeDropdown()
    }
}

// Handle escape key
const handleEscapeKey = (event: KeyboardEvent) => {
    if (event.key === 'Escape' && isOpen.value) {
        closeDropdown()
    }
}

// Lifecycle hooks
onMounted(() => {
    document.addEventListener('click', handleClickOutside)
    document.addEventListener('keydown', handleEscapeKey)
})

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside)
    document.removeEventListener('keydown', handleEscapeKey)
})

// Toggle dropdown
const toggleDropdown = () => {
    isOpen.value = !isOpen.value
    if (isOpen.value) {
        nextTick(() => {
            highlightedIndex.value = 0
        })
    }
}

// Close dropdown
const closeDropdown = () => {
    isOpen.value = false
    searchQuery.value = ''
    highlightedIndex.value = -1
}

// Check if item is selected
const isSelected = (value: any) => {
    return selectedItems.value.includes(value)
}

// Toggle selection
const toggleSelection = (value: any) => {
    if (props.mode === 'multiple') {
        const newSelection = selectedItems.value.includes(value)
            ? selectedItems.value.filter(item => item !== value)
            : [...selectedItems.value, value]
        selectedItems.value = newSelection
    } else {
        selectedItems.value = selectedItems.value.includes(value) ? [] : [value]
        closeDropdown()
    }
}

// Remove item from selection
const removeItem = (value: any) => {
    selectedItems.value = selectedItems.value.filter(item => item !== value)
}

// Toggle select all
const toggleSelectAll = () => {
    const allValues = filteredOptions.value.map(option => option.value)
    selectedItems.value = isAllSelected.value
        ? selectedItems.value.filter(value => !allValues.includes(value))
        : [...new Set([...selectedItems.value, ...allValues])]
}

// Get item label
const getItemLabel = (value: any) => {
    const option = props.options.find(opt => opt.value === value)
    return option ? option.label : value
}

// Keyboard navigation
const highlightNext = () => {
    highlightedIndex.value = Math.min(
        highlightedIndex.value + 1,
        filteredOptions.value.length - 1
    )
}

const highlightPrev = () => {
    highlightedIndex.value = Math.max(highlightedIndex.value - 1, 0)
}

const selectHighlighted = () => {
    if (filteredOptions.value[highlightedIndex.value]) {
        toggleSelection(filteredOptions.value[highlightedIndex.value].value)
    }
}
</script>

<style scoped>
::-webkit-scrollbar {
    width: 8px;
}
</style>