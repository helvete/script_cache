#!/usr/bin/php
<?php
class Morse {
    public const A = ".-";
    public const B = "-...";
    public const C = "-.-.";
    public const D = "-..";
    public const E = ".";
    public const F = "..-.";
    public const G = "--.";
    public const H = "....";
    public const I = "..";
    public const J = ".---";
    public const K = "-.-";
    public const L = ".-..";
    public const M = "--";
    public const N = "-.";
    public const O = "---";
    public const P = ".--.";
    public const Q = "--.-";
    public const R = ".-.";
    public const S = "...";
    public const T = "-";
    public const U = "..-";
    public const V = "...-";
    public const W = ".--";
    public const X = "-..-";
    public const Y = "-.--";
    public const Z = "--..";

    private static function con(string $in) {
        return constant("Morse::$in");
    }

    private static function parse(string $input) {
        $chars = str_split($input);
        if (!count($chars))
            throw new InvalidArgumentException("invalid input");
        $result = "";
        foreach ($chars as $code) {
            $result .= static::fromCode($code);
        }
        return $result;
    }

    private static function fromCode(string $code) {
        foreach (range('A', 'Z') as $letter) {
            if ($code == static::con($letter))
                return $letter;
        }
        throw new InvalidArgumentException(sprintf("unknown code: %s", $code));
    }

    private static function fromLetter(string $letter) {
        if (strlen($letter) != 1)
            throw new InvalidArgumentException("longer sequence than 1");
        $letter = strtoupper($letter);
        if (empty(static::con($letter)))
            throw new InvalidArgumentException(
                sprintf("unknown letter: %s", $letter)
            );
        return static::con($letter);
    }

    public static function encode(string $letters) {
        $sequence = str_split($letters);
        $encoded = "";
        foreach ($sequence as $letter) {
            if ($encoded != "")
                $encoded .= " ";
            $encoded .= static::fromLetter($letter);
        }
        return $encoded;
    }

    public static function decode(string $codeSequence) {
        return static::parse($codeSequence);
    }
}

if (!(count($argv) == 3)) {
    echo "Provide runtime params: encode|decode and message\n";
    exit(1);
}

$method = $argv[1];
$message = $argv[2];
if (!method_exists('Morse', $method)) {
    printf("Invalid operation: %s", $method);
    exit(1);
}
printf("%s\n", Morse::$method($message));
