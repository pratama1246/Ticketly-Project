<?php

if (!function_exists('mask_email')) {
    /**
     * Samarkan email sesuai standar privasi UU PDP.
     * Contoh: "pratama@gmail.com" -> "p*****a@gmail.com"
     */
    function mask_email(?string $email): string
    {
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return (string) $email;
        }

        [$name, $domain] = explode('@', $email, 2);
        $len = strlen($name);

        if ($len <= 2) {
            $maskedName = substr($name, 0, 1) . '*';
        } else {
            $first = substr($name, 0, 1);
            $last  = substr($name, -1);
            $maskedName = $first . str_repeat('*', max(3, $len - 2)) . $last;
        }

        return $maskedName . '@' . $domain;
    }
}

if (!function_exists('mask_phone')) {
    /**
     * Samarkan nomor telepon.
     * Contoh: "081234567890" -> "0812****7890"
     */
    function mask_phone(?string $phone): string
    {
        if (empty($phone)) {
            return '';
        }

        $clean = preg_replace('/[^\d+]/', '', $phone);
        $len   = strlen($clean);

        if ($len <= 6) {
            return str_repeat('*', $len);
        }

        $prefix = substr($clean, 0, 4);
        $suffix = substr($clean, -4);
        $maskedLength = max(3, $len - 8);

        return $prefix . str_repeat('*', $maskedLength) . $suffix;
    }
}

if (!function_exists('mask_nik')) {
    /**
     * Samarkan NIK/KTP sesuai standar privasi UU PDP.
     * Contoh: "3201234567890001" -> "3201**********0001"
     */
    function mask_nik(?string $nik): string
    {
        if (empty($nik)) {
            return '';
        }

        $len = strlen($nik);
        if ($len <= 6) {
            return str_repeat('*', $len);
        }

        $prefix = substr($nik, 0, 4);
        $suffix = substr($nik, -4);
        $maskedCount = max(4, $len - 8);

        return $prefix . str_repeat('*', $maskedCount) . $suffix;
    }
}
