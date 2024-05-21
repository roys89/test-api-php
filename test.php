
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fare Quote</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
        }
        .input-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .result {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .result img {
            max-width: 100%;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Get Fare Quote</h1>
    <form method="POST">
        <div class="input-group">
            <label for="origin">Origin Address</label>
            <input type="text" id="origin" name="origin" required>
        </div>
        <div class="input-group">
            <label for="destination">Destination Address</label>
            <input type="text" id="destination" name="destination" required>
        </div>
        <button type="submit">Get Quote</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $origin = htmlspecialchars($_POST['origin']);
        $destination = htmlspecialchars($_POST['destination']);

        $data = [
            "destination" => [
                "display_address" => $destination,
                "lat" => "12.998287",
                "long" => "77.59181"
            ],
            "origin" => [
                "display_address" => $origin,
                "lat" => "28.632425",
                "long" => "77.218791"
            ],
            "journey_type" => "oneway"
        ];

        $apiUrl = 'https://staging.leamigo.com/booking/get-quotes';
        $apiKey = 'a5O~radKoYxSp6xwlMMe3V';
        $options = [
            'http' => [
                'header'  => [
                    "Content-Type: application/json",
                    "X-API-KEY: $apiKey",
                    "accept: application/json"
                ],
                'method'  => 'POST',
                'content' => json_encode($data),
            ],
        ];
        $context  = stream_context_create($options);
        $result = file_get_contents($apiUrl, false, $context);

        if ($result === FALSE) {
            echo '<p>There was an error fetching the fare quote.</p>';
        } else {
            $response = json_decode($result, true);
            if (isset($response['data'][0])) {
                $quote = $response['data'][0];
                echo '<div class="result">';
                echo '<h2>Fare Quote</h2>';
                echo '<p>Class: ' . htmlspecialchars($quote['class']) . '</p>';
                echo '<p>Capacity: ' . htmlspecialchars($quote['capacity']) . '</p>';
                echo '<p>Max Luggage: ' . htmlspecialchars($quote['maxLuggage']) . '</p>';
                echo '<p>Tags: ' . htmlspecialchars($quote['tags']) . '</p>';
                echo '<p>Meet Charges: ' . htmlspecialchars($quote['meet_charges']) . '</p>';
                echo '<p>Fare: ' . htmlspecialchars($quote['fare']) . '</p>';
                echo '<img src="' . htmlspecialchars($quote['image']) . '" alt="Vehicle Image">';
                echo '</div>';
            } else {
                echo '<p>No fare quote found.</p>';
            }
        }
    }
    ?>
</div>

</body>
</html>


