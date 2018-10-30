<?php
    namespace Khalyomede;

    use InvalidArgumentException;

    class ConsoleCommand {
        protected $version = 'N/A';
        protected $buildOn = 'N/A';
        protected $description = "No description.";
        protected $arguments = [];
        protected $options = [];
        protected $flags = [];

        public static function run() {
            $instance = static::instance();

            $instance->throwExceptionIfSpecificationOfArgumentsIsInvalid();
            $instance->throwExceptionIfSpecificationOfOptionsIsInvalid();
            $instance->throwExceptionIfSpecificationOfFlagsIsInvalid();
        }

        private static function instance() {
            return new static;
        }

        private function throwExceptionIfSpecificationOfArgumentsIsInvalid(): void {
            if( is_array($this->arguments) === false ) {
                $type = gettype($this->arguments);
                
                throw new InvalidArgumentException("'argument' property should be an array ($type given)");
            }

            foreach( $this->arguments as $index => $argument ) {
                $INDEX = $index + 1;
                $KEYS = array_keys($argument);
                $KEY_NAME = $argument['name'] ?? null;
                $KEY_DESCRIPTION = $argument['description'] ?? null;

                if( in_array('name', $KEYS) === false ) {
                    throw new InvalidArgumentException("argument #$INDEX should have a key 'name'");
                }
                else if( is_string($KEY_NAME) === false ) {
                    $type = gettype($KEY_NAME);

                    throw new InvalidArgumentException("argument #$INDEX should have a string in the key 'name' ($type given)");
                }
                else if( in_array('description', $KEYS) && is_string($KEY_DESCRIPTION) === false ) {
                    $type = gettype($KEY_DESCRIPTION);

                    throw new InvalidArgumentException("argument #$INDEX should have a string in the key 'description' ($type given)");
                }
            }
        }

        private function throwExceptionIfSpecificationOfOptionsIsInvalid(): void {
            if( is_array($this->options) === false ) {
                $type = gettype($this->options);

                throw new InvalidArgumentException("'options' property should should be an array ($type given)");
            }
        }
        private function throwExceptionIfSpecificationOfFlagsIsInvalid(): void {}
    }
?>