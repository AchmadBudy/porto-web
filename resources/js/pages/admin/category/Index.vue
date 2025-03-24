<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Category, type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'
import { PaginationDefault } from '@/components/ui/pagination';

const { categories } = defineProps<{
    categories: {
        data: Category[],
        links: Array<{
            url: string | null,
            label: string,
            active: boolean
        }>,
        meta: {
            current_page: number,
            from: number,
            last_page: number,
            links: Array<any>,
            path: string,
            per_page: number,
            to: number,
            total: number
        }
    }
}>();


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Category',
        href: '/admin/categories',
    },
];
</script>

<template>

    <Head title="Category" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col flex-1 h-full gap-4 p-4 rounded-xl">
            <div class="relative flex justify-end rounded-xl dark:border-sidebar-border">
                <Button as-child>
                    <Link :href="route('admin.categories.create')">
                    Add Category
                    </Link>
                </Button>
            </div>
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <Table>
                    <TableCaption>A list of your Categories.</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead>
                                Category
                            </TableHead>
                            <TableHead class="text-right">
                                Action
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <!-- Update to use categories.data -->
                        <TableRow v-for="category in categories.data" :key="category.id">
                            <TableCell class="font-medium">
                                {{ category.name }}
                            </TableCell>
                            <TableCell class="space-x-2 text-right">
                                <Button as-child>
                                    <Link :href="route('admin.categories.show', category.id)">
                                    Show
                                    </Link>
                                </Button>
                                <Button as-child>
                                    <Link :href="route('admin.categories.edit', category.id)">
                                    Edit
                                    </Link>
                                </Button>
                                <Button as-child>
                                    <Link :href="route('admin.categories.destroy', category.id)" method="delete"
                                        class="text-red-500 hover:text-red-600">Delete
                                    </Link>
                                </Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>

                <!-- Add pagination component at the bottom -->
                <div class="flex justify-center mt-4">
                    <!-- <PaginationDefault :links="categories.links" :meta="categories.meta"
                        @page-changed="(page) => router.visit(route('admin.categories.index', { page }))" /> -->
                    <PaginationDefault :links="categories.links" :meta="categories.meta" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
