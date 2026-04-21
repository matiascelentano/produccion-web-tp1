<?php
interface Autenticable {
    public static function login(string $email, string $password);
}