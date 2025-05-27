<?php

/**
 * Write code for convert currency in words
 *
 * @return response()
 */
function convertCurrencyWords($amount)
{
    $no = floor($amount);
    $point = round(($amount - $no) * 100);

    $hundred = null;
    $digits_1 = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten',
                'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen',
                'seventeen', 'eighteen', 'nineteen', 'twenty'];
    $digits_2 = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
    $digits = ['', 'hundred', 'thousand', 'lakh', 'crore'];
    $str = [];

    $i = 0;
    while ($no > 0) {
        $divider = ($i == 2) ? 10 : 100;
        $number = $no % $divider;
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;

        if ($number) {
            $plural = ($counter = count($str)) && $number > 9 ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' And ' : null;
            $str[] = ($number < 21)
                ? $digits_1[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred
                : $digits_2[floor($number / 10)] . ' ' . $digits_1[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
        } else {
            $str[] = null;
        }
    }

    $result = implode('', array_reverse($str));
    $result = ucwords(strtolower($result)) . 'Rupees';

    if ($point) {
        if ($point < 21) {
            $paiseWords = $digits_1[$point] ?? '';
        } else {
            $paiseWords = $digits_2[floor($point / 10)] . ' ' . $digits_1[$point % 10];
        }
        $result .= ' And ' . ucwords($paiseWords) . ' Paise';
    }

    return trim($result) . ' INR only';
}