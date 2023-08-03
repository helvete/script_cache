#!/bin/env php
<?php

/*
 * Transfer SMS history from Android 1.6 device to a modern one (Android 5+)
 *
 * 1. Install apk from './data/SMS Backup_v1.0.7_apkfab.com.apk'
 * 2. Optionally delete sms threads that are not necessary to migrate.
 * 3. Run export in text format
 * 4. Transfer the export file from the phone
 * 5. Move it to './input.txt'
 * 6. Run this script
 * 7. Put the contents of the result file (`./converted.xml`)
 *  into the envelope (./data/sms-20230802142801.xml) under <smses> block
 * 8. Transfer the final file to the target phone
 * 9. Install https://play.google.com/store/apps/details?id=com.riteshsahu.SMSBackupRestore
 * 10. Run restore and celebrate!
 */

class Msg
{
    const IN = '1';
    const OUT = '2';
    // 22 Oct 2019 17:28:48
    const DATE_IN_FORMAT = 'j M Y H:i:s';
    // Aug 2, 2023 4:53:49 PM
    const DATE_OUT_FORMAT = 'M j, Y h:i:s A';

    private string $skip;
    private int $order;
    private string $phone_number;
    private \DateTime $date_time_identifier;
    private string $direction;
    private string $message;

    public function __construct(array $matchResult) {
        [$col1, $col2, $col3, $col4, $col5] = $matchResult;
        $this->skip = $col1;
        $this->order = (int)$col2;
        $this->phone_number = $col3;
        $this->date_time_identifier = \DateTime::createFromFormat(
            self::DATE_IN_FORMAT,
            $col4,
        );
        switch ($col5) {
        case 'in':
            $this->direction = self::IN;
            break;
        case 'out':
            $this->direction = self::OUT;
            break;
        }
    }

    public function setBody(string $body) {
        $this->message = $body;
    }

    public function getPhoneNumber(): string {
        return $this->phone_number;
    }

    public function getDirection(): string {
        return $this->direction;
    }

    public function getBody(): string {
        return $this->message;
    }

    public function getDateTs(): string {
        return $this->date_time_identifier->format('U') * 1000;
    }

    public function getDatePrint(): string {
        return $this->date_time_identifier->format(self::DATE_OUT_FORMAT);
    }
}
/* input in format like below - each record has 3 lines: meta, body and empty line
11 +420736768882 22 Oct 2019 17:34:50 out
okay

*/

$handle = fopen("./input.txt", "r");

$record = null;
$lineBatch = [];
while ($line = fgets($handle)) {
    $line = trim($line);
    if ($line == '') {
        $record = null;
        continue;
    }
    $result = preg_match('/^([0-9]+) ([+]?[0-9]+) (.*) (in|out)$/', $line, $matches);
    if ($result) {
        $record = new Msg($matches);
        continue;
    }
    $record->setBody($line);
    $lineBatch[] = create_record($record);
}
fclose($handle);

file_put_contents('./converted.xml', implode("\n", $lineBatch));

function create_record(Msg $message): string {
    $template = '  <sms protocol="0" address="%s" date="%s" type="%s" '
        . 'subject="null" body="%s" toa="null" sc_toa="null" '
        . 'service_center="+420608005689" read="1" status="-1" locked="0" '
        . 'date_sent="%s" sub_id="null" readable_date="%s" contact_name="%s" />';
    return sprintf(
        $template,
        $message->getPhoneNumber(),
        $message->getDateTs(),
        $message->getDirection(),
        $message->getBody(),
        $message->getDateTs(),
        $message->getDatePrint(),
        nameForNumber($message->getPhoneNumber()),
    );
}

function nameForNumber($number) {
    $map = [
        // fill this from google contacts in format key=name value=phone_number
        // export, then process the CSV in your favourite editor to get it!
        'Jane Doe' => '+420777777777',
    ];

    if (in_array($number, $map)) {
        $flipped = array_flip($map);
        return $flipped[$number];
    }
    // fall back to phone number if name not found
    return $number;
}
