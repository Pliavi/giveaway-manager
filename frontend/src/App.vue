<template>
  <div v-for="(giveaway, key) in giveaways" :key="key">
    
  </div>
</template>

<script>
import api from "./util/api";
import { onMounted, ref } from "vue";

export default {
  name: "App",
  setup() {
    const giveaways = ref([]);

    onMounted(async () => {
      const response = await api.get("/giveaways");
      giveaways.value = response.data;
    });

    return {
      giveaways,
    };
  },
};
</script>