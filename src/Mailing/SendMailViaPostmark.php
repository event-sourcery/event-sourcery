<?php namespace EventSourcery\Mailing;

class SendMailViaPostmark {

    private $postmarkServerToken;

    public function __construct($postmarkServerToken) {
        $this->postmarkServerToken = $postmarkServerToken;
    }

    // http://developer.postmarkapp.com/developer-send-api.html (send batch email)
    public function batch(array $batchedMail) {
        $batches = array_chunk($batchedMail, 500);

        foreach ($batches as $batch) {
            $this->post('https://api.postmarkapp.com/email/batch', array_map(function (Mail $mail) {
                return $mail->serialize();
            }, $batch));
        }
    }

    // http://developer.postmarkapp.com/developer-send-api.html (send a single email)
    public function single(Mail $mail) {
        return $this->post('https://api.postmarkapp.com/email', $mail->serialize());
    }

    private function post($url, array $fields) {
        $ch = curl_init();

        // request
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json',
            'X-Postmark-Server-Token: ' . $this->postmarkServerToken,
        ]);

        // payload
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // execute
        curl_exec($ch);
        curl_close($ch);
    }
}