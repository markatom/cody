<?php

use Markatom\Cody\Configuration;
use Markatom\Cody\File;
use Markatom\Cody\Finder;
use Markatom\Cody\Output;
use Markatom\Cody\Runner;

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/vendor/markatom/watchers/src/Boolean.php';
require_once __DIR__ . '/vendor/markatom/watchers/src/Keyword.php';
require_once __DIR__ . '/vendor/markatom/watchers/src/Naming.php';
require_once __DIR__ . '/vendor/markatom/watchers/src/Null.php';

$logo = <<<'END'
  *   *
  |\_/|
 / - ^ \  CODY v0.1.0
 ~~o O~~  using cs.neon
   \@/

END;

$help = <<<'END'
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

$runner = new Runner(new Configuration(new File('cs.neon')), new Finder, new Output(STDOUT, TRUE), TRUE);

$runner->run();
