<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Ytweb</title>
  @livewireStyles
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="bg-gray-800 text-gray-200">

  <div class="container mx-auto px-4">
    @livewire('url-form')
    @livewire('file-list')
  </div>

  <script src="{{ asset('js/app.js') }}"></script>
  @livewireScripts
</body>

</html>