<template>
  {{ value ?? '—' }}
</template>

<script setup lang="ts">
defineProps<{
  /**
   * Text value supplied by the PHP column.
   */
  value: string | number | null
}>()
</script>
