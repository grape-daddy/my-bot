<?php
function getProfile($userId, $arrayHeader){
    $strUrl = "https://api.line.me/v2/bot/profile/". $userId;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$strUrl);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $arrayHeader);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close ($ch);
    return $result;
 }

function accessToken($client_id, $client_secret) {
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.line.me/v2/oauth/accessToken",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "grant_type=client_credentials&client_id=".$client_id."&client_secret=" . $client_secret,
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/x-www-form-urlencoded"
    ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
    echo "cURL Error #:" . $err;
    } else {
    $data = json_decode($response);
    }

    return $data->access_token;
}

$client_id = '1563228754';
$client_secret = '94bd50a658cfc05dae429bcc2bec01c9';

$accessToken = accessToken($client_id, $client_secret);

$arrayHeader = array();
$arrayHeader[] = "Content-Type: application/json";
$arrayHeader[] = "Authorization: Bearer {$accessToken}";

$profile = json_decode(getProfile($_GET['code'], $arrayHeader));
?>
<div>
<?php echo $profile->displayName; ?>
</div>
<div>
<img src="<?php echo $profile->pictureUrl; ?>" width="180">
</div>
<?php
print_r($profile);
echo "<hr>";
echo $accessToken;
?>
