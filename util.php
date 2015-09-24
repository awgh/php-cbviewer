<?php
function minimime($fname) {
    $fh=fopen($fname,'rb');
    if ($fh) {
        $bytes6=fread($fh,6);
        fclose($fh);
        if ($bytes6===false) return false;
        if (substr($bytes6,0,3)=="\xff\xd8\xff") return 'image/jpeg';
        if ($bytes6=="\x89PNG\x0d\x0a") return 'image/png';
        if ($bytes6=="GIF87a" || $bytes6=="GIF89a") return 'image/gif';

        // Additions
        if (substr($bytes6,0,4) == "%PDF")
            return 'application/pdf';
        if (substr($bytes6,0,2) == "PK")
            return 'application/zip';
        if ($bytes6=="Rar!\x1A\x07")
            return 'application/x-rar-compressed';
        return 'application/octet-stream';
    }
    return false;
}

function cmp_rar_obj($a, $b) {
    $a1 = $a->getName();
    $b1 = $b->getName();
    return strcmp($a1,$b1);
}

// this only works for IPv4, from stackoverflow user Alnitak
function cidr_match($ip, $range)
{
    list ($subnet, $bits) = explode('/', $range);
    $ip = ip2long($ip);
    $subnet = ip2long($subnet);
    $mask = -1 << (32 - $bits);
    $subnet &= $mask; # nb: in case the supplied subnet wasn't correctly aligned
    return ($ip & $mask) == $subnet;
}
?>