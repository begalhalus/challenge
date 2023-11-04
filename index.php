<?php
session_start();

// set class
include('./Classes/Transaction.php');

$account = new Transaction();

// set session by username
$username = $_SESSION['username'];
if (!isset($_SESSION['username'])) {
    header('Location: auth');
    exit();
}

$recipient = ($username == 'vira') ? 'feon' : 'vira';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['deposit'])) {
        $depositAmount = floatval($_POST['deposit-amount']);
        $account->deposit($depositAmount);
    } elseif (isset($_POST['withdraw'])) {
        $withdrawAmount = floatval($_POST['withdraw-amount']);
        $errorMessage = $account->withdraw($withdrawAmount);
    } elseif (isset($_POST['tranfers'])) {
        $tranfersAmount = floatval($_POST['tranfers-amount']);
        $recipientUsername = $recipient;
        $errorMessage = $account->tranfers($tranfersAmount, $recipientUsername);
    }
}

if (isset($_POST['logout'])) {
    header('Location: auth.php');
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <link href="./main.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
</head>

<body>

    <div class="button-container">
        <button class="button" id="withdraw">Withdraw</button>
        <button class="button" id="deposit">Deposit</button>
        <button class="button" id="checks">Check Balance</button>
        <button class="button" id="tranfers">Tranfer</button>
        <button class="button" id="logout">Logout</button>
    </div>
    <div id="error-message"><?php if (isset($errorMessage)) echo $errorMessage; ?></div>

    <form id="deposit-form" class="form-depo deposit-form" action="index.php" method="post">
        <input type="number" name="deposit-amount" placeholder="Enter deposit amount">
        <button class="button" type="submit" name="deposit">Deposit</button>
    </form>

    <form id="withdraw-form" class="form-withdraw withdraw-form" action="index.php" method="post">
        <input type="number" name="withdraw-amount" placeholder="Enter withdraw amount">
        <button class="button" type="submit" name="withdraw">Withdraw</button>
    </form>

    <form id="checks-form" class="form-checks checks-form" action="index.php" method="post">
        <input class="input" type="number" id="transaction-amount" placeholder="Enter amount" readonly value="<?php echo $account->getBalance(); ?>">
    </form>

    <form id="tranfers-form" class="form-tranfer tranfers-form" action="index.php" method="post">
        <input type="number" name="tranfers-amount" placeholder="Enter tranfer amount">
        <button class="button" type="submit" name="tranfers">tranfer</button>
    </form>
    <br>

    <table border="1">
        <thead>
            <tr>
                <th>Time</th>
                <th>Type</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody id="transaction-table">
            <?php
            $configSessionName = 'config_' . $_SESSION['username'];
            if (isset($_SESSION[$configSessionName]['history']) && is_array($_SESSION[$configSessionName]['history'])) {
                foreach ($_SESSION[$configSessionName]['history'] as $transaction) {
                    echo '<tr>';
                    echo '<td>' . $transaction['date'] . '</td>';
                    echo '<td>' . $transaction['type'] . '</td>';
                    echo '<td>' . $transaction['debit'] . '</td>';
                    echo '<td>' . $transaction['credit'] . '</td>';
                    echo '<td>' . $transaction['balance'] . '</td>';
                    echo '<td>' . $transaction['desc'] . '</td>';
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
    </table>
</body>

</html>
<script>
    function toggleForm(formId) {
        const forms = ["deposit-form", "withdraw-form", "checks-form", "tranfers-form"];

        forms.forEach((form) => {
            document.getElementById(form).style.display = form === formId ? "flex" : "none";
        });
    }

    ["deposit", "withdraw", "checks", "tranfers"].forEach((buttonId) => {
        document.getElementById(buttonId).addEventListener("click", function() {
            toggleForm(buttonId + "-form");
        });
    });

    var logoutButton = document.getElementById("logout");

    logoutButton.addEventListener("click", function() {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "index.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("logout=1");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                window.location.href = "auth";
            }
        };
    });
</script>