<?php

namespace PHPMailer\PHPMailer;

use Exception;

class PHPMailer
{
    public $isSMTP = false;
    public $Host;
    public $SMTPAuth;
    public $Username;
    public $Password;
    public $SMTPSecure;
    public $Port;
    public $From;
    public $FromName;
    public $to = [];
    public $Subject;
    public $Body;
    public $AltBody;
    public $isHTML = false;

    public function isSMTP()
    {
        $this->isSMTP = true;
    }

    public function setFrom($email, $name = '')
    {
        $this->From = $email;
        $this->FromName = $name;
    }

    public function addAddress($email)
    {
        $this->to[] = $email;
    }

    public function isHTML($bool)
    {
        $this->isHTML = $bool;
    }

    public function send()
    {
        $headers = "From: {$this->FromName} <{$this->From}>\r\n";
        if ($this->isHTML) {
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        }

        $success = true;
        foreach ($this->to as $recipient) {
            if (!mail($recipient, $this->Subject, $this->Body, $headers)) {
                $success = false;
            }
        }

        if (!$success) {
            throw new Exception("Failed to send email(s).");
        }

        return true;
    }
}
