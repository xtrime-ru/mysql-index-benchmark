<?php

require "database.php";
require "logger.php";

$db = new DataBase();

$tableA = 'employes_a';
$tableB = 'employes_b';
Logger::log('Create tables ...');
$db->createTable($tableA, "INDEX gender_age (gender, age)");
$db->createTable($tableB, "INDEX age_gender (age, gender)");

Logger::log('Filling table A ...');
$db->fillTable($tableA, 2_000_000);
Logger::log('Copy rows from table A to B ...');
$db->copyTable($tableA, $tableB);

Logger::log('Start benchmark ...');

$query = "gender = 'male' AND age >= 20 AND age <= 30";
function benchmark(DataBase $db, string $table, string $query, int $iterations = 100): void
{
    $timer = -microtime(true);
    foreach (range(1, $iterations) as $_) {
        $db->count($table, $query);
    }
    $timer += microtime(true);
    $avgTime = round($timer / $iterations * 1000, 3);
    Logger::log("Table $table avg requests time: $avgTime ms");
}

Logger::log("$tableA explain: " . $db->explain($tableA, $query));
Logger::log("$tableB explain: " . $db->explain($tableB, $query));

benchmark($db, $tableA, $query, 100);
benchmark($db, $tableB, $query, 100);

Logger::log("$tableA count by query: " . $db->count($tableA, $query));
Logger::log("$tableB count by query: " . $db->count($tableB, $query));
