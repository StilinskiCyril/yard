<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="author" content="CarYard">
    <meta name="keywords" content="Buy Phones, Tablets, Tv's, Laptops, Fridges, Playstations, Groceries, Electronics, Music, Radio, Flash Disks, Baby, Women, Men, Utensils, Microwaves, Lipstick, Beautiful, Skin, Video games, Home decor, Eyebrows, Fashion, Model, Photography, Style, Appliances, Computers, Fragrances, Gym">
    <meta name="description" content="The Best Online Shop In Kenya, We Are The Fastest Growing Online Store Providing Quality And Affordable Products">
    <meta name="robots" content="index">
    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    @routes
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @inertiaHead

</head>

<body>
    @inertia
    <script src="https://kit.fontawesome.com/2f27e2edbb.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
</body>

</html>
