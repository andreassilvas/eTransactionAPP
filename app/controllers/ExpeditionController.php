<?php
class ExpeditionController
{
    public function store()
    {
        // Session should already be started in init.php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 1. Collect POST data (normalize some)
        $email = strtolower(trim($_POST['email'] ?? ''));
        $name = ucfirst(strtolower(trim($_POST['name'] ?? '')));
        $lastname = ucfirst(strtolower(trim($_POST['lastname'] ?? '')));
        $phone = trim($_POST['phone'] ?? '');
        $extention = trim($_POST['extention'] ?? '');
        $rawAddress = trim($_POST['address'] ?? '');
        $city = ucfirst(strtolower(trim($_POST['city'] ?? '')));
        $province = trim($_POST['province'] ?? '');
        $postcode = strtoupper(trim($_POST['postcode'] ?? ''));

        // Format phone
        $rawPhone = preg_replace('/\D/', '', $_POST['phone'] ?? '');
        if (strlen($rawPhone) === 10) {
            $phone = sprintf(
                "(%s) %s %s",
                substr($rawPhone, 0, 3),
                substr($rawPhone, 3, 3),
                substr($rawPhone, 6, 4)
            );
        } else {
            $phone = $rawPhone; // keep as-is if not 10 digits
        }
        //Format Address
        // Make everything lowercase first
        $rawAddress = strtolower($rawAddress);

        // Capitalize first letter after numbers/spaces
        $address = preg_replace_callback('/([a-z])/', function ($matches) {
            static $first = true;
            if ($first) {
                $first = false;
                return strtoupper($matches[1]);
            }
            return $matches[1];
        }, $rawAddress);


        // 2. Find or create client
        $clientModel = new Client();
        $client = $clientModel->findByEmailOrPhone($email, $phone);

        $clientId = $client
            ? $client['id']
            : $clientModel->create([
                'name' => $name,
                'lastname' => $lastname,
                'phone' => $phone,
                'extention' => $extention,
                'email' => $email,
                'address' => $address,
                'city' => $city,
                'province' => $province,
                'postcode' => $postcode
            ]);

        // 3. Create expedition
        $expeditionModel = new Expedition();
        $expeditionId = $expeditionModel->create([
            'client_id' => $clientId,
            'ship_email' => $email,
            'ship_address' => $address,
            'ship_city' => $city,
            'ship_province' => $province,
            'ship_postcode' => $postcode,
            'status' => 'pending',
            'date' => date('Y-m-d')
        ]);

        // Store expedition ID in session
        $_SESSION['expedition_id'] = $expeditionId;

        // 4. Redirect to payment
        header("Location: /eTransactionAPP/public/payment");
        exit;
    }
}
