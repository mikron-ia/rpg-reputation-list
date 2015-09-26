<?php

/**
 * Data file for Eclipse Phase RPG system
 * http://eclipsephase.com/
 * Data used in accord with http://creativecommons.org/licenses/by-nc-sa/3.0/us/
 */

$app['config.system.reputations'] = [
    "@" => [
        "name" => "@-list",
        "description" => "Autonomists: anarchists, Barsoomians, Extropians, Titanian, scum",
    ],
    "c" => [
        "name" => "CivicNet",
        "description" => "Hypercorps, Jovians, Lunars, Martians, Venusians",
    ],
    "e" => [
        "name" => "EcoWave",
        "description" => "nano-ecologists, preservationists, reclaimers",
    ],
    "f" => [
        "name" => "Fame",
        "description" => "Media: socialities, celebrities, glitterati",
    ],
    "g" => [
        "name" => "Guanxi",
        "description" => "Criminals",
    ],
    "r" => [
        "name" => "Research Network Associates",
        "description" => "Scientists: Argonauts, researchers, hypertechnologists",
    ],
    "x" => [
        "name" => "ExploreNet",
        "description" => "Gatecrashers",
    ],
    "m" => [
        "name" => "MilNet",
        "description" => "Mercenaries",
    ],
    "i" => [
        "name" => "The Eye",
        "description" => "Firewall",
        "restricted" => true,
    ],
    "u" => [
        "name" => "UltiNet",
        "description" => "Ultimates",
        "restricted" => true,
    ]
];