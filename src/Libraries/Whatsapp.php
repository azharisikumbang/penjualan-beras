<?php

class Whatsapp
{
    const BASE_URL = "https://graph.facebook.com";

    const VERSION = "v18.0";

    const PATH = "messages";

    public static function sendMessage(string $number, array $body, ?string $url = null): bool|string
    {
        $token = config('whatsapp', 'token');
        $sender = config('whatsapp', 'sender_number');
        $templateName = config('whatsapp', 'template');

        $template = [
            'name' => $templateName,
            'language' => "language-and-locale-code",
            'components' => [
                [
                    'type' => 'body',
                    'parameters' => $body
                ]
            ]
        ];

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $number,
            'type' => 'template',
            'template' => $template
        ];
        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ];

        $url = self::buildWhastappUrl($sender, self::PATH);
        $ch = \curl_init($url);

        \curl_setopt($ch, \CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($ch, \CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);

        $response = \curl_exec($ch);
        $error    = \curl_error($ch);
        $errno    = \curl_errno($ch);

        if (\is_resource($ch)) {
            \curl_close($ch);
        }

        if (0 !== $errno) {
            throw new \RuntimeException($error, $errno);
        }

        return $response;
    }

    public static function sentStaticMessage(string $number, string $body)
    {
        $token = config('whatsapp', 'token');
        $sender = config('whatsapp', 'sender_number');

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $number,
            'type' => 'text',
            'text' => [
                'preview_url' => false,
                'body' => $body
            ]
        ];
        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ];

        $url = self::buildWhastappUrl($sender, self::PATH);
        $ch = \curl_init($url);

        \curl_setopt($ch, \CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($ch, \CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);

        $response = \curl_exec($ch);
        $error    = \curl_error($ch);
        $errno    = \curl_errno($ch);

        if (\is_resource($ch)) {
            \curl_close($ch);
        }

        if (0 !== $errno) {
            throw new \RuntimeException($error, $errno);
        }

        return $response;
    }

    private static function buildWhastappUrl(string $sender, string $path)
    {
        // https://graph.facebook.com/v17.0/107920529067316/messages
        return sprintf("%s/%s/%s/%s", self::BASE_URL, self::VERSION, $sender, $path);
    }
}
