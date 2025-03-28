<script setup lang="ts">
import PortfolioLayout from '@/layouts/PortfolioLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Link } from '@inertiajs/vue3';
import { Category, GeneralSettings, Project } from '@/types';
import { PaginationDefault } from '@/components/ui/pagination';

const props = defineProps<{
    projects: {
        data: Project[];
        meta: any;
        links: any;
    };
    categories: Category[];
    generalSettings: GeneralSettings;
}>();

const selectedCategory = ref<number | null>(null);

const filteredProjects = computed(() => {
    if (!selectedCategory.value) {
        return props.projects.data;
    }

    return props.projects.data.filter((project: any) => {
        return project.categories.some((category: any) => category.id === selectedCategory.value);
    });
});

const selectCategory = (categoryId: number | null) => {
    selectedCategory.value = categoryId;
};

</script>

<template>

    <Head title="All Projects" />

    <PortfolioLayout title="All Projects" :generalSettings="generalSettings">
        <!-- Breadcrumb -->
        <div class="mb-8">
            <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                <Link :href="route('home')" class="transition-colors hover:text-primary">Home</Link>
                <span>/</span>
                <span class="text-gray-900 dark:text-gray-100">All Projects</span>
            </div>
        </div>

        <!-- Projects Section -->
        <section class="py-8">
            <div class="mb-12 text-center">
                <h2 class="mb-4 text-3xl font-bold tracking-tight">All Projects</h2>
                <p class="max-w-2xl mx-auto text-gray-600 dark:text-gray-400">
                    Browse through my complete portfolio of projects. Each project represents a unique challenge and
                    solution.
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

            <!-- Pagination -->
            <!-- <div v-if="props.projects.meta && props.projects.meta.links && props.projects.meta.links.length > 3"
                class="flex justify-center mt-12">
                <div class="flex space-x-1">
                    <template v-for="(link, i) in props.projects.meta.links" :key="i">
                        <div v-if="link.url === null"
                            class="px-4 py-2 text-sm text-gray-500 border border-gray-200 rounded-md dark:border-gray-700 dark:text-gray-400">
                            <span v-html="link.label"></span>
                        </div>
                        <Link v-else :href="link.url" :class="{
                            'px-4 py-2 text-sm border rounded-md': true,
                            'bg-primary text-black border-primary': link.active,
                            'text-gray-700 border-gray-200 hover:bg-gray-50 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-800': !link.active
                        }">
                        <span v-html="link.label"></span>
                        </Link>
                    </template>
</div>
</div> -->
            <!-- Add pagination component at the bottom -->
            <div class="flex justify-center mt-4">
                <PaginationDefault :links="projects.links" :meta="projects.meta"
                    @page-changed="(page) => router.visit(route('portfolio.all', { page }))" />
            </div>
        </section>
    </PortfolioLayout>
</template>