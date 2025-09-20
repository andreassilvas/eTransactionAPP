<?php
class ExpeditionController
{
    public function store()
    {
        // 1. Collect POST data
        $email = $_POST['email'] ?? '';
        $name = $_POST['name'] ?? '';
        $lastname = $_POST['lastname'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $extention = $_POST['extention'] ?? '';

        $address = $_POST['address'] ?? '';
        $city = $_POST['city'] ?? '';
        $province = $_POST['province'] ?? '';
        $postcode = $_POST['postcode'] ?? '';

        // 2. Check if client exists
        $clientModel = new Client();
        $client = $clientModel->findByEmailOrPhone($email, $phone);

        if (!$client) {
            // Create new client
            $clientId = $clientModel->create([
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
        } else {
            $clientId = $client['id'];
        }

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


        // 4. Redirect or render success view
        // header("Location: /expeditions/success?id=" . $expeditionId);
        header("Location: /eTransactionAPP/public/payment");
        exit;
    }
}
