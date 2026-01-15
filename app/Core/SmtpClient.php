<?php

namespace App\Core;

class SmtpClient
{
    private $host;
    private $port;
    private $username;
    private $password;
    private $timeout = 30;
    private $socket;

    public function __construct()
    {
        $this->host = env('MAIL_HOST');
        $this->port = env('MAIL_PORT');
        $this->username = env('MAIL_USERNAME');
        $this->password = env('MAIL_PASSWORD');
    }

    public function send($to, $subject, $body, $fromEmail, $fromName)
    {
        try {
            $this->connect();
            $this->auth();

            $this->sendCommand("MAIL FROM: <$fromEmail>");
            $this->sendCommand("RCPT TO: <$to>");
            $this->sendCommand("DATA");

            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers .= "From: $fromName <$fromEmail>\r\n";
            $headers .= "To: $to\r\n";
            $headers .= "Subject: $subject\r\n";

            $content = $headers . "\r\n" . $body . "\r\n.";
            $this->sendCommand($content);

            $this->sendCommand("QUIT");
            fclose($this->socket);

            return true;
        } catch (Exception $e) {
            error_log("SMTP Error: " . $e->getMessage());
            return false;
        }
    }

    private function connect()
    {
        $this->socket = fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout);
        if (!$this->socket) {
            throw new Exception("Could not connect to SMTP host: $errstr ($errno)");
        }
        $this->getResponse();

        $this->sendCommand("EHLO " . gethostname());
    }

    private function auth()
    {
        if ($this->username && $this->password) {
            $this->sendCommand("AUTH LOGIN");
            $this->sendCommand(base64_encode($this->username));
            $this->sendCommand(base64_encode($this->password));
        }
    }

    private function sendCommand($cmd)
    {
        fputs($this->socket, $cmd . "\r\n");
        return $this->getResponse();
    }

    private function getResponse()
    {
        $response = "";
        while (($line = fgets($this->socket, 515)) !== false) {
            $response .= $line;
            if (substr($line, 3, 1) == " ")
                break;
        }
        return $response;
    }
}
