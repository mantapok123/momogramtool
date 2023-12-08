<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nomogram Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Bangers&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .container {
            margin-top: 30px;
        }

        .result-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            animation: slideUp 0.5s ease-in-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .reset-button {
            background-color: #ff0019;
            color: #fff;
            border: none;
            padding: 5px 15px;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            animation: fadeIn 1s ease-in-out;
        }

        .chart-container {
            margin-top: 20px;
        }

        .chart {
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            background-color: #fff;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        h1 {
            color: #FF0C64;
    font-family: "Bangers", Sans-serif;
    font-size: 67px;
        }
        p{
            text-align:center;
        }
    </style>
</head>

<body style="background-color:#FFB3BB;">
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sample nomogram scoring algorithm
    $atypia = $_POST['atypia'];
    $overgrowth = $_POST['overgrowth'];
    $surgical = $_POST['surgical'];
    $mitoses = $_POST['mitoses'];
    
    // Implement your nomogram scoring logic here based on risk factors
    $mitoses_points = calculateMitosesPoints($mitoses);

    // Calculate the total nomogram score
    $nomogram_score = $atypia + $overgrowth + $surgical + $mitoses_points;

    // Display the nomogram score
    echo "<div class='container result-container'>";
    echo "<h1 style=' text-align:center;'>Nomogram Score: $nomogram_score</h1>";
    echo "</div>";
} else {
    // Redirect to the risk assessment page if accessed directly without submitting the form
    header("Location: index.html");
    exit();
}

function calculateMitosesPoints($mitoses) {
    if ($mitoses >= 0 && $mitoses <= 80) {
        // Your logic for calculating points based on Mitoses
        if ($mitoses == 0) return 0;
        elseif ($mitoses >= 1 && $mitoses <= 10) return 3;
        elseif ($mitoses >= 11 && $mitoses <= 20) return 6;
        elseif ($mitoses >= 21 && $mitoses <= 30) return 9;
        elseif ($mitoses >= 31 && $mitoses <= 40) return 12;
        elseif ($mitoses >= 41 && $mitoses <= 50) return 15;
        elseif ($mitoses >= 51 && $mitoses <= 60) return 18;
        elseif ($mitoses >= 61 && $mitoses <= 70) return 21;
        elseif ($mitoses >= 71 && $mitoses <= 80) return 24;
    } else {
        return 'Invalid Mitoses value';
    }
}
?>


    <div class="container result-container">
        <div class="row">
            <div class="col-md-4">
                <?php
                echo "<h2 style='color: pink;'>Survival Rate</h2>";

                function calculateExpressionsurvival($factor, $multiplier)
                {
                    global $nomogram_score;
                    $innerExponent = 0.052103 * $nomogram_score;
                    $innerResult = exp($innerExponent);
                    $outerExponent = $multiplier * $innerResult;
                    $survival = exp($outerExponent);
                    return $survival;
                }

                // Panggil fungsi dan hitung hasil
                $survival1 = calculateExpressionsurvival(1, -0.0060980953);
                $survival3 = calculateExpressionsurvival(3, -0.0210056562);
                $survival5 = calculateExpressionsurvival(5, -0.0323593232);
                $survival10 = calculateExpressionsurvival(10, -0.0416782685);
                // Buletkan hasil
                $roundedsurvival1 = ceil($survival1 * 100); // Mengalikan dengan 100 untuk mendapatkan persen
                $roundedsurvival3 = ceil($survival3 * 100);
                $roundedsurvival5 = ceil($survival5 * 100);
                $roundedsurvival10 = ceil($survival10 * 100);
                // Tampilkan hasil
                echo "<p>1-year FFR: $roundedsurvival1%</p>";
                echo "<p>3-year FFR: $roundedsurvival3%</p>";
                echo "<p>5-year FFR: $roundedsurvival5%</p>";
                echo "<p>10-year FFR: $roundedsurvival10%</p>";
               
                        // Display the nomogram score and survival rates
        echo "<div class='container result-container'>";
        // echo "<h2 style='color:#f78da7; text-align:center;'>Nomogram Score: $nomogram_score</h2>";
        echo "<canvas id='myChartsurvival' width='400' height='400'></canvas>";
        echo "</div>";

        // Chart.js script
        echo "<script src='https://cdn.jsdelivr.net/npm/chart.js'></script>";
        echo "<script>";
        echo "var ctx = document.getElementById('myChartsurvival').getContext('2d');";
        echo "var myChartsurvival = new Chart(ctx, {";
        echo "type: 'line',";
        echo "data: {";
        echo "labels: ['1-year FFR', '3-year FFR', '5-year FFR', '10-year FFR'],";
        echo "datasets: [{";
        echo "label: 'Survival Rate (%)',";
        echo "data: [$roundedsurvival1, $roundedsurvival3, $roundedsurvival5, $roundedsurvival10],";
        echo "backgroundColor: 'rgba(247, 141, 167, 0.5)',";
        echo "borderColor: 'rgba(247, 141, 167, 1)',";
        echo "borderWidth: 1";
        echo "}]}";
        echo "});";
        echo "</script>";
               
               ?>
            </div>

            <div class="col-md-4">
                <?php
               echo "<h2 style='color: red;'>Upper Limit</h2>";


                function calculateExpressionUpper($multiplier)
                {
                    global $nomogram_score;
                    $innerExponent = (0.052103 - 1.96 * 0.005728) * $nomogram_score;
                    $innerResult = exp($innerExponent);
                    $outerExponent = $multiplier * $innerResult;
                    $upper = exp($outerExponent);
                    return $upper;
                }

                // Panggil fungsi dan hitung hasil
                $upper1 = calculateExpressionUpper(-0.0060980953);
                $upper3 = calculateExpressionUpper(-0.0210056562);
                $upper5 = calculateExpressionUpper(-0.0323593232);
                $upper10 = calculateExpressionUpper(-0.0416782685);

                // Buletkan hasil
                $upper1 = ceil($upper1 * 100);
                $upper3 = ceil($upper3 * 100);
                $upper5 = ceil($upper5 * 100);
                $upper10 = ceil($upper10 * 100);

                // Tampilkan hasil
                echo "<p>1-year FFR: $upper1%</p>";
                echo "<p>3-year FFR: $upper3%</p>";
                echo "<p>5-year FFR: $upper5%</p>";
                echo "<p>10-year FFR: $upper10%</p>";


    // Display the nomogram score and survival rates
    echo "<div class='container result-container'>";
    // echo "<h2 style='color:#f78da7; text-align:center;'>Nomogram Score: $nomogram_score</h2>";
    echo "<canvas id='myChartupper' width='400' height='400'></canvas>";
    echo "</div>";

    // Chart.js script
    echo "<script src='https://cdn.jsdelivr.net/npm/chart.js'></script>";
    echo "<script>";
    echo "var ctx = document.getElementById('myChartupper').getContext('2d');";
    echo "var myChartupper = new Chart(ctx, {";
    echo "type: 'line',";
    echo "data: {";
    echo "labels: ['1-year FFR', '3-year FFR', '5-year FFR', '10-year FFR'],";
    echo "datasets: [{";
    echo "label: 'Upper Limit (%)',";
    echo "data: [$upper1, $upper3, $upper5, $upper10],";
    echo "backgroundColor: 'red',";
    echo "borderColor: 'red',";
    echo "borderWidth: 1";
    echo "}]}";
    echo "});";
    echo "</script>";

                ?>
            </div>

            <div class="col-md-4">
                <?php
                   echo "<h2 style='color: blue;'>Lower Limit</h2>";

                function calculateExpressionLower($multiplier)
                {
                    global $nomogram_score;
                    $innerExponent = (0.052103 + 1.96 * 0.005728) * $nomogram_score;
                    $innerResult = exp($innerExponent);
                    $outerExponent = $multiplier * $innerResult;
                    $lower = exp($outerExponent);
                    return $lower;
                }

                // Panggil fungsi dan hitung hasil
                $lower1 = calculateExpressionLower(-0.0060980953);
                $lower3 = calculateExpressionLower(-0.0210056562);
                $lower5 = calculateExpressionLower(-0.0323593232);
                $lower10 = calculateExpressionLower(-0.0416782685);

                // Buletkan hasil
                $lower1 = ceil($lower1 * 100);
                $lower3 = ceil($lower3 * 100);
                $lower5 = ceil($lower5 * 100);
                $lower10 = ceil($lower10 * 100);

                // Tampilkan hasil
                echo "<p>1-year FFR: $lower1%</p>";
                echo "<p>3-year FFR: $lower3%</p>";
                echo "<p>5-year FFR: $lower5%</p>";
                echo "<p>10-year FFR: $lower10%</p>";

                echo "<div class='container result-container'>";
                // echo "<h2 style='color:#f78da7; text-align:center;'>Nomogram Score: $nomogram_score</h2>";
                echo "<canvas id='myChartlower' width='400' height='400'></canvas>";
                echo "</div>";
            
                // Chart.js script
                echo "<script src='https://cdn.jsdelivr.net/npm/chart.js'></script>";
                echo "<script>";
                echo "var ctx = document.getElementById('myChartlower').getContext('2d');";
                echo "var myChartlower = new Chart(ctx, {";
                echo "type: 'line',";
                echo "data: {";
                echo "labels: ['1-year FFR', '3-year FFR', '5-year FFR', '10-year FFR'],";
                echo "datasets: [{";
                echo "label: 'Lower Limit (%)',";
                echo "data: [$lower1, $lower3, $lower5, $lower10],";
                echo "backgroundColor: 'blue',";
                echo "borderColor: 'blue',";
                echo "borderWidth: 1";
                echo "}]}";
                echo "});";
                echo "</script>";



                ?>
            </div>
        </div>
    </div>


    
    <div class="container button-container">
        <a href="index.html">
            <button class="reset-button" style="background-color:#ff0019;">
                <h2>RESET</h2>
            </button>
        </a>
</div>
</body>

</html>
