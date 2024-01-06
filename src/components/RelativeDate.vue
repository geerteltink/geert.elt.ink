<script setup lang="ts">
import { formatDistanceToNow, formatISO } from 'date-fns';

const props = defineProps({
  date: {
    type: Date,
    required: false,
    default: null,
  },
});

const isoDate = formatISO(props.date);

const relativeDate = formatDistanceToNow(props.date, {
  addSuffix: true,
});
</script>

<template>
  <time :datetime="isoDate" v-if="date">
    {{ relativeDate }}
  </time>
</template>
