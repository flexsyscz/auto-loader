<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Resources/TestClass.php';
require __DIR__ . '/Resources/Controls/WelcomeControl.php';
require __DIR__ . '/Resources/Forms/TestFormFactory.php';

Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');
