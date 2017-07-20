<?php

    function seg($sentence)
    {
        error_reporting(E_ALL);
        $service_port = 2001;
        $address = '140.116.245.151';

        /* Create a TCP/IP socket. */
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket === false)
            echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
        $result = socket_connect($socket, $address, $service_port);
        if ($result === false)
            echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";

        $data = "seg@@".$sentence;
        socket_write($socket, $data, strlen($data));

        $resp = '';
        while ($out = socket_read($socket, 2048)) {
            $resp = $resp.$out;
        }

        socket_close($socket);
        
        $resp = nf_to_wf($resp,0);

        // 中文字編碼範圍
        $count = preg_match_all("/(([^\s])+(\-)*([\x{4e00}-\x{9fa5}0-9a-zA-Z])*)\((.*?)\)/u", $resp, $result_array);
        $value = [];

        for($i = 0; $i< $count; $i++){
            $value[$i]['word'] = $result_array[1][$i];
            $value[$i]['pos'] = $result_array[5][$i];
        }

        return $value;
    }

    function nf_to_wf($strs, $types){  //全形半形轉換
        $nft = array(
            "(", ")", "[", "]", "{", "}", ".", ",", ";", ":",
            "-", "?", "!", "@", "#", "$", "%", "&", "|", "\\",
            "/", "+", "=", "*", "~", "`", "'", "\"", "<", ">",
            "^", "_",
            "0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j",
            "k", "l", "m", "n", "o", "p", "q", "r", "s", "t",
            "u", "v", "w", "x", "y", "z",
            "A", "B", "C", "D", "E", "F", "G", "H", "I", "J",
            "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T",
            "U", "V", "W", "X", "Y", "Z",
            " "
        );
        $wft = array(
            "（", "）", "〔", "〕", "｛", "｝", "﹒", "，", "；", "：",
            "－", "？", "！", "＠", "＃", "＄", "％", "＆", "｜", "＼",
            "／", "＋", "＝", "＊", "～", "、", "、", "＂", "＜", "＞",
            "︿", "＿",
            "０", "１", "２", "３", "４", "５", "６", "７", "８", "９",
            "ａ", "ｂ", "ｃ", "ｄ", "ｅ", "ｆ", "ｇ", "ｈ", "ｉ", "ｊ",
            "ｋ", "ｌ", "ｍ", "ｎ", "ｏ", "ｐ", "ｑ", "ｒ", "ｓ", "ｔ",
            "ｕ", "ｖ", "ｗ", "ｘ", "ｙ", "ｚ",
            "Ａ", "Ｂ", "Ｃ", "Ｄ", "Ｅ", "Ｆ", "Ｇ", "Ｈ", "Ｉ", "Ｊ",
            "Ｋ", "Ｌ", "Ｍ", "Ｎ", "Ｏ", "Ｐ", "Ｑ", "Ｒ", "Ｓ", "Ｔ",
            "Ｕ", "Ｖ", "Ｗ", "Ｘ", "Ｙ", "Ｚ",
            "　"
        );
 
        if ($types == '1'){
            // 轉全形
            $strtmp = str_replace($nft, $wft, $strs);
        }else{
            // 轉半形
            $strtmp = str_replace($wft, $nft, $strs);
        }
        return $strtmp;
    }

?>  