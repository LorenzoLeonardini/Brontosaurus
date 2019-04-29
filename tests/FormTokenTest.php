<?php

use PHPUnit\Framework\TestCase;

session_start();

require_once(__DIR__.'/../src/FormToken.php');

class FormTokenTest extends TestCase {

    public function testNoName() {
        $_POST['form_token'] = "random";
        $validation = \Brontosaurus\FormToken\validateToken("testForm");
        $this->assertSame(false, $validation->isSuccessfull());
        $this->assertSame(\Brontosaurus\FormToken\ValidationCode::NO_FORM_NAME, $validation->getCode());
    }

    public function testNoToken() {
        $_POST['form_name'] = "testForm";
        $validation = \Brontosaurus\FormToken\validateToken("testForm");
        $this->assertSame(false, $validation->isSuccessfull());
        $this->assertSame(\Brontosaurus\FormToken\ValidationCode::NO_FORM_TOKEN, $validation->getCode());
    }

    public function testWorking() {
        $_POST['form_token'] = \Brontosaurus\FormToken\generateToken("testForm");
        $_POST['form_name'] = "testForm";
        $validation = \Brontosaurus\FormToken\validateToken("testForm");
        $this->assertSame(true, $validation->isSuccessfull());

        $_POST['form_name'] = "random";
        $validation = \Brontosaurus\FormToken\validateToken("testForm");
        $this->assertSame(false, $validation->isSuccessfull());
        $this->assertSame(\Brontosaurus\FormToken\ValidationCode::WRONG_FORM_NAME, $validation->getCode());

        $_POST['form_name'] = "testForm";
        $_POST['form_token'] = "random";
        $validation = \Brontosaurus\FormToken\validateToken("testForm");
        $this->assertSame(false, $validation->isSuccessfull());
        $this->assertSame(\Brontosaurus\FormToken\ValidationCode::INVALID_TOKEN, $validation->getCode());
    }

    public function testMoreThanOneToken() {
        $_POST['form_name'] = "testForm";
        for($i = 0; $i < 20; $i++) {
            $_POST['form_token'] = \Brontosaurus\FormToken\generateToken("testForm");
        }
        $validation = \Brontosaurus\FormToken\validateToken("testForm");
        $this->assertSame(true, $validation->isSuccessfull());
    }

    public function testMaximumTwentyTokensPart1() {
        $_POST['form_name'] = "testForm";
        $_POST['form_token'] = \Brontosaurus\FormToken\generateToken("testForm");
        for($i = 0; $i < 21; $i++) {
            \Brontosaurus\FormToken\generateToken("testForm");
        }
        $validation = \Brontosaurus\FormToken\validateToken("testForm");
        $this->assertSame(false, $validation->isSuccessfull());
        $this->assertSame(\Brontosaurus\FormToken\ValidationCode::INVALID_TOKEN, $validation->getCode());
    }

    public function testMaximumTwentyTokensPart2() {
        $_POST['form_name'] = "testForm";
        for($i = 0; $i < 22; $i++) {
            $_POST['form_token'] = \Brontosaurus\FormToken\generateToken("testForm");
        }
        $validation = \Brontosaurus\FormToken\validateToken("testForm");
        $this->assertSame(true, $validation->isSuccessfull());
    }

    public function testMaximumTwentyTokensPart3() {
        $_POST['form_name'] = "testForm";
        for($i = 0; $i < 50; $i++) {
            \Brontosaurus\FormToken\generateToken("testForm");
        }
        $this->assertSame(20, count($_SESSION['form_tokens_testForm']));
    }

    public function testIllegalValidationCodePart1() {
        $this->expectException(InvalidArgumentException::class);
        new \Brontosaurus\FormToken\Validation(false, -1);
    }

    public function testIllegalValidationCodePart2() {
        $this->expectException(InvalidArgumentException::class);
        new \Brontosaurus\FormToken\Validation(false, 5);
    }

    public function testIllegalValidationCodePart3() {
        $this->expectException(InvalidArgumentException::class);
        new \Brontosaurus\FormToken\Validation(false, 0);
    }

    public function testIllegalValidationCodePart4() {
        $this->expectException(InvalidArgumentException::class);
        new \Brontosaurus\FormToken\Validation(true, 2);
    }

}
