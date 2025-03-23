<script setup lang="ts">
import { Pagination, PaginationEllipsis, PaginationFirst, PaginationLast, PaginationList, PaginationListItem, PaginationNext, PaginationPrev } from '.';
import { Button } from '../button';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    meta: {
        type: Object,
        required: true
    },
    links: {
        type: Object,
        required: true
    }
});

function handleNavigation(url: string | null) {
    if (url) {
        router.get(url);
    }
}

function getPageUrl(page: number) {
    const url = new URL(props.meta.path);
    url.searchParams.set('page', page.toString());
    return url.toString();
}
</script>

<template>
    <Pagination v-slot="{ page }" :items-per-page="meta.per_page" :total="meta.total" :sibling-count="1" show-edges
        :default-page="meta.current_page">
        <PaginationList v-slot="{ items }" class="flex items-center gap-1">
            <PaginationFirst @click="handleNavigation(links.first)" :disabled="meta.current_page === 1" />
            <PaginationPrev @click="handleNavigation(links.prev)" :disabled="!links.prev" />

            <template v-for="(item, index) in items">
                <PaginationListItem v-if="item.type === 'page'" :key="index" :value="item.value" as-child>
                    <Button class="w-10 h-10 p-0" :variant="item.value === meta.current_page ? 'default' : 'outline'"
                        @click="handleNavigation(getPageUrl(item.value))">
                        {{ item.value }}
                    </Button>
                </PaginationListItem>
                <PaginationEllipsis v-else :key="item.type" :index="index" />
            </template>

            <PaginationNext @click="handleNavigation(links.next)" :disabled="!links.next" />
            <PaginationLast @click="handleNavigation(links.last)" :disabled="meta.current_page === meta.last_page" />
        </PaginationList>
    </Pagination>
</template>