<template>
  <v-layout>
    <div slot="body">
      <div>
        <v-table></v-table>
      </div>
    </div>
    <div slot="footer">
    </div>
  </v-layout>
</template>

<script>
  /* ============
   * Home Index Page
   * ============
   *
   * The home index page.
   */

  import VLayout from '@/layouts/Default.vue';
  import VTable from '@/components/Table.vue';


  export default {
    /**
     * The name of the page.
     */
    name: 'EventsIndex',

    /**
     * The components that the page can use.
     */
    components: {
      VLayout,
      VTable
    },
  };
</script>
