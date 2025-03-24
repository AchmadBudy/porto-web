<script setup lang="ts">
import PortfolioLayout from '@/layouts/PortfolioLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Link } from '@inertiajs/vue3';
import { Separator } from '@/components/ui/separator';
import { GeneralSettings } from '@/types';

const props = defineProps<{
    project: any;
    relatedProjects: any;
    generalSettings: GeneralSettings;
}>();

// Format attributes for display
const formatAttributes = (attributes: any) => {
    if (!attributes || !Array.isArray(attributes) || attributes.length === 0) {
        return [];
    }

    return attributes.map(attr => {
        return {
            label: attr.label || '',
            value: attr.value || ''
        };
    });
};

const projectAttributes = formatAttributes(props.project.attributes);

// Developer information (static data)
const developer = {
    name: 'John Doe',
    title: 'Full Stack Developer',
    bio: 'Passionate full-stack developer with expertise in Laravel, Vue.js, and modern web technologies. I build elegant, efficient, and user-friendly applications.',
    avatar: 'https://avatars.githubusercontent.com/u/12345678',
    skills: ['Laravel', 'Vue.js', 'Tailwind CSS', 'TypeScript', 'MySQL', 'Git', 'Docker'],
    socials: [
        { name: 'GitHub', url: 'https://github.com', icon: 'github', imageUrl: 'https://cdn-icons-png.flaticon.com/512/25/25231.png' },
        { name: 'Twitter', url: 'https://twitter.com', icon: 'twitter', imageUrl: 'https://img.freepik.com/free-vector/new-2023-twitter-logo-x-icon-design_1017-45418.jpg' },
        { name: 'LinkedIn', url: 'https://linkedin.com', icon: 'linkedin', imageUrl: 'https://cdn-icons-png.flaticon.com/512/174/174857.png' },
    ]
};
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
                <span>{{ new Date(project.created_at).toLocaleDateString('en-US', {
                    year: 'numeric', month: 'long', day:
                        'numeric'
                }) }}</span>
            </div>
        </div>

        <!-- Project Content -->
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-3">
            <!-- Main Content -->
            <div class="space-y-8 lg:col-span-2">
                <!-- Main Image -->
                <div class="overflow-hidden border border-gray-200 rounded-lg dark:border-gray-800">
                    <img :src="project.image" :alt="project.name" class="object-cover w-full h-auto" />
                </div>

                <!-- Description -->
                <div class="prose prose-gray dark:prose-invert max-w-none">
                    <h2 class="mb-4 text-2xl font-bold">Project Overview</h2>
                    <p class="whitespace-pre-line">{{ project.description }}</p>
                </div>

                <!-- Gallery -->
                <div v-if="project.galleries && project.galleries.length > 0" class="space-y-4">
                    <h2 class="text-2xl font-bold">Project Gallery</h2>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div v-for="(gallery, index) in project.galleries" :key="index"
                            class="overflow-hidden border border-gray-200 rounded-lg dark:border-gray-800">
                            <img :src="gallery.image" :alt="`${project.name} gallery ${index + 1}`"
                                class="object-cover w-full h-64 transition-transform duration-300 hover:scale-105" />
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
                        <div v-if="projectAttributes.length > 0">
                            <div v-for="(attr, index) in projectAttributes" :key="index" class="py-3">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ attr.label
                                        }}</span>
                                    <span class="text-sm text-gray-900 dark:text-gray-100">{{ attr.value }}</span>
                                </div>
                                <Separator v-if="index < projectAttributes.length - 1" class="mt-3" />
                            </div>
                        </div>
                        <div v-else class="text-sm text-gray-500 dark:text-gray-400">
                            No attributes available
                        </div>
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
                    class="overflow-hidden transition-all border border-gray-200 rounded-lg group dark:border-gray-800 hover:shadow-md">
                    <Link :href="route('portfolio.show', relatedProject.slug)">
                    <div class="relative overflow-hidden aspect-video">
                        <img :src="relatedProject.image" :alt="relatedProject.name"
                            class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-105" />
                    </div>
                    <div class="p-4">
                        <h3 class="mb-2 text-lg font-bold transition-colors group-hover:text-primary">{{
                            relatedProject.name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ relatedProject.description
                            }}</p>
                    </div>
                    </Link>
                </div>
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