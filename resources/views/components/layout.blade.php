<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
      [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full" style="background-color: #f3f4f6;">

<div class="min-h-full" style="background-color: #f3f4f6;">
  <x-navbar />
  <!-- Header Judul Halaman (Putih & Memiliki Bayangan) -->
  <x-header :title="$title" />

  <main style="background-color: #f3f4f6; min-height: calc(100vh - 160px);">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        {{  $slot }}
    </div>
  </main>
</div>
  <script src="https://cdm.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
</html>