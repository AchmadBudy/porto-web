<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { CategoryForm, type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import InputError from '@/components/InputError.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create Category',
        href: '/admin/categories/create',
    },
];

const form = useForm<CategoryForm>({
    name: '',
    description: '',
});

const submit = () => {
    form.post(route('admin.categories.store'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>

    <Head title="Create Category" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col flex-1 h-full gap-4 p-4 rounded-xl">
            <div class="relative flex justify-end rounded-xl dark:border-sidebar-border">
                <Button as-child>
                    <Link :href="route('admin.categories.index')">
                    Back
                    </Link>
                </Button>
            </div>
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <div class="p-8 space-y-6">
                    <HeadingSmall title="Crate Category" description="Just Create Your Project Category" />

                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid gap-2">
                            <Label for="name">Category Name</Label>
                            <Input id="name" ref="nameInput" v-model="form.name" type="text" class="block w-full mt-1"
                                autocomplete="name" placeholder="Category Name" />
                            <InputError :message="form.errors.name" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="description">Category Description</Label>
                            <Textarea id="description" ref="descriptionInput" v-model="form.description" type="text"
                                class="block w-full mt-1" autocomplete="description"
                                placeholder="Category Description" />
                            <InputError :message="form.errors.description" />
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
