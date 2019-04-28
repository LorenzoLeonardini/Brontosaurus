<img align="left" src="http://brontosaurus.leonardini.dev/logo.svg" height="110px"><h1>&nbsp;Brontosaurus <a href="https://travis-ci.org/LorenzoLeonardini/Brontosaurus" target="blank"><img src="https://travis-ci.org/LorenzoLeonardini/Brontosaurus.svg?branch=master"></a></h1>
<br />

**Brontosaurus** is a security tool for your PHP website.

Current features include:

- Form tokens validation

## Table of Contents

- [Installation](#installation)
- [Getting Started](#getting-started)
- [Form Tokens](#form-tokens)

## Installation

The easiest way to install **Brontosaurus** is with Composer:

```
composer require leonardini/brontosaurus
```

If you prefer you can download the [latest release](https://github.com/LorenzoLeonardini/Brontosaurus/releases/latest) and manually add the files to your project. Keep in mind that this is discouraged as you won't be able to easily update the library.

> **WARNING**: Make sure to `require_once` _every_ file inside the `src` folder

## Getting Started

> **NOTICE**: this tutorial assumes that you've installed **Brontosaurus** using Composer, if you haven't you can still follow this, but some parts would be different

To be able to use **Brontosaurus** you have to `require_once` the `autoload.php` file inside Composer's vendor folder.

```php
require_once("vendor/autoload.php");
```

This is actually the only thing you need to do to get **Brontosaurus** and all its tools up and running. For an usage example see the next section about [Form Tokens](#form-tokens)

## Form Tokens

When your website has a form, you usually want to receive submissions only from your legit page and not from other sources, such as unauthorized third-parties services.

Keeping in mind that this problem cannot be completely solved, **Brontosaurus** has a nice tool to help you make your forms a little bit more secure.

This works by generating a hidden random token every time the form page is loaded. The token is than sent to the server together with the form data and checked if the same of the one saved in session. The user could have multiple browser tabs opened and to support that _the last 20 tokens_ are saved in session (I plan to make that number customizable).

The code you need on the form page is the following:

```php
// It is extremely important that a descriptive form name is provided as parameter, because tokens must be strictly linked to every form of your website
$token = \Brontosaurus\FormToken::generateToken("form_name");

// The token must be sent to the server in a 'form_token' parameter, for security only POST request are supported
echo "<input type=\"hidden\" name=\"form_token\" value=\"$token\">";
```

To check the validity of the token you will use:

```php
$validToken = \Brontosaurus\FormToken::validateToken("form_name");

if($validToken) {
    // The token comes from your form
} else {
    // The token has not passed the check
}
```
