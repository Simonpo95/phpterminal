<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>alPH - @yield('title')</title>

    <link rel="icon" href="/favicon.ico">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" integrity="sha384-3AB7yXWz4OeoZcPbieVW64vVXEwADiYyAEhwilzWsLw+9FgqpyjjStpPnpBO8o8S" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/admin.css?{{ uniqid(rand()) }}">
    @yield('styles')
</head>

<body class="wrapper toggled">
    <header>
        <nav class="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="/" class="logo">
                        <img src="/assets/img/alph-logo-xl.png" class="logo">
                    </a>
                </li>
                <li>
                    <a href="/admin">Dashboard</a>
                </li>
                <li>
                    <a href="/admin/terminal">Terminals</a>
                </li>
                <li>
                    <a href="/admin/network">Networks</a>
                </li>
                <li>
                    <a href="/admin/account">Users</a>
                </li>
                <li>
                    <a href="/admin/referential">Referencial</a>
                </li>
            </ul>
        </nav>
    </header>

    @yield('content')

    @yield('script')
</body>

</html>
