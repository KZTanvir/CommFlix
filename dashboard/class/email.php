<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

class EmailSender {
    private $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
        $this->initializeMailer();
    }

    private function initializeMailer() {
        $this->mailer->isSMTP();	
        $this->mailer->SMTPAuth = true;	
        $this->mailer->Host = 'smtp.gmail.com';
        $this->mailer->Port = 587;
        $this->mailer->SMTPSecure = 'tls';
        $this->mailer->Username = 'Smtp account username here';
        $this->mailer->Password = 'Smtp account password here';
        $this->mailer->setFrom('Your email here', 'KZT Super Stream');
    }

    public function sendEmail($recipientEmail, $recipientName, $subject, $body) {
        $this->mailer->addAddress($recipientEmail, $recipientName);
        $this->mailer->Subject = $subject;
        $this->mailer->isHTML(true);
        $this->mailer->Body = $body;
        if (!$this->mailer->send()) {
            //echo 'Message could not be sent. Mailer Error: ' . $this->mailer->ErrorInfo;
            $this->mailer->smtpClose();
            return false;
        } else {
            //echo 'Message has been sent';
            $this->mailer->smtpClose();
            return true;
        }
    }
}
?>
