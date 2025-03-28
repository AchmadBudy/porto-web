<script setup lang="ts">
import PortfolioLayout from '@/layouts/PortfolioLayout.vue';
import { ref, computed } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Link } from '@inertiajs/vue3';
import { Category, GeneralSettings, Project } from '@/types';

const { projects, categories, generalSettings } = defineProps<{
    projects: {
        data: Project[];
        links: object;
        meta: object;
    };
    categories: Category[];
    generalSettings: GeneralSettings;
}>();

const selectedCategory = ref<number | null>(null);

const filteredProjects = computed(() => {
    if (!selectedCategory.value) {
        return projects.data;
    }

    return projects.data.filter((project: any) => {
        return project.categories.some((category: any) => category.id === selectedCategory.value);
    });
});

const selectCategory = (categoryId: number | null) => {
    selectedCategory.value = categoryId;
};

</script>

<template>
    <PortfolioLayout title="Portfolio" :generalSettings="generalSettings">
        <!-- Hero Section -->
        <section class="py-12 md:py-20">
            <div class="grid items-center grid-cols-1 gap-12 md:grid-cols-2">
                <div class="space-y-6">
                    <div class="inline-block px-3 py-1 text-sm font-medium rounded-full bg-primary/10 text-primary">
                        {{ generalSettings.title }}
                    </div>
                    <h1 class="text-4xl font-bold tracking-tight md:text-5xl">
                        Hi, I'm {{ generalSettings.name }} <span class="block">Welcome to my portfolio</span>
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        {{ generalSettings.bio }}
                    </p>
                    <div class="flex flex-wrap gap-2">
                        <Badge v-for="skill in generalSettings.skills" :key="skill" variant="outline" class="text-sm">
                            {{ skill }}
                        </Badge>
                    </div>
                    <div class="flex space-x-4">
                        <a v-for="social in generalSettings.socials" :key="social.name" :href="social.url"
                            target="_blank"
                            class="text-gray-500 transition-colors hover:text-primary dark:text-gray-400 dark:hover:text-primary">
                            <img :src="social.previewImageUrl ?? ''" :alt="social.name" class="w-6 h-6" />
                        </a>
                    </div>
                    <Button as-child>
                        <a href="#projects" class="flex items-center gap-2">
                            View Projects
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </a>
                    </Button>
                </div>
                <div class="relative">
                    <div
                        class="absolute rounded-lg opacity-75 -inset-1 bg-gradient-to-r from-primary/20 to-primary/40 blur">
                    </div>
                    <div
                        class="relative p-4 overflow-hidden bg-white border border-gray-200 rounded-lg dark:border-gray-800 dark:bg-gray-900">
                        <div
                            class="flex items-center justify-between pb-2 border-b border-gray-200 dark:border-gray-800">
                            <div class="flex space-x-1">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">generalSettings.profile</div>
                        </div>
                        <div class="mt-4 font-mono text-sm">
                            <div class="flex mb-2">
                                <span class="mr-2 text-primary">$</span>
                                <span class="typing-animation">whoami</span>
                            </div>
                            <div class="pl-6 mb-4 text-gray-700 dark:text-gray-300">{{ generalSettings.name }}</div>

                            <div class="flex mb-2">
                                <span class="mr-2 text-primary">$</span>
                                <span>cat skills.txt</span>
                            </div>
                            <div class="pl-6 mb-4 text-gray-700 dark:text-gray-300">
                                <span v-for="(skill, index) in generalSettings.skills" :key="skill">
                                    {{ skill }}{{ index < generalSettings.skills.length - 1 ? ', ' : '' }} </span>
                            </div>

                            <div class="flex mb-2">
                                <span class="mr-2 text-primary">$</span>
                                <span class="typing-animation">cd projects</span>
                            </div>
                            <div class="flex mb-2">
                                <span class="mr-2 text-primary">$</span>
                                <span class="blink">_</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Projects Section -->
        <section id="projects" class="py-16">
            <div class="mb-12 text-center">
                <h2 class="mb-4 text-3xl font-bold tracking-tight">My Projects</h2>
                <p class="max-w-2xl mx-auto text-gray-600 dark:text-gray-400">
                    Browse through my latest work. Each project represents a unique challenge and solution.
                </p>
            </div>

            <!-- Category Filter -->
            <div class="flex flex-wrap justify-center gap-2 mb-8">
                <Button :variant="selectedCategory === null ? 'default' : 'outline'" @click="selectCategory(null)"
                    class="text-sm">
                    All Projects
                </Button>
                <Button v-for="category in categories" :key="category.id"
                    :variant="selectedCategory === category.id ? 'default' : 'outline'"
                    @click="selectCategory(category.id)" class="text-sm">
                    {{ category.name }}
                </Button>
                <Button as-child variant="secondary" class="ml-2 text-sm">
                    <Link :href="route('portfolio.all')" class="flex items-center gap-1">
                    View All Projects
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                    </Link>
                </Button>
            </div>

            <!-- Projects Grid -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <Card v-for="project in filteredProjects" :key="project.id"
                    class="overflow-hidden transition-all hover:shadow-md">
                    <div class="relative overflow-hidden aspect-video">
                        <img :src="project.image" :alt="project.name"
                            class="object-cover w-full h-full transition-transform duration-300 hover:scale-105" />
                    </div>
                    <CardContent class="p-6">
                        <div class="flex flex-wrap gap-2 mb-4">
                            <Badge v-for="category in project.categories" :key="category.id" variant="secondary"
                                class="text-xs">
                                {{ category.name }}
                            </Badge>
                        </div>
                        <h3 class="mb-2 text-xl font-bold">{{ project.name }}</h3>
                        <p class="mb-4 text-sm text-gray-600 line-clamp-3 dark:text-gray-400">
                            {{ project.description }}
                        </p>
                        <Button as-child variant="outline" class="w-full">
                            <Link :href="route('portfolio.show', project.slug)" class="w-full">
                            View Project
                            </Link>
                        </Button>
                    </CardContent>
                </Card>
            </div>

            <!-- Empty State -->
            <div v-if="filteredProjects.length === 0" class="mt-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <h3 class="mb-2 text-xl font-medium">No projects found</h3>
                <p class="text-gray-600 dark:text-gray-400">
                    No projects match the selected category. Try selecting a different category.
                </p>
            </div>
        </section>
    </PortfolioLayout>
</template>
