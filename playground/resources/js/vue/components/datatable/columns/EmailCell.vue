<template>
  <a :href="href" class="underline underline-offset-4">{{ label }}</a>
</template>

<script setup lang="ts">
defineProps<{
  /**
   * Display label of the email link.
   */
  label: string

  /**
   * Destination supplied by the PHP column.
   */
  href: string
}>()
</script>
