<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #fff;
            color: #ff0000;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #ff0000;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ff0000;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #ff0000;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #ffe6e6;
        }

        .back-arrow {
            font-size: 40px;
            position: absolute;
            top: 20px;
            left: 20px;
            color: #ff0000;
            cursor: pointer;
            text-decoration: none;
        }

        @media only screen and (max-width: 600px) {
            table {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <a href="/" class="back-arrow">&#x2190;</a>

    <h1>Leaderboard</h1>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Score</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leaderboard as $entry)
            <tr>
                <td>{{ $entry->name }}</td>
                <td>{{ $entry->score }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
