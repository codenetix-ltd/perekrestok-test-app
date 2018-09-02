/* ============
 * Routes File
 * ============
 *
 * The routes and redirects are defined in this file.
 */

export default [
  // Home
  {
    path: '/events',
    name: 'events.index',
    component: () => import('@/views/Events/Index.vue'),
  },

  {
    path: '/',
    redirect: '/events',
  },

  {
    path: '/*',
    redirect: '/events',
  },
];
