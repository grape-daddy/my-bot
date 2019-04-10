<?php
$client_id = '1563228754';
$client_secret = '94bd50a658cfc05dae429bcc2bec01c9';

function pushMsg($arrayHeader,$arrayPostData){
    $strUrl = "https://api.line.me/v2/bot/message/push";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$strUrl);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $arrayHeader);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrayPostData));
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

if ($_POST['type'] == "push") {
    //$accessToken = 'RaZjxS9f4bo3lkA1YDyddtjyWZMPlEBw6GewIKOmVyWgfr2WDGQVU35LlFtnZyTsE8A0qSdtGaFStdQJHauat42zT5o1K+Fz9BVNDOESZLfO9fi3gOHXZG46NAf4yW0BsVc8As60NfNrpI9YpqIU1QdB04t89/1O/w1cDnyilFU=';
    $accessToken = accessToken($client_id, $client_secret);
    $content = file_get_contents('php://input');
    $arrayJson = json_decode($content, true);
    
    $arrayHeader = array();
    $arrayHeader[] = "Content-Type: application/json";
    $arrayHeader[] = "Authorization: Bearer {$accessToken}";

    $arrayPostData['to'] = $_POST['to'];
    $arrayPostData['messages'][0]['type'] = "text";
    $arrayPostData['messages'][0]['text'] = $_POST['text'];
    include("header.php");
    ?>
    <div class="container">
        <?php print_r(json_decode(pushMsg($arrayHeader,$arrayPostData))); ?>
        <p>Press back for return</p>
    </div>
    <?php
    include("footer.php");
    die();
}
    include("header.php");
    ?>
<div class="container">
    <form class="form-horizontal" method="post">
        <input type="hidden" name="type" value="push">
        <?php
        $lines = file('files/registered.txt');
        ?>
        <div>
            <select class="form-control" name="to">
            <?php
            foreach($lines as $line)
            {
                $ln = explode("|", $line);
                if ($ln[0] != "Group ID" && $ln[0] != "\n") {
                    ?>
                    <option value="<?php echo $ln[0]; ?>"><?php echo $ln[2]; ?></option>
                    <?php
                }
            }
            ?>
            </select>
        </div>
        <div>
            <input class="form-control" type="text" name="text" placeholder="Message">
        </div>
        <div>
            <button type="submit" class="btn btn-info">Send</button>
        </div>
    </form>
    <div class="text-center">Scan me for notify</div>
    <img src="http://qr-official.line.me/M/AJcskfL4Yw.png" class="img-responsive center-block" />
    <a href="https://line.me/R/ti/p/%40bcx1207d"><img height="36" border="0" alt="เพิ่มเพื่อน" src="https://scdn.line-apps.com/n/line_add_friends/btn/en.png" class="img-responsive center-block" /></a>
    <br><br>
</div>

<?php
include("footer.php");
?>
