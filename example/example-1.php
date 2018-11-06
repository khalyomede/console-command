<?php
    require(__DIR__ . '/../vendor/autoload.php');

    use Khalyomede\ConsoleCommand as Command;

    class ReadFileCommand extends Command {
        protected $name = 'readfile';
        protected $version = '0.1.0';
        protected $lastBuiltOn = '2018-10-28 20:32';
        protected $description = "Read a file and display its content.";
        protected $example = 'readfile /documents/programing/php/test.php';
        protected $arguments = [
            [
                'name' => 'path',
                'description' => 'Location of the file.',
            ]
        ];
        protected $options = [
            [
                'name' => 'max-length',
                'shortName' => 'm',
                'description' => 'Cap the maximum of character read to prevent reaching memory limit on big files.'
            ]
        ];
        protected $flags = [
            [
                'name' => 'quiet',
                'shortName' => 'q',
                'description' => 'Do not display runtime information.'
            ]
        ];

        public function process() {
            $filename = $this->argument('path');
            $maxLength = $this->hasOption('max-length') ? $this->option('max-length') : null;
            $shouldBeQuiet = $this->hasFlag('quiet');

            $elapsed = null;
            $content = '';
            
            // if( $shouldBeQuiet === false ) {
            //     echo "reading file...";

            //     $elapsed = microtime(true);
            // }

            if( $maxLength > 0 ) {
                $content = file_get_contents($filename, null, null, null, $maxLength);
            }
            else {
                $content = file_get_contents($filename);
            }

            echo $content . PHP_EOL;

            // if( $shouldBeQuiet === false ) {
            //     $elapsed = round(microtime(true) - $elapsed, 4);

            //     echo "file read in $elapsed seconds";
            // }
        }
    }

    ReadFileCommand::run();
?>