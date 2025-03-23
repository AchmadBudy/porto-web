<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { Toaster } from '@/components/ui/sonner'
import { toast } from 'vue-sonner'
import { onMounted, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});


interface Flash {
    success?: null;
    error?: null;
}

onMounted(() => {
    watch(() => usePage<{ flash: Flash }>().props.flash,
        (flash: Flash) => {
            if (flash.success) {
                toast.success(flash.success);
                flash.success = null;
            }
            if (flash.error) {
                toast.error(flash.error);
                flash.error = null;
            }
        }, { immediate: true });
})
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
        <Toaster position="top-right" richColors />
    </AppLayout>
</template>
