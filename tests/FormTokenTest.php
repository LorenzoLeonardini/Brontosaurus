<?php

use PHPUnit\Framework\TestCase;

session_start();

require_once(__DIR__.'/../src/FormToken.php');

class FormTokenTest extends TestCase {

    public function testRaiseException() {
        $this->expectException(\Brontosaurus\FormTokenException::class);
        \Brontosaurus\FormToken::validateToken("testForm");
    }

    public function testWorking() {
        $_POST['form_token'] = \Brontosaurus\FormToken::generateToken("testForm");
        $this->assertSame(true, \Brontosaurus\FormToken::validateToken("testForm"));

        $_POST['form_token'] = "random";
        $this->assertSame(false, \Brontosaurus\FormToken::validateToken("testForm"));
    }

    public function testMoreThanOneToken() {
        for($i = 0; $i < 20; $i++) {
            $_POST['form_token'] = \Brontosaurus\FormToken::generateToken("testForm");
        }
        $this->assertSame(true, \Brontosaurus\FormToken::validateToken("testForm"));
    }

    public function testMaximumTwentyTokensPart1() {
        $_POST['form_token'] = \Brontosaurus\FormToken::generateToken("testForm");
        for($i = 0; $i < 21; $i++) {
            \Brontosaurus\FormToken::generateToken("testForm");
        }
        $this->assertSame(false, \Brontosaurus\FormToken::validateToken("testForm"));
    }

    public function testMaximumTwentyTokensPart2() {
        for($i = 0; $i < 22; $i++) {
            $_POST['form_token'] = \Brontosaurus\FormToken::generateToken("testForm");
        }
        $this->assertSame(true, \Brontosaurus\FormToken::validateToken("testForm"));
    }

    public function testMaximumTwentyTokensPart3() {
        for($i = 0; $i < 50; $i++) {
            \Brontosaurus\FormToken::generateToken("testForm");
        }
        $this->assertSame(20, count($_SESSION['form_tokens_testForm']));
    }

}
