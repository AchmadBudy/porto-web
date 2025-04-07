<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Project, type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { MultiSelectAi } from '@/components/ui/multi-select';
import { ref } from 'vue';
import { RepeaterOrigin } from '@/components/ui/repeater';
import 'quill/dist/quill.snow.css';

type selectCategory = {
    value: number;
    label: string;
}

const { categories, project } = defineProps<{
    categories: selectCategory[],
    project: Project;
}>();

const imagePreview = ref<string | null>(project.image);

const currentCategory = ref<number[]>(project.categories.map(category => category.id));

// Create a local copy of project attributes to avoid mutating props
const projectAttributes = ref(project.attributes);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Show Project',
        href: '/admin/projects',
    },
];

</script>

<template>

    <Head title="Show Project" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col flex-1 h-full gap-4 p-4 rounded-xl">
            <div class="relative flex justify-end rounded-xl dark:border-sidebar-border">
                <Button as-child>
                    <Link :href="route('admin.projects.index')">
                    Back
                    </Link>
                </Button>
            </div>
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <div class="p-8 space-y-6">
                    <HeadingSmall title="Show Project" description="Showing Your Project" />

                    <div class="grid gap-2">
                        <Label for="name">Project Name</Label>
                        <Input id="name" ref="nameInput" :defaultValue="project.name" type="text"
                            class="block w-full mt-1" autocomplete="name" placeholder="Project Name" disabled />
                    </div>

                    <div class="grid gap-2">
                        <Label for="description">Project Description</Label>
                        <div v-html="project.description" class="ql-editor">
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <Label for="description">Project Category</Label>
                        <MultiSelectAi v-model="currentCategory" :options="categories" mode="multiple"
                            placeholder="Select Categories..." :disabled="true" />
                    </div>

                    <!-- Add this to your form, perhaps after the galleries section -->
                    <div class="grid gap-2">
                        <Label>Project Attributes</Label>

                        <RepeaterOrigin v-model="projectAttributes" :disabled="true" />
                    </div>

                    <div class="grid-gap-2">
                        <Label for="image">Project Image</Label>
                        <div v-if="imagePreview"
                            class="mt-3 overflow-hidden border rounded-lg shadow-sm border-neutral-200 dark:border-neutral-800">
                            <img :src="imagePreview" class="object-cover w-full max-h-[200px]"
                                alt="Project image preview" />
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <Label for="gallery">List Current Gallery</Label>

                        <div v-if="project.galleries" class="mt-3">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-neutral-600 dark:text-neutral-400">
                                    {{ project.galleries.length }} images
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-4">
                                <div v-for="(preview, index) in project.galleries" :key="index"
                                    class="relative overflow-hidden border rounded-lg shadow-sm group border-neutral-200 dark:border-neutral-800">
                                    <img :src="preview.image" class="object-cover w-full h-24"
                                        :alt="`Gallery image ${index + 1}`" />
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>