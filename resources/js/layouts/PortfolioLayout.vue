<script setup lang="ts">
import { GeneralSettings } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, ref, onMounted } from 'vue';

interface Props {
    title?: string;
    generalSettings: GeneralSettings
}

const props = withDefaults(defineProps<Props>(), {
    title: 'Portfolio',
});

const isDarkMode = ref(false);

onMounted(() => {
    isDarkMode.value = document.documentElement.classList.contains('dark');
});

const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;
    document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', isDarkMode.value ? 'dark' : 'light');
};

const pageTitle = computed(() => {
    return props.title ?? 'Portfolio';
});

// const page = usePage();
// const siteName = computed(() => page.props.name as string);
const siteName = usePage<{ name: string }>().props.name;
</script>

<template>

    <Head :title="pageTitle" />
    <div class="min-h-screen bg-[#FDFDFC] text-[#1b1b18] dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
        <header class="container px-4 py-6 mx-auto">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <Link :href="route('home')" class="text-xl font-bold">{{ siteName }}
                    </Link>
                </div>
                <nav class="flex items-center space-x-6">
                    <Link :href="route('home')" class="text-sm transition-colors hover:text-primary">Home</Link>
                    <Link :href="route('portfolio.all')" class="text-sm transition-colors hover:text-primary">Projects
                    </Link>
                    <button @click="toggleDarkMode"
                        class="p-2 transition-colors rounded-full hover:bg-gray-100 dark:hover:bg-gray-800">
                        <svg v-if="isDarkMode" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                clip-rule="evenodd" />
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                        </svg>
                    </button>
                    <template v-if="$page.props.auth.user">
                        <Link :href="route('dashboard')"
                            class="px-4 py-2 text-sm transition-colors border border-gray-200 rounded-md dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800">
                        Dashboard</Link>
                    </template>
                </nav>
            </div>
        </header>
        <main class="container px-4 py-8 mx-auto">
            <slot />
        </main>
        <footer class="container px-4 py-8 mx-auto border-t border-gray-200 dark:border-gray-800">
            <div class="flex flex-col items-center justify-between md:flex-row">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    &copy; {{ new Date().getFullYear() }} {{ siteName }}. All rights
                    reserved.
                </div>
                <div class="flex mt-4 space-x-4 md:mt-0">
                    <a v-for="social in generalSettings.socials" :key="social.name" :href="social.url" target="_blank"
                        class="text-gray-500 transition-colors hover:text-primary dark:text-gray-400 dark:hover:text-primary">
                        <img :src="social.previewImageUrl ?? ''" :alt="social.name" class="w-6 h-6" />
                    </a>
                </div>
            </div>
        </footer>
    </div>
</template>

<style>
.text-primary {
    color: hsl(var(--primary));
}
</style>