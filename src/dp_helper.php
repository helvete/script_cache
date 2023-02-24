#!/usr/bin/php
<?php

/*
    Kalkulacka pro danove priznani zamestnani + OSVC, dary, uroky
*/

// suma urhnu (radek 1) z Potvrzení o zdanitelných příjmech
$uhrnPrijmuZam = 1000000;
// dilci zaklad dane (radek 14) z Prilohy 1
$dilciZakladOsvc = 400000;
$hodnotaDaru = 12000;
$vyseUrokuHypo = 150000;
// konstanta 2023
$slevaNaPopl = 30840;
// suma uhrnu zaloh na dan + srazene dane (radek 8 resp. 3) z Potvrzení o zdanitelných příjmech (standard resp. DPP)
$uhrnSrazenychZaloh = 119160;

function func() {
    [$v1, $v2, $v3, $v4, $v5, $v6] = func_get_args();
    # 1
    $_[31] = $v1;
    $_[34] = $_[31];
    # 2
    $_[36] = $_[34];
    $_[37] = $v2;
    $_[41] = $_[37];
    $_[42] = $_[36] + $_[41];
    $_[45] = $_[42];
    # 3
    $_[46] = $v3;
    $_[47] = $v4;
    # -
    $_[54] = $_[46] + $_[47];
    $_[55] = $_[45] - $_[54];
    $_[56] = (int)floor($_[55] / 100) * 100; // always int
    $_[57] = (int)($_[56] * 0.15); // always int
    # 4
    $_[58] = $_[57];
    $_[60] = $_[58];
    # 5
    $_[64] = $v5;
    $_[70] = $_[64];
    $_[71] = $_[60] - $_[70];
    $_[74] = $_[71];
    $_[75] = $_[74];
    $_[77] = $_[75];
    # 7
    $_[84] = $v6;
    $_[91] = $_[77] - $_[84];

    return $_;
}

$r1 = func($uhrnPrijmuZam, $dilciZakladOsvc, $hodnotaDaru, $vyseUrokuHypo, $slevaNaPopl, $uhrnSrazenychZaloh);
//$r2 = func($uhrnPrijmuZam+3300, $dilciZakladOsvc, $hodnotaDaru, $vyseUrokuHypo, $slevaNaPopl, $uhrnSrazenychZaloh+494);

echo (end($r1) > 0 ? 'Nedoplatek' : 'Preplatek') . ': ' . end($r1) . "\n\n";
//echo end($r2) . "\n\n";

echo "Chlivky v danovem priznani:\n\n";
var_dump($r1);
