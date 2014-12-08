<?php

require_once __DIR__ . '/vendor/autoload.php';

$logo = <<<END
  .   .
  |\_/|
 / - ^ \  CODY the fox
 ~~o O~~  version 0.1.0
   \@/

END;

$help = <<<END
\033[1mUSAGE:
    \033[1mcody\033[0m check [-h | --help] [-d | --details] [ -s | --summary] |
         fix [-h | --help] [-d | --details] [ -s | --summary]

\033[1mCOMMANDS:\033[0m
    \033[1mcheck\033[0m
      Checks whether target source files contains violiation against defined
      coding standards.

    \033[1mfix\033[0m
      Performs reformatting of target source files according to defined
      coding standards.

\033[1mOPTIONS:\033[0m
    \033[1m-h, --help\033[0m
      Display help page.

    \033[1m-d, --details\033[0m
      Foo bar.

    \033[1m-s, --summary\033[0m
      Lorem ipsum dolor sit amet.

END;

echo $logo . PHP_EOL;

$output = new \Markatom\Cody\Output();

$output->startProgress(256);

for ($i = 0; $i < 256; $i++) {
	$result = new \Markatom\Cody\Result('foo');

	switch(rand(0, 40)) {
		case 0:
			$result->addViolation(new \Markatom\Cody\Token(0, 0, 'Lorem Ipsum dolor sit amet'), '');
			break;

		case 1:
			$result->addWarning(new \Markatom\Cody\Token(0, 0, ''), '');
			break;
	}

	$output->advanceProgress($result);

	usleep(10000);
}

$output->finishProgress();
$output->writeSummary();
$output->writeHints();

var_dump(((function_exists('posix_isatty') && posix_isatty(STDOUT)) || getenv('ConEmuANSI') === 'ON' || getenv('ANSICON') !== FALSE));