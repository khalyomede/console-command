<?php
    require(__DIR__ . '/../vendor/autoload.php');

    use Khalyomede\ConsoleCommand as Command;

    class ReadFileCommand extends Command {
        protected $version = '0.1.0';
        protected $buildOn = '2018-10-28 20:32';
        protected $description = "Read a file and display its content.";
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
                'description' => 'Cap the maximum of character read to prevent reaching memory limit on big files.',
            ]
        ];
        protected $flags = [
            [
                'name' => 'quiet',
                'shortName' => 'q',
                'description' => 'Do not display runtime information.'
            ]
        ];
    }

    ReadFileCommand::run();
?>