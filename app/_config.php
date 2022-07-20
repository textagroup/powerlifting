<?php

use SilverStripe\Control\Director;
use SilverStripe\Security\PasswordValidator;
use SilverStripe\Security\Member;

// remove PasswordValidator for SilverStripe 5.0
$validator = PasswordValidator::create();
// Settings are registered via Injector configuration - see passwords.yml in framework
Member::set_password_validator($validator);
// Hack: set base url for Heroku so css files are not blocked
$url = getenv('SS_BASE_URL');

if (isset($url) && $url != '') {
    Director::config()->set('alternate_base_url', (rtrim($url, '/') . '/'));
}
