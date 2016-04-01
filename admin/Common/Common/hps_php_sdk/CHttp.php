<?php
class CHttp
{
    public static function GetRequest($url, $protocol = 'http', $timeout = 3, $charset = 'utf-8')
    {
        $ch = curl_init();
        if (false == $ch) {
            return false;
        }

        if ($protocol === 'https') {
            $isHttps = true;
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        }
        $header = array(
            "MIME-Version: 1.0",
            "Content-type: text/html; charset=" . $charset,
            "Content-transfer-encoding: text"
        );

        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_CONNECTTIMEOUT => 2,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_RETURNTRANSFER => 1,
        );
        curl_setopt_array($ch, $options);

        $content = curl_exec($ch);
        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
            curl_close($ch);
            return false;
        }
        curl_close($ch);
        return $content;
    }

    public static function postContent($url, $data, $protocol = 'http', $timeout = 30)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($protocol == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        }
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($ch);

        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
            curl_close($ch);
            false;
        }

        curl_close($ch);
        return $content;
    }

    public static function constructGetUrl($baseurl, $arrkeyvalue = array())
    {
        $strUrl = $baseurl . '?';
        $isFirst = true;
        $strUri = self::constructGetUriAttrributes($arrkeyvalue);
        return ($strUrl . $strUri);
    }

    public static function constructGetUriAttrributes($arrkeyvalue = array())
    {
        $strUri = '';
        foreach ($arrkeyvalue as $key => $value) {
            if (is_array($value)) {
                foreach ($value as &$temp) {
                    if ($strUri == '')
                        $strUri = $strUri . $key . '=' . urlencode($temp);
                    else
                        $strUri = $strUri . '&' . $key . '=' . urlencode($temp);
                }
            } else {
                if ($strUri == '')
                    $strUri = $strUri . $key . '=' . urlencode($value);
                else
                    $strUri = $strUri . '&' . $key . '=' . urlencode($value);
            }
        }

        return $strUri;
    }
}