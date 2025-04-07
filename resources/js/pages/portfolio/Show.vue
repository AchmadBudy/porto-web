<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import PortfolioLayout from '@/layouts/PortfolioLayout.vue';
import { GeneralSettings } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { onUnmounted, ref } from 'vue';
import 'quill/dist/quill.snow.css';

const props = defineProps<{
    project: any;
    relatedProjects: any;
    generalSettings: GeneralSettings;
}>();

// Image gallery modal functionality
const isModalOpen = ref(false);
const currentImage = ref('');
const currentImageAlt = ref('');

const openModal = (image: string, alt: string) => {
    currentImage.value = image;
    currentImageAlt.value = alt;
    isModalOpen.value = true;
    document.body.classList.add('overflow-hidden');
};

const closeModal = () => {
    isModalOpen.value = false;
    document.body.classList.remove('overflow-hidden');
};

// Close modal on escape key
const handleKeyDown = (event: KeyboardEvent) => {
    if (event.key === 'Escape' && isModalOpen.value) {
        closeModal();
    }
};

// Add event listener when component is mounted
if (typeof window !== 'undefined') {
    window.addEventListener('keydown', handleKeyDown);
}

// Clean up when component is unmounted
if (typeof window !== 'undefined') {
    onUnmounted(() => {
        window.removeEventListener('keydown', handleKeyDown);
        if (isModalOpen.value) {
            document.body.classList.remove('overflow-hidden');
        }
    });
}
</script>

<template>

    <Head :title="project.name" />

    <PortfolioLayout :title="project.name" :generalSettings="generalSettings">
        <!-- Breadcrumb -->
        <div class="mb-8">
            <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                <Link :href="route('home')" class="transition-colors hover:text-primary">Projects</Link>
                <span>/</span>
                <span class="text-gray-900 dark:text-gray-100">{{ project.name }}</span>
            </div>
        </div>

        <!-- Project Header -->
        <div class="mb-12">
            <div class="flex flex-wrap gap-2 mb-4">
                <Badge v-for="category in project.categories" :key="category.id" variant="secondary" class="text-xs">
                    {{ category.name }}
                </Badge>
            </div>
            <h1 class="mb-4 text-4xl font-bold tracking-tight">{{ project.name }}</h1>
            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                <span>{{
                    new Date(project.created_at).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                    })
                }}</span>
            </div>
        </div>

        <!-- Project Content -->
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-3">
            <!-- Main Content -->
            <div class="space-y-8 lg:col-span-2">
                <!-- Main Image -->
                <div class="overflow-hidden border border-gray-200 rounded-lg dark:border-gray-800">
                    <img :src="project.image" :alt="project.name" @click="openModal(project.image, project.name)"
                        class="h-auto max-h-[600px] w-full cursor-pointer object-cover transition-transform duration-300 hover:scale-[1.02]" />
                </div>

                <!-- Description -->
                <div class="prose prose-gray dark:prose-invert max-w-none">
                    <h2 class="mb-4 text-2xl font-bold">Project Overview</h2>
                    <div class="whitespace-pre-line ql-editor" v-html="project.description"></div>
                </div>

                <!-- Gallery -->
                <div v-if="project.galleries && project.galleries.length > 0" class="space-y-4">
                    <h2 class="text-2xl font-bold">Project Gallery</h2>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div v-for="(gallery, index) in project.galleries" :key="index"
                            class="overflow-hidden border border-gray-200 rounded-lg dark:border-gray-800">
                            <img :src="gallery.image" :alt="`${project.name} gallery ${index + 1}`"
                                @click="openModal(gallery.image, `${project.name} gallery ${index + 1}`)"
                                class="object-cover w-full h-64 transition-transform duration-300 cursor-pointer hover:scale-105" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Project Attributes -->
                <div class="p-6 border border-gray-200 rounded-lg dark:border-gray-800">
                    <h3 class="mb-4 text-xl font-bold">Project Details</h3>
                    <div class="space-y-4">
                        <div v-if="props.project.attributes.length > 0">
                            <div v-for="(attr, index) in props.project.attributes" :key="index" class="py-3">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ attr.label
                                        }}</span>
                                    <span class="text-sm text-gray-900 dark:text-gray-100">{{ attr.value }}</span>
                                </div>
                                <Separator v-if="index < props.project.attributes.length - 1" class="mt-3" />
                            </div>
                        </div>
                        <div v-else class="text-sm text-gray-500 dark:text-gray-400">No attributes available</div>
                    </div>
                </div>

                <!-- Back to Projects -->
                <Button as-child variant="outline" class="w-full">
                    <Link :href="route('home')" class="w-full">
                    <span class="flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Projects
                    </span>
                    </Link>
                </Button>
            </div>
        </div>

        <!-- Related Projects -->
        <div v-if="relatedProjects && relatedProjects.length > 0" class="mt-16">
            <h2 class="mb-8 text-2xl font-bold">Related Projects</h2>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="relatedProject in relatedProjects" :key="relatedProject.id"
                    class="overflow-hidden transition-all border border-gray-200 rounded-lg group hover:shadow-md dark:border-gray-800">
                    <Link :href="route('portfolio.show', relatedProject.slug)">
                    <div class="relative overflow-hidden aspect-video">
                        <img :src="relatedProject.image" :alt="relatedProject.name"
                            class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-105" />
                    </div>
                    <div class="p-4">
                        <h3 class="mb-2 text-lg font-bold transition-colors group-hover:text-primary">{{
                            relatedProject.name }}</h3>
                        <p class="text-sm text-gray-600 line-clamp-2 dark:text-gray-400">{{ relatedProject.description
                            }}</p>
                    </div>
                    </Link>
                </div>
            </div>
        </div>

        <!-- Image Modal -->
        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/75"
            @click="closeModal">
            <div class="relative mx-4 max-h-[90vh] w-full max-w-4xl">
                <button @click="closeModal"
                    class="absolute z-10 p-2 text-white rounded-full right-4 top-4 bg-black/50 hover:bg-black/70"
                    aria-label="Close modal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <img :src="currentImage" :alt="currentImageAlt" class="mx-auto max-h-[85vh] max-w-full object-contain"
                    @click.stop />
            </div>
        </div>
    </PortfolioLayout>
</template>

<style>
.prose a {
    color: hsl(var(--primary));
    text-decoration: underline;
    font-weight: 500;
}

.prose a:hover {
    text-decoration: none;
}
</style>
