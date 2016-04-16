<?php

namespace Service;

/**
 * Class MailService
 *
 * Provides methods dealing with emails.
 *
 * @package Service
 */
class MailService extends Service
{

    /**
     * Initializes PHPMailer with the settings from the config.
     *
     * @return \PHPMailer
     */
    private function mailInit() {
        $config = $this->container['config']['mail'];
        $mail = new \PHPMailer();
        $mail->isSMTP();
        $mail->Host = $config['smtp']['host'];
        $mail->SMTPAuth = $config['smtp']['authentication'];
        $mail->Username = $config['smtp']['username'];
        $mail->Password = $config['smtp']['password'];
        $mail->SMTPSecure = $config['smtp']['secure'];
        $mail->Port = $config['smtp']['port'];
        $mail->setFrom($config['from']['address'], $config['from']['name']);
        $mail->addReplyTo($config['replyTo']['address'], $config['replyTo']['name']);
        return $mail;
    }

    /**
     * Sends an email with plain text.
     *
     * @param $email   string recipient email address
     * @param $subject string subject of the email
     * @param $message string body of the email
     * @throws \Exception if sending the email does not succeed
     */
    public function emailPlainString($email, $subject, $message) {
        $mail = $this->mailInit();

        $mail->addAddress($email);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = $message;
        $mail->isHTML(false);

        try {
            if (!$mail->send()) {
                throw new \Exception("Message could not be send:" . $mail->ErrorInfo);
            }
        } catch (\phpmailerException $e) {
            throw new \Exception("Message could not be send:" . $e->getMessage());
        }
    }

    /**
     * Sends an email with plain text.
     *
     * @param $email   string recipient email address
     * @param $subject string subject of the email
     * @param $message string body of the email
     * @throws \Exception if sending the email does not succeed
     */
    public function emailHtml($email, $subject, $message) {
        $mail = $this->mailInit();

        $mail->addAddress($email);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = \Html2Text\Html2Text::convert($message);
        $mail->isHTML(true);

        try {
            if (!$mail->send()) {
                throw new \Exception(_("Message could not be send:") . $mail->ErrorInfo);
            }
        } catch (\phpmailerException $e) {
            throw new \Exception(_("Message could not be send:") . $e->getMessage());
        }
    }

}