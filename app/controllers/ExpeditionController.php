<?php
class ExpeditionController
{
    public function store()
    {
        // 1. Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 2. Collect POST data
        $email = strtolower(trim($_POST['email'] ?? ''));
        $name = ucfirst(strtolower(trim($_POST['name'] ?? '')));
        $lastname = ucfirst(strtolower(trim($_POST['lastname'] ?? '')));
        $phoneRaw = preg_replace('/\D/', '', $_POST['phone'] ?? '');
        $extention = trim($_POST['extention'] ?? '');
        $addressRaw = trim($_POST['address'] ?? '');
        $city = ucfirst(strtolower(trim($_POST['city'] ?? '')));
        $province = trim($_POST['province'] ?? '');
        $postcode = strtoupper(trim($_POST['postcode'] ?? ''));

        // 3. Format phone
        if (strlen($phoneRaw) === 10) {
            $phone = sprintf(
                "(%s) %s %s",
                substr($phoneRaw, 0, 3),
                substr($phoneRaw, 3, 3),
                substr($phoneRaw, 6, 4)
            );
        } else {
            $phone = $phoneRaw;
        }

        // 4. Format address (capitalize first letter after numbers)
        $address = preg_replace_callback('/([a-z])/', function ($matches) {
            static $first = true;
            if ($first) {
                $first = false;
                return strtoupper($matches[1]);
            }
            return $matches[1];
        }, strtolower($addressRaw));

        // 5. Store expedition data in session (not DB yet)
        $_SESSION['expedition_data'] = [
            'email' => $email,
            'name' => $name,
            'lastname' => $lastname,
            'phone' => $phone,
            'extention' => $extention,
            'address' => $address,
            'city' => $city,
            'province' => $province,
            'postcode' => $postcode
        ];

        // 6. Redirect to payment page
        header("Location: /eTransactionAPP/public/payment");
        exit;
    }
}
