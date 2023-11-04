<?php
class Transaction
{
    private $balance;

    public function __construct()
    {
        $configSessionName = 'config_' . $_SESSION['username'];
        if (!isset($_SESSION[$configSessionName]['balance'])) {
            $_SESSION[$configSessionName]['balance'] = 0;
        }
        $this->balance = $_SESSION[$configSessionName]['balance'];
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function deposit($amount)
    {
        $configSessionName = 'config_' . $_SESSION['username'];

        if ($amount > 0) {
            $this->balance += $amount;
            $configSessionName = 'config_' . $_SESSION['username'];
            $_SESSION[$configSessionName]['balance'] = $this->balance;

            $transaction = [
                'date' => date('Y-m-d H:i:s'),
                'type' => 'Deposit',
                'debit' => $amount,
                'credit' => null,
                'balance' => $_SESSION[$configSessionName]['balance']
            ];
            $_SESSION[$configSessionName]['history'][] = $transaction;
        }
    }

    public function withdraw($amount)
    {
        $configSessionName = 'config_' . $_SESSION['username'];

        if ($amount > 0 && $amount <= $this->balance) {
            $this->balance -= $amount;
            $_SESSION[$configSessionName]['balance'] = $this->balance;

            $transaction = [
                'date' => date('Y-m-d H:i:s'),
                'type' => 'Withdraw',
                'debit' => null,
                'credit' => $amount,
                'balance' => $_SESSION[$configSessionName]['balance']
            ];
            $_SESSION[$configSessionName]['history'][] = $transaction;
        } else {
            return "Your balance is insufficient.";
        }
    }

    public function tranfers($amount, $recipient)
    {

        if ($amount > 0 && $amount <= $this->balance) {

            $configSender = 'config_' . $_SESSION['username'];
            $configRecipient = 'config_' . $recipient;


            $this->balance -= $amount;
            $_SESSION[$configSender]['balance'] = $this->balance;

            $_SESSION[$configRecipient]['balance'] += $amount;

            $senderTransaction = [
                'date' => date('Y-m-d H:i:s'),
                'type' => 'Transfer',
                'debit' => null,
                'credit' => $amount,
                'balance' => $_SESSION[$configSender]['balance'],
                'desc' => 'Transfer to ' . $recipient
            ];

            $recipientTransaction = [
                'date' => date('Y-m-d H:i:s'),
                'type' => 'Transfer',
                'debit' => $amount,
                'credit' => null,
                'balance' => $_SESSION[$configRecipient]['balance'],
                'desc' => 'Transfer from ' . $_SESSION['username']
            ];

            $_SESSION[$configSender]['history'][] = $senderTransaction;
            $_SESSION[$configRecipient]['history'][] = $recipientTransaction;
        } else {
            return "Your balance is insufficient.";
        }
    }
}
