<template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <router-link
        :to="{ name: 'home.index' }"
        class="navbar-brand"
      >
        Perekrestok events
      </router-link>
    </nav>

    <div class="container pt-4">
      <div class="row">
        <div class="col col-12">
          <slot name="header"/>
          <slot name="body"/>
          <slot name="footer"/>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
  /* ============
   * Default Layout
   * ============
   *
   * Used for the home and account pages.
   *
   * Layouts are used to store a lot of shared code.
   * This way the app stays clean.
   */

  export default {
    /**
     * The name of the layout.
     */
    name: 'DefaultLayout',

    /**
     * The data that can be used by the page.
     *
     * @returns {Object} The view-model data.
     */
    data() {
      return {
      };
    },

    /**
     * The methods that the layout can use.
     */
    methods: {
    },
  };
</script>
