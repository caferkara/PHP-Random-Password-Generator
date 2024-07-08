<?php
if (isset($_POST['generate'])) {
    $length = isset($_POST['length']) ? intval($_POST['length']) : 8;
    $characters = '';
    if (isset($_POST['uppercase'])) {
        $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    }
    if (isset($_POST['lowercase'])) {
        $characters .= 'abcdefghijklmnopqrstuvwxyz';
    }
    if (isset($_POST['numbers'])) {
        $characters .= '0123456789';
    }
    if (isset($_POST['special'])) {
        $characters .= '#$&';
    }

    function generatePassword($length, $characters) {
        $charactersLength = strlen($characters);
        $randomPassword = '';
        for ($i = 0; $i < $length; $i++) {
            $randomPassword .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomPassword;
    }

    function passwordStrength($password) {
        $length = strlen($password);
        $hasUppercase = preg_match('/[A-Z]/', $password);
        $hasLowercase = preg_match('/[a-z]/', $password);
        $hasNumbers = preg_match('/[0-9]/', $password);
        $hasSpecial = preg_match('/[#\$&]/', $password);

        $strength = 0;
        if ($length >= 8) $strength++;
        if ($hasUppercase) $strength++;
        if ($hasLowercase) $strength++;
        if ($hasNumbers) $strength++;
        if ($hasSpecial) $strength++;

        switch ($strength) {
            case 5: return 'Very Strong';
            case 4: return 'Strong';
            case 3: return 'Good';
            case 2: return 'Weak';
            default: return 'Very Weak';
        }
    }

    $password = generatePassword($length, $characters);
    $strength = passwordStrength($password);
} else {
    $length = 8;
    $password = '';
    $strength = '';
}

function getStrengthColor($strength) {
    switch ($strength) {
        case 'Very Strong':
        case 'Strong': return 'green';
        case 'Good': return 'orange';
        case 'Weak':
        case 'Very Weak': return 'red';
        default: return 'darkred';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Random Password Generator</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Lato', sans-serif;
            background-color: #f4f2ef;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 600px;
        }
        .checkbox-group {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .result {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .slider-container {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
        }
        .strength {
            padding: 5px;
            border-radius: 5px;
            color: white;
            flex-shrink: 0;
        }
        .copy-btn, .generate-btn {
            background-color: #0984e3;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .copy-btn:hover, .generate-btn:hover {
            background-color: #0376ce;
        }
        .password-box {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
            max-width: 100%;
        }
        .password-input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            max-width: 300px;
            text-align: center;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Random Password Generator</h1>
        <form method="post" action="">
            <label for="length"><b>Password Length:</b></label><br><br>
            <div class="slider-container">
                <input type="range" id="length" name="length" min="1" max="50" value="<?php echo htmlspecialchars($length); ?>" oninput="this.nextElementSibling.value = this.value">
                <output><?php echo htmlspecialchars($length); ?></output>
            </div>
            <br>
            <label><b>Characters Used:</b></label><br><br>
            <div class="checkbox-group">
                <div>
                    <input type="checkbox" id="uppercase" name="uppercase" checked>
                    <label for="uppercase">ABC</label>
                </div>
                <div>
                    <input type="checkbox" id="lowercase" name="lowercase" checked>
                    <label for="lowercase">abc</label>
                </div>
                <div>
                    <input type="checkbox" id="numbers" name="numbers" checked>
                    <label for="numbers">123</label>
                </div>
                <div>
                    <input type="checkbox" id="special" name="special" checked>
                    <label for="special">#$&</label>
                </div>
            </div>
            <br><br>
            <button type="submit" name="generate" class="generate-btn">Generate</button><br><br>
        </form>

        <?php if (!empty($password)): ?>
            <div class="result">
                <h3>Generated Password:</h3>
                <div class="password-box">
                    <input type="text" class="password-input" id="generated-password" value="<?php echo htmlspecialchars($password); ?>" readonly>
                    <button class="copy-btn" onclick="copyPassword()">Copy</button>
                </div>
                <p class="strength" style="background-color: <?php echo getStrengthColor($strength); ?>;"><?php echo htmlspecialchars($strength); ?></p>
            </div>
        <?php endif; ?>
    </div>
    <script>
        function copyPassword() {
            var copyText = document.getElementById("generated-password");
            copyText.select();
            copyText.setSelectionRange(0, 99999); /* For mobile devices */
            document.execCommand("copy");
            alert("Copied the password: " + copyText.value);
        }
    </script>
</body>
</html>