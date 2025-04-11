<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Project, ProjectForm, type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { MultiSelectAi } from '@/components/ui/multi-select';
import InputError from '@/components/InputError.vue';
import { RepeaterOrigin } from '@/components/ui/repeater';
import InputImageFile from '@/components/custom/InputImageFile.vue';
import InputImageMultiFile from '@/components/custom/InputImageMultiFile.vue';
import InputQuill from '@/components/custom/InputQuill.vue';

type selectCategory = {
    value: number;
    label: string;
}

const { categories, currentProject } = defineProps<{
    categories: selectCategory[],
    currentProject: Project;
}>();


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Edit Project',
        href: '/admin/projects',
    },
];

// Update your form definition
const form = useForm<ProjectForm>({
    name: currentProject.name,
    description: currentProject.description,
    image: null,
    attributes: currentProject.attributes,
    categories: currentProject.categories.map(category => category.id), // Extract only the IDs
    galleries: [],
    old_galleries: currentProject.galleries,
    _method: 'PUT',
});


const submit = () => {
    form.post(route('admin.projects.update', currentProject.id), {
        onFinish: () => form.reset(),
    });
};



const clearCurrentGallery = () => {
    form.old_galleries = [];
};
const remveCurrentGallery = (index: number) => {
    if (form.old_galleries) {
        form.old_galleries.splice(index, 1);
    }
};
</script>

<template>

    <Head title="Edit Project" />

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
                    <HeadingSmall title="Edit Project" description="Just Edit Your Project" />

                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid gap-2">
                            <Label for="name">Project Name</Label>
                            <Input id="name" ref="nameInput" v-model="form.name" type="text" class="block w-full mt-1"
                                autocomplete="name" placeholder="Project Name" />
                            <InputError :message="form.errors.name" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="description">Project Description</Label>
                            <InputQuill id="description" ref="descriptionInput" v-model="form.description"
                                autocomplete="description" placeholder="Project Description" />
                            <InputError :message="form.errors.description" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="description">Project Category</Label>
                            <MultiSelectAi v-model="form.categories" :options="categories" mode="multiple"
                                placeholder="Select Categories..." />
                            <InputError :message="form.errors.description" />
                        </div>

                        <!-- Add this to your form, perhaps after the galleries section -->
                        <div class="grid gap-2">
                            <Label>Project Attributes</Label>

                            <RepeaterOrigin v-model="form.attributes" />
                            <InputError :message="form.errors.attributes" />
                        </div>

                        <div class="gird-gap-2">
                            <Label for="currentImage">Current Project Image</Label>
                            <div v-if="currentProject.image" class="mt-3">
                                <img :src="currentProject.image" class="object-cover w-full max-h-[200px]"
                                    alt="Project image preview" />
                            </div>
                        </div>

                        <div class="grid-gap-2">
                            <Label for="image">Project Image</Label>
                            <InputImageFile v-model="form.image" :placeholder="'Project Image'" />
                            <InputError :message="form.errors.image" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="gallery">List Current Gallery</Label>

                            <div v-if="form.old_galleries" class="mt-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-neutral-600 dark:text-neutral-400">
                                        {{ form.old_galleries.length }} images
                                    </span>
                                    <Button variant="outline" size="sm" @click="clearCurrentGallery"
                                        class="text-xs text-destructive hover:bg-destructive/10" type="button">
                                        Clear All
                                    </Button>
                                </div>

                                <div class="grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-4">
                                    <div v-for="(preview, index) in form.old_galleries" :key="index"
                                        class="relative overflow-hidden border rounded-lg shadow-xs group border-neutral-200 dark:border-neutral-800">
                                        <img :src="preview.image" class="object-cover w-full h-24"
                                            :alt="`Gallery image ${index + 1}`" />
                                        <div
                                            class="absolute inset-0 flex items-center justify-center transition-opacity opacity-0 bg-black/50 group-hover:opacity-100">
                                            <Button variant="ghost" size="sm" @click="remveCurrentGallery(index)"
                                                class="text-xs text-white hover:bg-white/20" type="button">
                                                Remove
                                            </Button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="grid gap-2">
                            <Label for="gallery">Project Gallery</Label>
                            <InputImageMultiFile v-model="form.galleries" :placeholder="'Project Gallery'" />

                            <InputError :message="form.errors.galleries" />
                        </div>


                        <div class="flex items-center gap-4">
                            <Button :disabled="form.processing">Update Project</Button>

                            <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                                <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Saved.</p>
                            </Transition>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
