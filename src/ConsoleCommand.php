<?php
    namespace Khalyomede;

    use InvalidArgumentException;
    use Khalyomede\CommandParser as Parser;
    use Khalyomede\Exception\NotEnoughArgumentsException;
    use Khalyomede\Exception\OptionWithoutValueException;

    class ConsoleCommand {
        const RESERVED_FLAG_NAMES = ['help', 'version'];
        const RESERVED_FLAG_SHORT_NAMES = ['h', 'v'];

        protected $name = '';
        protected $version = 'N/A';
        protected $lastBuiltOn = 'N/A';
        protected $description = "No description.";
        protected $arguments = [];
        protected $options = [];
        protected $flags = [];
        
        private $argumentsList = [];
        private $optionsList = [];
        private $flagsList = [];

        public static function run() {
            $instance = static::instance();

            $instance->throwExceptionIfSpecificationOfArgumentsIsInvalid();
            $instance->throwExceptionIfSpecificationOfOptionsIsInvalid();
            $instance->throwExceptionIfSpecificationOfFlagsIsInvalid();
            $instance->evaluateArguments();
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

                throw new InvalidArgumentException("'options' property should be an array ($type given)");
            }

            foreach( $this->options as $index => $option ) {
                $INDEX = $index + 1;
                $KEYS = array_keys($option);
                $KEY_NAME = $option['name'] ?? null;
                $KEY_SHORT_NAME = $option['shortName'] ?? null;
                $KEY_DESCRIPTION = $option['description'] ?? null;

                if( in_array('name', $KEYS) === false ) {
                    throw new InvalidArgumentException("option #$INDEX should have a key 'name'");
                }
                else if( is_string($KEY_NAME) === false ) {
                    $type = gettype($KEY_NAME);

                    throw new InvalidArgumentException("option #$INDEX should have a string in the key 'name' ($type given)");
                }
                else if( in_array('shortName', $KEYS) === true && is_string($KEY_SHORT_NAME) === false ) {
                    $type = gettype($KEY_SHORT_NAME);

                    throw new InvalidArgumentException("option #$INDEX should have a string in the key 'shortName' ($type given)");
                }
                else if( in_array('description', $KEYS) === true && is_string($KEY_DESCRIPTION) === false ) {
                    $type = gettype($KEY_DESCRIPTION);

                    throw new InvalidArgumentException("option #$INDEX should have a string in the key 'description' ($type given)");
                }
            }
        }

        private function throwExceptionIfSpecificationOfFlagsIsInvalid(): void {
            if( is_array($this->flags) === false ) {
                $type = gettype($this->flags);

                throw new InvalidArgumentException("'flags' property should be an array ($type given)");
            }

            foreach( $this->flags as $index => $flag ) {
                $INDEX = $index + 1;
                $KEYS = array_keys($flag);
                $KEY_NAME = $flag['name'] ?? null;
                $KEY_SHORT_NAME = $flag['shortName'] ?? null;
                $KEY_DESCRIPTION = $flag['description'] ?? null;

                if( in_array('name', $KEYS) === false ) {
                    throw new InvalidArgumentException("flag #$INDEX should have a key 'name'");
                }
                else if( is_string($KEY_NAME) === false ) {
                    $type = gettype($KEY_NAME);
                    
                    throw new InvalidArgumentException("flag #$INDEX should have a string in the key 'name' ($type given)");
                }
                else if( in_array($KEY_NAME, static::RESERVED_FLAG_NAMES) === true ) {
                    $FLAG_NAMES = implode(', ', static::RESERVED_FLAG_NAMES);

                    throw new InvalidArgumentException("flag #$INDEX should not have the following reserved flags names: $FLAG_NAMES");
                }
                else if( in_array('shortName', $KEYS) === true && is_string($KEY_SHORT_NAME) === false ) {
                    $type = gettype($KEY_SHORT_NAME);

                    throw new InvalidArgumentException("flag #$INDEX should have a string in the key 'shortName' ($type given)");
                }
                else if( in_array($KEY_SHORT_NAME, static::RESERVED_FLAG_SHORT_NAMES) === true ) {
                    $FLAG_SHORT_NAMES = implode(', ', static::RESERVED_FLAG_SHORT_NAMES);

                    throw new InvalidArgumentException("flag #$INDEX should not have the following reserved flag short names: $FLAG_SHORT_NAMES");
                }
                else if( in_array('description', $KEYS) === true && is_string($KEY_DESCRIPTION) === false ) {
                    $type = gettype($KEY_DESCRIPTION);

                    throw new InvalidArgumentException("flag #$INDEX should have a string in the key 'description' ($type given)");
                }
            }
        }

        private function evaluateArguments() {
            $configuration = $this->commandConfiguration();

            try {
                $command = Parser::parse($configuration);
            }
            catch( NotEnoughArgumentsException $exception ) {
                $userNumberOfArgument = $exception->getUserNumberOfArgument();
                $requiredNumberOfArgument = $exception->getRequiredNumberOfArgument();

                echo "Not enough arguments." . str_repeat(PHP_EOL, 2);
                echo "Usage:" . PHP_EOL;
                echo " " . $this->usageDetail() . PHP_EOL;

                exit(1);
            }
            catch( OptionWithoutValueException $exception ) {
                $name = $exception->getOptionName();

                echo "Option \"$name\" does not have any value." . str_repeat(PHP_EOL, 2);
                echo "Use --help or -h for a complete list of options and flags." . PHP_EOL;

                exit(2);
            }

            if( isset($command['flags']['version']) === true ) {
                echo "{$this->name} version {$this->version} (built on {$this->lastBuiltOn}).";

                exit(0);
            }
            else if( isset($command['flags']['help']) === true ) {
                echo "{$this->description}" . str_repeat(PHP_EOL, 2);
                echo "Usage:" . PHP_EOL;
                echo " " . $this->usageDetail() . str_repeat(PHP_EOL, 2);
                echo $this->example();
                echo "Arguments:" . PHP_EOL;
                echo $this->argumentsDetail() . PHP_EOL;
                echo "Options:" . PHP_EOL;
                echo $this->options() . PHP_EOL;
                echo "Flags:" . PHP_EOL;
                echo $this->flags();
    
                exit(0);
            }
            else {
                $arguments = $this->getArguments();

                $argumentsKeys = $this->argumentsNames();

                $this->argumentsList = array_combine($argumentsKeys, $arguments);
                $this->optionsList = getopt($this->getOptShortOptions(), $this->getOptLongOptions());
                $this->flagsList = getopt($this->getOptShortFlags(), $this->getOptLongFlags());

                $this->process();
            }
        }

        private function userRequiresCommandVersion(): bool {
            $options = getopt('v', ['version']);

            return isset($options['v']) || isset($options['version']);
        }

        private function userRequiresHelpManual(): bool {
            $options = getopt('qh', ['help']);

            die();            

            return isset($options['h']) || isset($options['help']);
        }

        private function arguments(): string {
            $arguments = '';
            
            foreach( $this->arguments as $argument ) {
                $name = $argument['name'];

                $arguments .= " <$name>";
            }

            return $arguments;
        }

        private function argumentsNames(): array {
            return array_map(function($argument) {
                return $argument['name'];
            }, $this->arguments);
        }

        private function options() {
            $options = '';

            foreach( $this->options as $option ) {
                $name = $option['name'];
                $shortName = $option['shortName'] ?? null;
                $description = $option['description'] ?? null;

                $options .= " --$name";

                if( empty($shortName) === false ) {
                    $options .= ", -$shortName";
                }

                if( empty($description) === false ) {
                    $options .= ": $description";
                }

                $options .= PHP_EOL;
            }

            return $options;
        }

        private function flags(): string {
            $flags = '';

            foreach( $this->flags as $flag ) {
                $name = $flag['name'];
                $shortName = $flag['shortName'] ?? null;
                $description = $flag['description'] ?? null;

                $flags .= " --$name";

                if( empty($shortName) === false ) {
                    $flags .= ", -$shortName";
                }

                if( empty($description) === false ) {
                    $flags .= ": $description";
                }

                $flags .= PHP_EOL;
            }

            return $flags;
        }

        private function example() {
            if( empty($this->example) === false ) {
                echo "Example:" . PHP_EOL;
                echo " {$this->example}" . str_repeat(PHP_EOL, 2);
            }
        }

        private function getOptShortOptions(): string {
            $shortOptions = '';
            
            foreach( $this->options as $option ) {
                if( isset($option['shortName']) === false ) {
                    continue;
                }       
                
                $shortOptions .= "{$option['shortName']}:";

                if( isset($option['default']) === true ) {
                    $shortOptions .= ":";
                }
            }

            return $shortOptions;
        }

        private function getOptShortFlags(): string {
            $shortFlags = '';

            foreach( $this->flags as $flag ) {
                if( isset($flag['shortName']) === false ) {
                    continue;
                }

                $shortFlags .= "{$flag['shortName']}";
            }

            return $shortFlags;
        }

        /**
         * @see http://php.net/manual/en/function.getopt.php
         */
        private function shortOptionsAndFlags(): string {
            $shortOptionsAndFlags = '';
            
            $shortOptionsAndFlags .= $this->getOptShortOptions();
            $shortOptionsAndFlags .= $this->getOptShortFlags();

            return $shortOptionsAndFlags;
        }

        private function getOptLongOptions(): array {
            $longOptions = [];
            
            foreach( $this->options as $option ) {
                $longOption = "{$option['name']}:";

                if( isset($option['default']) === true ) {
                    $longOption .= ':';
                }

                $longOptions[] = $longOption;
            }

            return $longOptions;
        }

        private function getOptLongFlags(): array {
            $longFlags = [];
            
            foreach( $this->flags as $flag ) {
                $longFlags[] = "{$flag['name']}";
            }

            return $longFlags;
        }

        private function longOptionsAndFlags(): array {
            return array_merge($this->getOptLongOptions(), $this->getOptLongFlags());
        }

        private function argumentsDetail(): string {
            $detail = '';
            
            foreach( $this->arguments as $argument ) {
                $detail .= " {$argument['name']}";

                if( isset($argument['description']) === true ) {
                    $detail .= ": {$argument['description']}";
                }

                $detail .= PHP_EOL;
            }

            return $detail;
        }

        /**
         * @todo make a library?
         */
        private function getArguments(): array {
            global $argc;
            global $argv;

            $argumentsValues = [];
            
            for( $i = 0; $i < $argc; $i++ ) {
                $argument = ltrim($argv[$i], '-');

                if( in_array($argument, $this->longOptions()) === true || in_array($argument, $this->shortOptions()) === true ) {
                    $i++;

                    continue;
                }
                else if( $this->userUsedEqualToSetAValueFor($argument) === true ) {
                    continue;
                }
                else if( in_array($argument, $this->longFlags()) === true || in_array($argument, $this->shortFlags()) === true ) {
                    continue;
                }

                $argumentsValues[] = $argument;
            }

            array_shift($argumentsValues);

            return $argumentsValues;
        }

        private function longOptions(): array {
            return array_map(function($option) {
                return $option['name'];
            }, $this->options);
        }

        private function shortOptions(): array {
            return array_map(function($option) {
                return $option['shortName'];
            }, array_filter($this->options, function($option) {
                return isset($option['shortName']);
            }));
        }

        private function longFlags(): array {
            return array_map(function($flag) {
                return $flag['name'];
            }, $this->flags);
        }

        private function shortFlags(): array {
            return array_map(function($flag) {
                return $flag['shortName'];
            }, array_filter($this->flags, function($flag) {
                return isset($flag['shortName']);
            }));
        }

        private function userUsedEqualToSetAValueFor(string $argument): bool {            
            $options = array_merge($this->shortOptions(), $this->longOptions());

            $userUsedEqualToSetAValue = false;

            foreach( $options as $option ) {
                $regexp = '/^' . preg_quote($option) . '=/';

                if( preg_match($regexp, $argument) === 1 ) {
                    $userUsedEqualToSetAValue = true;

                    break;
                }
            }
            
            return $userUsedEqualToSetAValue;
        }

        private function usageDetail(): string {
            $usageDetail = '';

            $usageDetail .= $this->name;

            foreach( $this->arguments as $argument ) {
                $usageDetail .= " <{$argument['name']}>";
            }

            return $usageDetail;
        }

        /**
         * To be overrided by the developer 
         * that extends this class.
         */
        protected function process() {}

        /**
         * @return mixed|null
         */
        protected function argument(string $name) {
            return $this->argumentsList[$name] ?? null;
        }

        protected function hasOption(string $name): bool {
            return isset($this->optionsList[$name]);
        }

        protected function option(string $name) {
            return $this->optionsList[$name] ?? null;
        }

        protected function hasFlag(string $name): bool {
            return isset($this->flagsList[$name]);
        }

        private function commandConfiguration(): array {
            $configuration = ['arguments' => [], 'options' => [], 'flags' => ['version,v', 'help,h']];

            foreach( $this->arguments as $argument ) {
                $configuration['arguments'][] = $argument['name'];
            }

            foreach( $this->options as $option ) {
                $conf = "{$option['name']}";

                if( isset($option['shortName']) === true ) {
                    $conf .= ",{$option['shortName']}";
                }

                $configuration['options'][] = $conf;
            }
            
            foreach( $this->flags as $flag ) {
                $conf = "{$flag['name']}";

                if( isset($flag['shortName']) === true ) {
                    $conf .= ",{$flag['shortName']}";
                }

                $configuration['flags'][] = $conf;
            }

            return $configuration;
        }
    }
?>