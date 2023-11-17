[200~<?php
$link = mysqli_connect('localhost', 'ecomuser', 'ecompassword', 'ecomdb');
if (!$link) {
	    die('Could not connect: ' . mysqli_error());
}

$query = "SELECT * FROM products;";
$result = mysqli_query($link, $query);

if (!$result) {
	    die('Error in query: ' . mysqli_error($link));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Azure TechScript Mall</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        main {
            padding: 1rem;
        }
        .product {
            border: 1px solid #ddd;
            padding: 1rem;
            margin: 1rem;
            text-align: center;
            background-color: #fff;
            border-radius: 5px;
            display: inline-block;
        }
        img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <header>
        <h1>Azure TechScript Mall</h1>
    </header>

    <main>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="product">';
            echo '<img src="' . $row['ImageUrl'] . '" alt="' . $row['Name'] . '">';
            echo '<h2>' . $row['Name'] . '</h2>';
            echo '<p>$' . $row['Price'] . '</p>';
            echo '</div>';
        }

        // Close the connection
//         mysqli_close($link);
//                 ?>
//                     </main>
//
//                     </body>
//                     </html>
//
