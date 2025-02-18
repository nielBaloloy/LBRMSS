<template>
  <q-list>
    <template v-for="(item, index) in menuItems" :key="index">
      <q-item
        v-if="!item.children"
        :to="item.to"
        active-class="text-orange"
        exact
        @click="activeclick"
      >
        <q-item-section avatar v-if="item.icon">
          <q-icon color="primary" :name="item.icon" />
        </q-item-section>
        <q-item-section>{{ item.label }}</q-item-section>
      </q-item>

      <q-expansion-item
        v-else
        expand-separator
        :icon="item.icon"
        :label="item.label"
        header-class="text-dark"
      >
        <q-item
          v-for="(child, cIndex) in item.children"
          :key="cIndex"
          :to="child.to"
          active-class="text-orange"
          exact
          @click="activeclick"
        >
          <q-item-section avatar v-if="child.icon">
            <q-icon color="primary" :name="child.icon" />
          </q-item-section>
          <q-item-section>{{ child.label }}</q-item-section>
        </q-item>
      </q-expansion-item>
    </template>
  </q-list>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
  menuItems: {
    type: Array,
    required: true,
  },
});

const activeclick = () => {
  console.log("Menu item clicked");
};
</script>
