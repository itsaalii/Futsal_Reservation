<!doctype html>
<html lang="id" class="h-full">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Admin | Jaya Futsal')</title>

    <!-- Google Fonts: Plus Jakarta Sans & IBM Plex Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['"Plus Jakarta Sans"', '"IBM Plex Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            colors: {
              primary: {
                DEFAULT: '#2F6BFF',
                dark: '#3A4BD1',
                50: '#EEF4FF',
                100: '#DDE9FF',
                200: '#C0D5FF',
                300: '#94BAFF',
                400: '#6196FF',
                500: '#2F6BFF',
                600: '#2557DE',
                700: '#3A4BD1',
                800: '#2935A1',
                900: '#1E2A78',
              },
              // Map teal to royal blue palette so existing markup updates harmoniously
              teal: {
                50: '#EEF4FF',
                100: '#DDE9FF',
                200: '#C0D5FF',
                300: '#94BAFF',
                400: '#6196FF',
                500: '#2F6BFF',
                600: '#2F6BFF',
                700: '#3A4BD1',
                800: '#2935A1',
                900: '#1E2A78',
              },
              secondary: '#7B5CE6',
              accent: '#4FA8F0',
              brand: {
                page: '#DCE4EF',
                sidebar: '#C9D6F2',
                card: 'rgba(255, 255, 255, 0.55)',
                border: 'rgba(255, 255, 255, 0.7)',
                heading: '#12162B',
                name: '#1E2A78',
                muted: '#5B6478',
                disabled: '#8A93A6',
                success: '#22B14C',
                warning: '#F5A524',
                danger: '#EF4F5F',
                info: '#3B82F6',
              },
              ink: '#12162B',
              paper: '#DCE4EF',
            }
          }
        }
      }
    </script>

    <style>
      :root {
        --primary: #2F6BFF;
        --primary-dark: #3A4BD1;
        --secondary: #7B5CE6;
        --accent: #4FA8F0;

        --bg-page: #DCE4EF;
        --bg-sidebar: #C9D6F2;
        --bg-card: rgba(255, 255, 255, 0.55);
        --border-card: rgba(255, 255, 255, 0.7);

        --text-heading: #12162B;
        --text-name: #1E2A78;
        --text-muted: #5B6478;
        --text-disabled: #8A93A6;

        --success: #22B14C;
        --warning: #F5A524;
        --danger: #EF4F5F;
        --info: #3B82F6;
      }

      body {
        background-color: var(--bg-page);
        background-image: 
          radial-gradient(circle at 12% 15%, rgba(79, 168, 240, 0.22) 0%, transparent 45%),
          radial-gradient(circle at 88% 80%, rgba(123, 92, 230, 0.18) 0%, transparent 50%),
          radial-gradient(circle at 50% 50%, rgba(47, 107, 255, 0.08) 0%, transparent 60%);
        background-attachment: fixed;
        color: var(--text-heading);
      }

      .glass-card {
        background: var(--bg-card);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border-card);
        border-radius: 1.25rem;
        box-shadow: 0 10px 30px -5px rgba(30, 42, 120, 0.05), 0 4px 10px -2px rgba(0, 0, 0, 0.02);
      }

      .glass-sidebar {
        background: rgba(201, 214, 242, 0.7);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border-right: 1px solid rgba(255, 255, 255, 0.75);
      }

      .glass-header {
        background: rgba(255, 255, 255, 0.55);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.75);
      }

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
        background: rgba(138, 147, 166, 0.4);
        border-radius: 999px;
      }
      ::-webkit-scrollbar-thumb:hover {
        background: rgba(138, 147, 166, 0.7);
      }
    </style>

    @stack('styles')
  </head>
  <body class="bg-paper text-ink font-sans antialiased min-h-screen flex flex-col selection:bg-primary-100 selection:text-primary-900 @yield('body-class')">
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
