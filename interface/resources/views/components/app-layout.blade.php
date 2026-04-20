<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'TriageApp' }}</title>

    @vite(['resources/css/app.css'])

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body class="bg-gradient-to-br from-blue-50 to-purple-100 min-h-screen font-sans">

<!-- 🌸 Navbar -->
<x-navbar/>

<!-- 🌼 Page container -->
<main class="max-w-5xl mx-auto px-6 py-8">

    <!-- ✨ Card wrapper -->
    <div class="bg-white rounded-3xl shadow-lg p-6">
        {{ $slot ?? '' }}
    </div>

</main>

</body>
</html>
