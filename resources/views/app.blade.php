<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Web scrapper</title>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
    <script>
        window.authUser = @json(Auth::user())
    </script>

    @vite('resources/css/app.css')
</head>
<body>
<div id="app"></div>
@vite('resources/js/app.js')

</body>
</html>
