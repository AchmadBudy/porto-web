<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ProjectForm, type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { MultiSelectAi } from '@/components/ui/multi-select';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { PlusCircleIcon, XCircleIcon } from 'lucide-vue-next';
import { RepeaterOrigin } from '@/components/ui/repeater';

type selectCategory = {
    value: number;
    label: string;
}

defineProps<{
    categories: selectCategory[]
}>();


const imagePreview = ref<string | null>(null);
const imageInput = ref<string>('');


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create Project',
        href: '/admin/projects/create',
    },
];

// Update your form definition
const form = useForm<ProjectForm>({
    name: '',
    description: '',
    image: null,
    attributes: [], // Change from string to array of objects
    categories: [],
    galleries: [],
});

const submit = () => {
    form.post(route('admin.projects.store'));
};

const previewImage = (e: Event) => {
    const target = e.target as HTMLInputElement;
    const file = target.files?.[0];

    if (file) {
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const clearImage = () => {
    imagePreview.value = null;
    form.image = null;
    if (imageInput.value) {
        // Create a new form to reset file input
        imageInput.value = '';
    }
};


// Add these with your other ref declarations
const galleryPreviews = ref<string[]>([]);
const galleryInput = ref<string>('');

// Add these methods after your existing methods
const previewGallery = (e: Event) => {
    const target = e.target as HTMLInputElement;
    const files = target.files;

    if (files && files.length > 0) {
        // Clear previous previews if needed
        galleryPreviews.value = [];

        // Add form.galleries to your ProjectForm type and initialize it in useForm
        form.galleries = [];

        // Create preview for each selected image
        Array.from(files).forEach(file => {
            galleryPreviews.value.push(URL.createObjectURL(file));
            // Add to form data (ensure form.galleries is initialized as an array)
            form.galleries.push(file);
        });
    }
};

const clearGallery = () => {
    galleryPreviews.value = [];
    form.galleries = [];
    if (galleryInput.value) {
        galleryInput.value = '';
    }
};

</script>

<template>

    <Head title="Create Project" />

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
                    <HeadingSmall title="Crate Project" description="Just Create Your Project" />

                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid gap-2">
                            <Label for="name">Project Name</Label>
                            <Input id="name" ref="nameInput" v-model="form.name" type="text" class="block w-full mt-1"
                                autocomplete="name" placeholder="Project Name" />
                            <InputError :message="form.errors.name" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="description">Project Description</Label>
                            <Textarea id="description" ref="descriptionInput" v-model="form.description" type="text"
                                class="block w-full mt-1" autocomplete="description"
                                placeholder="Project Description" />
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

                        <div class="grid-gap-2">
                            <Label for="image">Project Image</Label>
                            <Input id="image" v-model="imageInput" type="file" class="block w-full mt-1"
                                autocomplete="image" placeholder="Project Image" accept="image/*"
                                @change="previewImage" />
                            <div v-if="imagePreview"
                                class="mt-3 overflow-hidden border rounded-lg shadow-sm border-neutral-200 dark:border-neutral-800">
                                <img :src="imagePreview" class="object-cover w-full max-h-[200px]"
                                    alt="Project image preview" />
                                <div class="flex items-center justify-between p-2 bg-neutral-50 dark:bg-neutral-900">
                                    <span class="text-xs text-neutral-500">Preview</span>
                                    <Button variant="ghost" size="sm" @click="clearImage"
                                        class="text-xs text-destructive hover:bg-destructive/10">
                                        Remove
                                    </Button>
                                </div>
                            </div>
                            <InputError :message="form.errors.image" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="gallery">Project Gallery</Label>
                            <Input id="gallery" v-model="galleryInput" type="file" class="block w-full mt-1"
                                autocomplete="gallery" placeholder="Project Gallery" accept="image/*" multiple
                                @change="previewGallery" />

                            <div v-if="galleryPreviews.length > 0" class="mt-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-neutral-600 dark:text-neutral-400">
                                        {{ galleryPreviews.length }} images selected
                                    </span>
                                    <Button variant="outline" size="sm" @click="clearGallery"
                                        class="text-xs text-destructive hover:bg-destructive/10">
                                        Clear All
                                    </Button>
                                </div>

                                <div class="grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-4">
                                    <div v-for="(preview, index) in galleryPreviews" :key="index"
                                        class="relative overflow-hidden border rounded-lg shadow-sm group border-neutral-200 dark:border-neutral-800">
                                        <img :src="preview" class="object-cover w-full h-24"
                                            :alt="`Gallery image ${index + 1}`" />
                                    </div>
                                </div>
                            </div>

                            <InputError :message="form.errors.galleries" />
                        </div>


                        <div class="flex items-center gap-4">
                            <Button :disabled="form.processing">Create Description</Button>

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
