<?php

$lastTimeString = "2022-08-26";
$cooldownDays = 75;
$preferredDay = "friday";
$last = \DateTime::createFromFormat("Y-m-d|", $lastTimeString);
$next = (clone $last)->add(new \DateInterval("P{$cooldownDays}D"));
$next->modify("next {$preferredDay}");
$orderDate = (clone $next)->modify('monday last week');

printf(
    "Next blood donation scheduled for friday %s, "
        . "please place an order beforehand, preferrably on monday %s",
    $next->format('Y-m-d'),
    $orderDate->format('Y-m-d'),
);
