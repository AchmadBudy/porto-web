<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Category, type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import HeadingSmall from '@/components/HeadingSmall.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Show Category',
        href: '/admin/categories/create',
    },
];

const { category } = defineProps<{
    category: Category;
}>();

</script>

<template>

    <Head title="Show Category" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col flex-1 h-full gap-4 p-4 rounded-xl">
            <div class="relative flex justify-end rounded-xl dark:border-sidebar-border">
                <Button as-child>
                    <Link :href="route('admin.categories.index')">
                    Back
                    </Link>
                </Button>
            </div>
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <div class="p-8 space-y-6">
                    <HeadingSmall title="Update Category" description="Just Update Your Category" />
                    <div class="grid gap-2">
                        <Label for="name">Category Name</Label>
                        <Input id="name" :defaultValue="category.name" type="text" class="block w-full mt-1"
                            autocomplete="name" placeholder="Category Name" disabled />
                    </div>

                    <div class="grid gap-2">
                        <Label for="description">Category Description</Label>
                        <Textarea id="description" :defaultValue="category.description" type="text"
                            class="block w-full mt-1" autocomplete="description" placeholder="Category Description"
                            disabled />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
