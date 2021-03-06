<?php
    use PHPUnit\Framework\TestCase;
    use Khalyomede\ConsoleCommand;

    class TestCommand1 extends ConsoleCommand {
        protected $arguments = null;
    }

    class TestCommand2 extends ConsoleCommand {
        protected $arguments = [
            ['names' => 'path']
        ];
    }

    class TestCommand3 extends ConsoleCommand {
        protected $arguments = [
            ['name' => 42]
        ];
    }

    class TestCommand4 extends ConsoleCommand {
        protected $arguments = [
            ['name' => 'path', 'description' => 42]
        ];
    }

    class TestCommand5 extends ConsoleCommand {
        protected $options = null;
    }

    class TestCommand6 extends ConsoleCommand {
        protected $options = [
            ['names' => 'foo']
        ];
    }

    class TestCommand7 extends ConsoleCommand {
        protected $options = [
            ['name' => 42]
        ];
    }

    class TestCommand8 extends ConsoleCommand {
        protected $options = [
            ['name' => 'foo', 'shortName' => 42]
        ];
    }

    class TestCommand9 extends ConsoleCommand {
        protected $options = [
            ['name' => 'foo', 'shortName' => 'f', 'description' => 42]
        ];
    }

    class TestCommand10 extends ConsoleCommand {
        protected $flags = null;
    }

    class TestCommand11 extends ConsoleCommand {
        protected $flags = [
            ['names' => 42]
        ];
    }

    class TestCommand12 extends ConsoleCommand {
        protected $flags = [
            ['name' => 42]
        ];
    }

    class TestCommand13 extends ConsoleCommand {
        protected $flags = [
            ['name' => "foo", 'shortName' => 42]
        ];
    }

    class TestCommand14 extends ConsoleCommand {
        protected $flags = [
            ['name' => "foo", 'shortName' => 'f', 'description' => 42]
        ];
    }

    class TestCommand15 extends ConsoleCommand {
        protected $flags = [
            ['name' => 'help']
        ];
    }

    class TestCommand16 extends ConsoleCommand {
        protected $flags = [
            ['name' => 'hoo', 'shortName' => 'h']
        ];
    }

    class ConsoleCommandTest extends TestCase {
        public function testShouldThrowAnExceptionIfArgumentPropertyIsNullinsteadOfAnArray() {
            $this->expectException(InvalidArgumentException::class);

            TestCommand1::run();
        }

        public function testShouldThrowAnExceptionMessageIfArgumentPropertyIsNullInsteadOfAnArray() {
            $this->expectExceptionMessage("'argument' property should be an array (NULL given)");

            TestCommand1::run();
        }

        /**
         * @todo repeat 2 above for every types possibles
         */

        public function testShouldThrowAnExceptionIfArgumentKeyNameDoesNotExists() {
            $this->expectException(InvalidArgumentException::class);

            TestCommand2::run();
        }

        public function testShouldThrowAnExceptionMessageIfArgumentKeyNameDoesNotExists() {
            $this->expectExceptionMessage("argument #1 should have a key 'name'");

            TestCommand2::run();
        }

        public function testShouldThrowAnExceptionIfArgumentKeyNameIsAnIntegerAndNotAString() {
            $this->expectException(InvalidArgumentException::class);

            TestCommand3::run();
        }

        public function testShouldThrowAnExceptionMessageIfArgumentKeyNameIsAnIntegerAndNotAString() {
            $this->expectExceptionMessage("argument #1 should have a string in the key 'name' (integer given)");

            TestCommand3::run();
        }

        /**
         * @todo repeat 2 above for every types possibles
         */

        public function testShouldThrowAnExceptionIfArgumentKeyDescriptionIsAnIntegerInsteadOfAString() {
            $this->expectException(InvalidArgumentException::class);

            TestCommand4::run();
        }

        public function testShouldThrowAnExceptionMessageIfArgumentKeyDescriptionIsAnIntegerInsteadOfAString() {
            $this->expectExceptionMessage("argument #1 should have a string in the key 'description' (integer given)");

            TestCommand4::run();
        }

        /**
         * @todo repeat 2 above for every types possibles
         */
        public function testShouldThrowAnExceptionIfOptionsPropertyIsNullInsteadOfAnArray() {
            $this->expectException(InvalidArgumentException::class);

            TestCommand5::run();
        }

        public function testShouldThrowAnExceptionMessageIfOptionsPropertyIsNullInsteadOfAnArray() {
            $this->expectExceptionMessage("'options' property should be an array (NULL given)");

            TestCommand5::run();
        }

        /**
         * @todo repeat 2 above for every types possibles
         */

        public function testShouldThrowAnExceptionIfAnOptionHasNotTheKeyName() {
            $this->expectException(InvalidArgumentException::class);

            TestCommand6::run();
        }

        public function testShouldThrowAnExceptionMessageIfAnOptionHasNotTheKeyName() {
            $this->expectExceptionMessage("option #1 should have a key 'name'");

            TestCommand6::run();
        }

        public function testShouldThrowAnExceptionIfAnOptionHasAnIntegerInsteadOfAStringInTheKeyName() {
            $this->expectException(InvalidArgumentException::class);

            TestCommand7::run();
        }

        public function testShouldThrowAnExceptionMessageIfAnOptionHasAnIntegerInsteadOfAStringInTheKeyName() {
            $this->expectExceptionMessage("option #1 should have a string in the key 'name' (integer given)");

            TestCommand7::run();
        }

        public function testShouldThrowAnExceptionIfAnOptionHasAnIntegerInsteadOfAStringInTheKeyShortName() {
            $this->expectException(InvalidArgumentException::class);

            TestCommand8::run();
        }

        public function testShouldThrowAnExceptionMessageIfAnOptionHasAnIntegerInsteadOfAStringInTheKeyShortName() {
            $this->expectExceptionMessage("option #1 should have a string in the key 'shortName' (integer given)");

            TestCommand8::run();
        }

        public function testShouldThrowAnExceptionIfOptionHasAnIntegerInsteadOfAStringInTheKeyDescription() {
            $this->expectException(InvalidArgumentException::class);

            TestCommand9::run();
        }

        public function testShouldThrowAnExceptionMessageIfOptionHasAnIntegerInsteadOfAStringInTheKeyDescription() {
            $this->expectExceptionMessage("option #1 should have a string in the key 'description' (integer given)");

            TestCommand9::run();
        }

        public function testShouldThrowAnExceptionIfTheFlagsPropertyIsNullInsteadOfAnArray() {
            $this->expectException(InvalidArgumentException::class);

            TestCommand10::run();
        }

        public function testShouldThrowAnExceptionIfFlagHasAnNotTheKeyName() {
            $this->expectException(InvalidArgumentException::class);

            TestCommand11::run();
        }

        public function testShouldThrowAnExceptionMessageIfFlagHasAnNotTheKeyName() {
            $this->expectExceptionMessage("flag #1 should have a key 'name'");

            TestCommand11::run();
        }

        public function testShouldThrowAnExceptionIfFlagHasAnIntegerInsteadOfAStringInTheKeyName() {
            $this->expectException(InvalidArgumentException::class);

            TestCommand12::run();
        }

        public function testShouldThrowAnExceptionMessageIfFlagHasAnIntegerInsteadOfAStringInTheKeyName() {
            $this->expectExceptionMessage("flag #1 should have a string in the key 'name' (integer given)");

            TestCommand12::run();
        }

        public function testShouldThrowAnExceptionIfFlagHasAnIntegerInsteadOfAStringInTheKeyShortName() {
            $this->expectException(InvalidArgumentException::class);

            TestCommand13::run();
        }

        public function testShouldThrowAnExceptionMessageIfFlagHasAnIntegerInsteadOfAStringInTheKeyShortName() {
            $this->expectExceptionMessage("flag #1 should have a string in the key 'shortName' (integer given)");

            TestCommand13::run();
        }

        public function testShouldThrowAnExceptionIfFlagHasAnIntegerInsteadOfAStringInTheKeyDescription() {
            $this->expectException(InvalidArgumentException::class);

            TestCommand14::run();
        }

        public function testShouldThrowAnExceptionMessageIfFlagHasAnIntegerInsteadOfAStringInTheKeyDescription() {
            $this->expectExceptionMessage("flag #1 should have a string in the key 'description' (integer given)");

            TestCommand14::run();
        }

        public function testShouldThrowAnExceptionIfAFlagNameIsAReservedFlagName() {
            $this->expectException(InvalidArgumentException::class);

            TestCommand15::run();
        }

        public function testShouldThrowAnExceptionMessageIfAFlagNameIsAReservedFlagName() {
            $FLAG_NAMES = implode(', ', TestCommand15::RESERVED_FLAG_NAMES);

            $this->expectExceptionMessage("flag #1 should not have the following reserved flags names: $FLAG_NAMES");

            TestCommand15::run();
        }

        public function testShouldThrowAnExceptionIfAFlagShortNameIsAReservedFlagShortName() {
            $this->expectException(InvalidArgumentException::class);

            TestCommand16::run();
        }

        public function testShouldThrowAnExceptionMessageIfAFlagShortNameIsAReservedFlagShortName() {
            $FLAG_SHORT_NAMES = implode(', ', TestCommand16::RESERVED_FLAG_SHORT_NAMES);

            $this->expectExceptionMessage("flag #1 should not have the following reserved flag short names: $FLAG_SHORT_NAMES");

            TestCommand16::run();
        }
    }
?>