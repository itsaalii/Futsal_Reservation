<!doctype html>
<html lang="id" class="h-full">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Admin | Jaya Futsal')</title>

    <!-- Google Font: IBM Plex Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['"IBM Plex Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            colors: {
              ink: '#18232F',
              paper: '#F2F5F7',
              teal: {
                50: '#E7F4F2',
                100: '#CBE6E2',
                200: '#97CFC7',
                300: '#5EEAD4',
                400: '#2DD4BF',
                500: '#14B8A6',
                600: '#0F766E',
                700: '#0B5F58',
                800: '#084843',
                900: '#05312E',
              }
            }
          }
        }
      }
    </script>

    <style>
      /* Smooth transitions for collapsible sidebar */
      #adminSidebar.collapsed {
        margin-left: -16rem;
      }
      /* Custom scrollbars */
      ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
      }
      ::-webkit-scrollbar-track {
        background: transparent;
      }
      ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 999px;
      }
      ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
      }
    </style>

    @stack('styles')
  </head>
  <body class="bg-paper text-ink font-sans antialiased min-h-screen flex flex-col selection:bg-teal-100 selection:text-teal-900 @yield('body-class')">
    <div class="flex-1 flex min-h-screen overflow-x-hidden">
      <!-- Left Sidebar Navigation -->
      @include('partials.admin.sidebar')

      <!-- Main Content Area -->
      <div class="flex-1 flex flex-col min-w-0 transition-all duration-300">
        @yield('content')
      </div>
    </div>

    <!-- Core Admin Layout Scripts -->
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.getElementById('adminSidebar');
        const toggleBtn = document.getElementById('toggleSidebarBtn');
        const closeBtn = document.getElementById('closeSidebarBtn');
        const backdrop = document.getElementById('sidebarBackdrop');

        // Check stored desktop preference
        const isDesktop = () => window.innerWidth >= 768;
        const storedCollapse = localStorage.getItem('admin_sidebar_collapsed') === 'true';

        if (isDesktop() && storedCollapse && sidebar) {
          sidebar.classList.add('collapsed');
        }

        // Toggle Sidebar Function
        function toggleSidebar() {
          if (!sidebar) return;

          if (isDesktop()) {
            // Desktop toggle (slide out to left / collapse)
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('admin_sidebar_collapsed', sidebar.classList.contains('collapsed'));
          } else {
            // Mobile toggle (drawer open/close)
            const isOpen = !sidebar.classList.contains('-translate-x-full');
            if (isOpen) {
              closeMobileSidebar();
            } else {
              openMobileSidebar();
            }
          }
        }

        function openMobileSidebar() {
          if (!sidebar) return;
          sidebar.classList.remove('-translate-x-full');
          if (backdrop) backdrop.classList.remove('hidden');
          document.body.classList.add('overflow-hidden');
        }

        function closeMobileSidebar() {
          if (!sidebar) return;
          sidebar.classList.add('-translate-x-full');
          if (backdrop) backdrop.classList.add('hidden');
          document.body.classList.remove('overflow-hidden');
        }

        if (toggleBtn) {
          toggleBtn.addEventListener('click', toggleSidebar);
        }

        if (closeBtn) {
          closeBtn.addEventListener('click', closeMobileSidebar);
        }

        if (backdrop) {
          backdrop.addEventListener('click', closeMobileSidebar);
        }

        window.addEventListener('resize', () => {
          if (isDesktop()) {
            if (backdrop) backdrop.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            if (sidebar) {
              sidebar.classList.remove('-translate-x-full');
              if (localStorage.getItem('admin_sidebar_collapsed') === 'true') {
                sidebar.classList.add('collapsed');
              } else {
                sidebar.classList.remove('collapsed');
              }
            }
          } else {
            if (sidebar) {
              sidebar.classList.remove('collapsed');
              sidebar.classList.add('-translate-x-full');
            }
          }
        });
      });

      // Simple Universal Modal Helper for Tailwind
      window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
          modal.classList.remove('hidden');
          document.body.classList.add('overflow-hidden');
        }
      };

      window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
          modal.classList.add('hidden');
          document.body.classList.remove('overflow-hidden');
        }
      };
    </script>

    @stack('scripts')
  </body>
</html>
