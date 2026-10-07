<?php
// Konfigurasi koneksi database - dibaca dari environment variable docker-compose
$DB_HOST = getenv('DB_HOST') ?: 'portal-db';
$DB_NAME = getenv('DB_NAME') ?: 'portal_akademik';
$DB_USER = getenv('DB_USER') ?: 'portal';
$DB_PASS = getenv('DB_PASS') ?: 'ChangeMePortal123!';

$conn = @new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

session_start();
