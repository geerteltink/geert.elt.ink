---
title: "Vue.js 3.2 Composition API Setup with TypeScript"
description: "If you have the opportunity to attend conferences, talks or a workshop, just go for it!"
slug: "vue-js-3-2-composition-api-setup-with-typescript"
pubDate: "2021-09-15"
tags:
  - "vue.js"
---

{% image "./src/assets/images/logo-vuejs.png", "Vue.js" %}

Since Vue.js 3.2 you can use the `<script setup>` compile-time syntactic sugar for using Composition API inside Single File Components (SFCs). It is now the recommended syntax if you are using both SFCs and Composition API.

It has some advantages:

- less boilerplate
- declare props and emitted events using pure TypeScript
- runtime performance
- better IDE type-inference performance

What I like from it is that you don't need to return anything anymore. Top-level bindings are exposed to the template automatically, and the same goes for imports.

I haven't found complete examples on how to use it with TypeScript. While playing with this new feature, I've created an example for testing. I'm just going to leave it here so it might help someone else too.

```js
<script setup lang="ts">
import { nextId } from '@lib/api';
import { Session } from '@lib/domain';
import { useHead } from '@vueuse/head';
import {
  computed,
  defineProps,
  inject,
  onBeforeMount,
  onBeforeUnmount,
  onBeforeUpdate,
  onErrorCaptured,
  onMounted,
  onRenderTracked,
  onRenderTriggered,
  onUnmounted,
  onUpdated,
  ref,
  toRef,
  watch,
} from 'vue';

function Sleep(milliseconds: number) {
  return new Promise((resolve) => setTimeout(resolve, milliseconds));
}

const props = defineProps<{
  message?: string;
}>();

/* State */

const id = nextId();
const title = 'Composition API Based Component';
const propMessage = toRef(props, 'message');
const msg = ref<string | number>(id);
const helloMsg = 'Hello, ' + propMessage.value;
const counter = ref(0);

const session = inject('session') as Session;

useHead({
  title: title,
});

/* Computed props */

const computedMsg = computed(() => 'computed ' + msg.value);

/* Methods */

const greet = () => {
  counter.value++;
  console.log('greeting: ' + msg.value);
  session.logout();
};

const asyncGreet = async () => {
  await Sleep(6000);
  counter.value++;
  console.log('async greeting: ' + msg.value);
  session.refresh();
};

/* Watchers */

const msgWatch = watch(msg, (newVal, oldVal) => console.log('Msg changed', newVal, oldVal));

watch(counter, (newVal, oldVal) => console.log('Counter changed', newVal, oldVal));

/* Hooks */

onBeforeMount(() => {
  console.log('onBeforeMount');
  session.login('name', 'password');
});

onMounted(() => {
  console.log('onMounted');
});

onBeforeUpdate(() => {
  console.log('onBeforeUpdate');
});

onBeforeUnmount(() => {
  console.log('onBeforeUnmount');
});

onUnmounted(() => {
  console.log('onUnmounted');
  session.logout();
});

onErrorCaptured(() => {
  console.log('onErrorCaptured');
});

onRenderTracked(() => {
  console.log('onRenderTracked');
});

onRenderTriggered(() => {
  console.log('onRenderTriggered');
});

onUpdated(() => {
  console.log('onUpdated');
});
</script>
```
