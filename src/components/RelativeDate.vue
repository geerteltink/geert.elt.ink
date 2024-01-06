<script setup lang="ts">
import { formatDistanceToNow, formatISO } from 'date-fns';

const props = defineProps({
  date: {
    type: Date,
    required: true,
  },
});

const isoDate = formatISO(props.date);

const relativeDate = formatDistanceToNow(props.date, {
  addSuffix: true,
});
</script>

<template>
  <time :datetime="isoDate">
    {{ relativeDate }}
  </time>
</template>
