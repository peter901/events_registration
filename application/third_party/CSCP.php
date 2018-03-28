<?php

define ('HMAC_SHA256', 'sha256');
define ('CSPP_KEY', '20ec8e28478547f584d3dc1d3dd9e2f62c5070802f6b4f7ab7968520e252edeb686b33b58dbb43e394dbf6ea9d1275f3bc1d588870c54d488818a48b6a472f56f62660ab9f0f4fb999143ed46bf1f6753d3f2b05928e43f38ec24e5393693264d03440c7f9514b7586447d35839351be5b918201da2246e4965abffdfd75ced6');

function sign ($params) {
  return signData(buildDataToSign($params), CSPP_KEY);
}

function signData($data, $secretKey) {
    return base64_encode(hash_hmac('sha256', $data, $secretKey, true));
}

function buildDataToSign($params) {
        $signedFieldNames = explode(",",$params["signed_field_names"]);
        foreach ($signedFieldNames as $field) {
           $dataToSign[] = $field . "=" . $params[$field];
        }
        return commaSeparate($dataToSign);
}

function commaSeparate ($dataToSign) {
    return implode(",",$dataToSign);
}

?>
