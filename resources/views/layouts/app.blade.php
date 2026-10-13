<!doctype html>
<html lang="id" class="h-full">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Jaya Futsal')</title>

    <!-- Google Font: Plus Jakarta Sans & IBM Plex Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          container: {
            center: true,
            padding: {
              DEFAULT: '1.25rem',
              sm: '2rem',
              lg: '2.5rem',
              xl: '3rem',
              '2xl': '4rem',
            },
          },
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

    <!-- Core styling assets for user wizard / modals -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />

    @stack('styles')
  </head>
  <body class="bg-paper text-ink font-sans antialiased min-h-screen selection:bg-primary-100 selection:text-primary-900 @yield('body-class')">
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @stack('scripts')
  </body>
</html>
