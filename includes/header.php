<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">
    <link rel="stylesheet" href="/css/styles.css">
    <title>Blog</title>
</head>

<body>

    <div class="container">

        <header>
            <h1>My Blog</h1>
        </header>

        <nav>
            <ul class="nav">
                <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                <?php if (Auth::isLoggedIn()) : ?>
                    <li class="nav-item"><a class="nav-link" href="/admin/">Admin</a></li>
                    <li class="nav-item"><a class="nav-link" href="/logout.php">Log out</a></li>
                <?php else : ?>
                    <li class="nav-item"><a class="nav-link" href="/login.php">Log in</a></li>
                <?php endif; ?>

                <li class="nav-item"><a class="nav-link" href="/contact.php">Contact</a></li>
            </ul>
        </nav>

        <main>