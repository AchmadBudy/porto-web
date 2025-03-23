<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Project, type BreadcrumbItem } from '@/types';
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

const { projects } = defineProps<{
    projects: {
        data: Project[],
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
        title: 'Projects',
        href: '/admin/projects',
    },
];
</script>

<template>

    <Head title="Projects" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col flex-1 h-full gap-4 p-4 rounded-xl">
            <div class="relative flex justify-end rounded-xl dark:border-sidebar-border">
                <Button as-child>
                    <Link :href="route('admin.projects.create')">
                    Add Project
                    </Link>
                </Button>
            </div>
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <Table>
                    <TableCaption>A list of your Projects.</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead>
                                Project Name
                            </TableHead>
                            <TableHead class="text-right">
                                Action
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <!-- Update to use projects.data -->
                        <TableRow v-for="project in projects.data" :key="project.id">
                            <TableCell class="font-medium">
                                {{ project.name }}
                            </TableCell>
                            <TableCell class="space-x-2 text-right">
                                <Button as-child>
                                    <Link :href="route('admin.projects.show', project.id)">
                                    Show
                                    </Link>
                                </Button>
                                <Button as-child>
                                    <Link :href="route('admin.projects.edit', project.id)">
                                    Edit
                                    </Link>
                                </Button>
                                <Button as-child>
                                    <Link :href="route('admin.projects.destroy', project.id)" method="delete"
                                        class="text-red-500 hover:text-red-600">Delete
                                    </Link>
                                </Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>

                <!-- Add pagination component at the bottom -->
                <div class="flex justify-center mt-4">
                    <PaginationDefault :links="projects.links" :meta="projects.meta"
                        @page-changed="(page) => router.visit(route('admin.projects.index', { page }))" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
