<?php

namespace Permafrost\Tests\Unit\Commands;

use Permafrost\PhpCsFixerRules\Commands\GenerateConfigCommand;
use Permafrost\PhpCsFixerRules\Finders\LaravelProjectFinder;
use Permafrost\PhpCsFixerRules\Rulesets\DefaultRuleset;
use Permafrost\PhpCsFixerRules\SharedConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\SingleCommandApplication;
use Symfony\Component\Console\Tester\CommandTester;

class GenerateConfigCommandTest extends TestCase
{
    protected $tempFileCache = [];

    public function setUp(): void
    {
        $this->tempFileCache = [];
    }

    public function tearDown(): void
    {
        $failures = [];

        foreach($this->tempFileCache as $filename) {
            if (file_exists($filename) && is_file($filename)) {
                //echo "unlinking " . basename($filename) . "\n";
                if (!unlink($filename)) {
                    $failures[] = $filename;
                }
            }
        }

        // persist any files that couldn't be unlinked to try again on future method invocations
        $this->tempFileCache = $failures;
    }

    public function getRandomConfigFilename(): string
    {
        $seed = str_repeat('abcdefghijklmnopqrstuvwxyz0987654321', 4);

        return './test_config-' . sha1(str_shuffle($seed)) . '.temp';
    }

    public function generateTempFilename()
    {
        $targetFile = $this->getRandomConfigFilename();

        $counter = 0;
        while (file_exists($targetFile) && $counter < 100) {
            $counter++;
            $targetFile = $this->getRandomConfigFilename();
        }

        $this->tempFileCache[] = $targetFile;

        return $targetFile;
    }

    public function getCommand()
    {
        $command = new GenerateConfigCommand();
        $command->setName('generate');

        return $command;
    }

    public function getCommandTester()
    {
        $command = $this->getCommand();
        $app = $this->getApp($command);

        return new CommandTester($command);
    }

    public function getApp($command)
    {
        return (new Application())
            ->add($command)
            ->addArgument('type', InputArgument::OPTIONAL, 'The type of finder to use.', 'laravel')
            ->addOption('ruleset', 'r', InputOption::VALUE_REQUIRED,  'The ruleset to use', 'default')
            ->addOption('outfile', 'o', InputOption::VALUE_REQUIRED,  'The filename to write to', '.php_cs.dist')
            ->addOption('force', 'f', InputOption::VALUE_NONE,  'Overwrite existing file');
    }

    /** @test */
    public function it_generates_a_config_file(): void
    {
        $filename = $this->generateTempFilename();
        $tester = $this->getCommandTester();

        $this->assertFileDoesNotExist($filename);
        $this->assertNotEmpty($filename);

        $tester->execute(['command' => 'laravel', '-o' => "$filename"]);

        $this->assertEquals(Command::SUCCESS, $tester->getStatusCode());
        $this->assertFileExists($filename);
    }

    /** @test */
    public function it_generates_a_config_file_with_the_correct_imports(): void
    {
        $filename = $this->generateTempFilename();
        $tester = $this->getCommandTester();

        $this->assertFileDoesNotExist($filename);
        $this->assertNotEmpty($filename);

        $tester->execute(['command' => 'laravel', '-o' => "$filename", '-r' => 'default']);

        $this->assertEquals(Command::SUCCESS, $tester->getStatusCode());
        $this->assertFileExists($filename);

        $content = file_get_contents($filename);

        $this->assertStringContainsString('use ' . SharedConfig::class . ';', $content);
        $this->assertStringContainsString('use ' . DefaultRuleset::class . ';', $content);
        $this->assertStringContainsString('use ' . LaravelProjectFinder::class . ';', $content);
    }

    /** @test */
    public function it_generates_a_config_file_that_creates_Ruleset_and_SharedConfig_instances(): void
    {
        $filename = $this->generateTempFilename();
        $tester = $this->getCommandTester();

        $this->assertFileDoesNotExist($filename);
        $this->assertNotEmpty($filename);

        $tester->execute(['command' => 'laravel', '-o' => "$filename", '-r' => 'default']);

        $this->assertEquals(Command::SUCCESS, $tester->getStatusCode());
        $this->assertFileExists($filename);

        $content = file_get_contents($filename);

        $this->assertStringContainsString('new DefaultRuleset()', $content);
        $this->assertStringContainsString('SharedConfig::create(', $content);
    }

    /** @test */
    public function it_generates_a_config_file_that_requires_the_autoloader_file(): void
    {
        $filename = $this->generateTempFilename();
        $tester = $this->getCommandTester();

        $this->assertFileDoesNotExist($filename);
        $this->assertNotEmpty($filename);

        $tester->execute(['command' => 'laravel', '-o' => "$filename", '-r' => 'default']);

        $this->assertEquals(Command::SUCCESS, $tester->getStatusCode());
        $this->assertFileExists($filename);

        $content = file_get_contents($filename);

        $this->assertMatchesRegularExpression("~^\s*require(_once)?\(__DIR__\s*.\s*'/vendor/autoload.php'\);\s*$~m", $content);
    }

    /** @test */
    public function it_fails_to_generate_a_config_file_when_an_invalid_ruleset_name_is_provided(): void
    {
        $filename = $this->generateTempFilename();
        $tester = $this->getCommandTester();

        $this->assertFileDoesNotExist($filename);
        $this->assertNotEmpty($filename);

        $tester->execute(['command' => 'laravel', '-o' => "$filename", '-r' => 'badrulesetname']);

        $this->assertEquals(Command::FAILURE, $tester->getStatusCode());
        $this->assertFileDoesNotExist($filename);
    }

    /** @test */
    public function it_fails_to_generate_a_config_file_when_an_invalid_type_name_is_provided(): void
    {
        $filename = $this->generateTempFilename();
        $tester = $this->getCommandTester();

        $this->assertFileDoesNotExist($filename);
        $this->assertNotEmpty($filename);

        $tester->execute(['command' => 'invalidtypename', '-o' => "$filename", '-r' => 'default']);

        $this->assertEquals(Command::FAILURE, $tester->getStatusCode());
        $this->assertFileDoesNotExist($filename);
    }

    /** @test */
    public function it_fails_when_prompting_the_user_to_overwrite_when_target_config_file_already_exists_and_user_declines(): void
    {
        $filename = $this->generateTempFilename();
        $tester = $this->getCommandTester();

        $this->assertFileDoesNotExist($filename);
        $this->assertNotEmpty($filename);

        $tester->execute(['command' => 'laravel', '-o' => "$filename", '-r' => 'default']);

        $this->assertEquals(Command::SUCCESS, $tester->getStatusCode());
        $this->assertFileExists($filename);

        $tester->setInputs(['no']);
        $tester->execute(['command' => 'laravel', '-o' => "$filename", '-r' => 'default']);

        $this->assertEquals(Command::FAILURE, $tester->getStatusCode());
    }

    /** @test */
    public function it_succeeds_when_prompting_the_user_to_overwrite_when_target_config_file_already_exists_and_user_affirms(): void
    {
        $filename = $this->generateTempFilename();
        $tester = $this->getCommandTester();

        $this->assertFileDoesNotExist($filename);
        $this->assertNotEmpty($filename);

        $tester->execute(['command' => 'laravel', '-o' => "$filename", '-r' => 'default']);

        $this->assertEquals(Command::SUCCESS, $tester->getStatusCode());
        $this->assertFileExists($filename);

        $tester->setInputs(['yes']);
        $tester->execute(['command' => 'laravel', '-o' => "$filename", '-r' => 'default']);

        $this->assertEquals(Command::SUCCESS, $tester->getStatusCode());
        $this->assertFileExists($filename);
    }

    /** @test */
    public function it_succeeds_in_overwriting_existing_file_when_force_option_is_provided_and_target_config_file_already_exists(): void
    {
        $filename = $this->generateTempFilename();
        $tester = $this->getCommandTester();

        $this->assertFileDoesNotExist($filename);
        $this->assertNotEmpty($filename);

        $tester->execute(['command' => 'laravel', '-o' => "$filename", '-r' => 'default']);

        $this->assertEquals(Command::SUCCESS, $tester->getStatusCode());
        $this->assertFileExists($filename);

        $tester->execute(['command' => 'laravel', '--force' => true, '-o' => "$filename"]);

        $this->assertEquals(Command::SUCCESS, $tester->getStatusCode());
        $this->assertFileExists($filename);
    }
}
