<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

const props = defineProps<{
  placeholder?: string;
}>();

const modelValue = defineModel<string>();
const editorEl = ref<HTMLElement | null>(null);
const quill = ref<Quill | null>(null);


onMounted(() => {
  if (editorEl.value) {
    quill.value = new Quill(editorEl.value, {
      theme: 'snow',
      placeholder: props.placeholder || 'Type here...',
    });

    quill.value.on('text-change', () => {
      modelValue.value = quill.value?.root.innerHTML;
    });

    // Set initial content
    if (modelValue.value) {
      quill.value.root.innerHTML = modelValue.value;
    }
  }
});

onBeforeUnmount(() => {
  if (quill.value) {
    quill.value.off('text-change');
  }
});


</script>

<template>
  <div class="mb-12 quill-editor">
    <div ref="editorEl"></div>
  </div>
</template>

<style scoped></style>