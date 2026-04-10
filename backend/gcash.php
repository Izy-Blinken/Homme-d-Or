<?php
function createPayMongoCheckout($amount_in_pesos, $description, $success_url, $cancel_url) {
    $secret_key = 'sk_test_orPszWHfvEiD6SXS1xwVUCjj';
    $amount_in_centavos = intval($amount_in_pesos * 100);

    $payload = json_encode([
        'data' => [
            'attributes' => [
                'amount'               => $amount_in_centavos,
                'currency'             => 'PHP',
                'capture_type'         => 'automatic',
                'description'          => $description,
                'statement_descriptor' => 'Homme dOr',
                'payment_method_types' => ['gcash'],
                'line_items'           => [
                    [
                        'currency' => 'PHP',
                        'amount'   => $amount_in_centavos,
                        'name'     => $description,
                        'quantity' => 1,
                    ]
                ],
                'success_url' => $success_url,
                'cancel_url'  => $cancel_url,
            ]
        ]
    ]);

    $ch = curl_init('https://api.paymongo.com/v1/checkout_sessions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Basic ' . base64_encode($secret_key . ':')
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // TEMP DEBUG
    file_put_contents(sys_get_temp_dir() . '/paymongo_debug.txt',
        "HTTP: $http_code\nResponse: $response"
    );

    $data = json_decode($response, true);
    return $data['data']['attributes']['checkout_url'] ?? null;
}
?>