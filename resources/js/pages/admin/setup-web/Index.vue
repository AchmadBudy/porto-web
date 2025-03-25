<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { RepeaterDynamic, RepeaterSingle } from '@/components/ui/repeater';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, GeneralSettings } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const { generalSettings } = defineProps<{ generalSettings: GeneralSettings }>();

const form = useForm<GeneralSettings>({
    name: generalSettings.name,
    title: generalSettings.title,
    bio: generalSettings.bio,
    avatar: generalSettings.avatar,
    skills: generalSettings.skills ?? [],
    socials: generalSettings.socials ?? [],
    _method: 'PUT',
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Edit Global Settings',
        href: '/admin/setup-web',
    },
];

const imagePreview = ref<string | null>(null);
const imageInput = ref<string>('');
const previewImage = (e: Event) => {
    const target = e.target as HTMLInputElement;
    const file = target.files?.[0];

    if (file) {
        form.avatar = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const clearImage = () => {
    imagePreview.value = null;
    form.avatar = null;
    if (imageInput.value) {
        // Create a new form to reset file input
        imageInput.value = '';
    }
};

const submit = () => {
    form.post(route('admin.settings.web-setup.update'), {
        onFinish: () => {
            form.reset('socials');
        }
    });
};

</script>

<template>

    <Head title="Edit Global Settings" />

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
                    <HeadingSmall title="Edit Global Settings" description="Just Edit The Global Settings" />

                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid gap-2">
                            <Label for="name">Your Name</Label>
                            <Input id="name" ref="nameInput" v-model="form.name" type="text" class="block w-full mt-1"
                                autocomplete="name" placeholder="Project Name" />
                            <InputError :message="form.errors.name" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="title">Your Title</Label>
                            <Input id="title" ref="titleInput" v-model="form.title" type="text"
                                class="block w-full mt-1" autocomplete="title" placeholder="Project title" />
                            <InputError :message="form.errors.title" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="bio">Your Bio</Label>
                            <Textarea id="bio" ref="bioInput" v-model="form.bio" type="text" class="block w-full mt-1"
                                autocomplete="bio" placeholder="Project bio" />
                            <InputError :message="form.errors.bio" />
                        </div>

                        <div class="grid gap-2">
                            <Label>Your Skills</Label>

                            <RepeaterSingle v-model="form.skills" placeholder="Enter your skills..." />
                            <InputError :message="form.errors.skills" />
                        </div>

                        <div class="grid gap-2">
                            <Label>Your Socials</Label>

                            <RepeaterDynamic ref="repeaterRef" :fields="[
                                { name: 'name', label: 'Name', placeholder: 'Enter social name' },
                                { name: 'url', label: 'URL', placeholder: 'Enter social URL' },
                                {
                                    name: 'imageUrl', label: 'Image URL', placeholder: 'Enter social image URL', type: 'file', acceptFile: 'image/*'
                                },]" v-model="form.socials" :errors="form.errors" />
                            <InputError :message="form.errors.socials" />
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
                            <InputError :message="form.errors.avatar" />
                        </div>

                        <div class="flex items-center gap-4">
                            <Button :disabled="form.processing">Update Settings</Button>

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