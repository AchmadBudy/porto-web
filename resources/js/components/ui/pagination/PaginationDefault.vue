<script setup lang="ts">
import { computed } from 'vue';
import { Pagination, PaginationEllipsis, PaginationFirst, PaginationLast, PaginationList, PaginationListItem, PaginationNext, PaginationPrev } from '.';
import { Button } from '../button';

const props = defineProps({
    links: {
        type: Array,
        required: true
    },
    meta: {
        type: Object,
        required: true
    }
});

// Computed property to convert Laravel pagination meta to component format
const paginationData = computed(() => {
    return {
        currentPage: props.meta.current_page,
        itemsPerPage: props.meta.per_page,
        total: props.meta.total
    };
});

// Emit event when page changes
const emit = defineEmits(['page-changed']);

const handlePageChange = (page: any) => {
    emit('page-changed', page);
};
</script>

<template>
    <Pagination v-slot="{ page }" :items-per-page="paginationData.itemsPerPage" :total="paginationData.total"
        :default-page="paginationData.currentPage" :sibling-count="1" show-edges @update:page="handlePageChange">
        <PaginationList v-slot="{ items }" class="flex items-center gap-1">
            <PaginationFirst />
            <PaginationPrev />

            <template v-for="(item, index) in items">
                <PaginationListItem v-if="item.type === 'page'" :key="index" :value="item.value" as-child>
                    <Button class="w-10 h-10 p-0" :variant="item.value === page ? 'default' : 'outline'">
                        {{ item.value }}
                    </Button>
                </PaginationListItem>
                <PaginationEllipsis v-else :key="item.type" :index="index" />
            </template>

            <PaginationNext />
            <PaginationLast />
        </PaginationList>
    </Pagination>
</template>