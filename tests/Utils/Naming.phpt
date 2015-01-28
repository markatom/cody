<?php

use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

function test($shouldPass, $method, $set) {
	foreach ($set as $string) {
		$result = call_user_func($method, $string);
		if ($shouldPass) {
			Assert::true($result);
		} else {
			Assert::false($result);
		}
	}
}

test(TRUE, 'Markatom\Cody\Utils\Naming::isCamelCase', [
	'a',
	'loremIpsumDolorSitAmet',
	'fooBar42',
	'roles2users',
]);

test(FALSE, 'Markatom\Cody\Utils\Naming::isCamelCase', [
	'_fooBar',
	'roles2Users',
	'42theAnswerToTheUltimateQuestionOfLifeTheUniverseAndEverything',
	'alphaBEta',
	'PascalCase',
	'snake_case',
]);

test(TRUE, 'Markatom\Cody\Utils\Naming::isUnderscorePrefixedCamelCase', [
	'a',
	'loremIpsumDolorSitAmet',
	'fooBar42',
	'roles2users',
	'_a',
	'_loremIpsumDolorSitAmet',
	'_fooBar42',
	'_roles2users',
]);

test(FALSE, 'Markatom\Cody\Utils\Naming::isUnderscorePrefixedCamelCase', [
	'roles2Users',
	'42theAnswerToTheUltimateQuestionOfLifeTheUniverseAndEverything',
	'alphaBEta',
	'PascalCase',
	'snake_case',
	'_',
	'__a',
	'_roles2Users',
	'_42theAnswerToTheUltimateQuestionOfLifeTheUniverseAndEverything',
	'_alphaBEta',
	'_PascalCase',
	'_snake_case',
]);

test(TRUE, 'Markatom\Cody\Utils\Naming::isPascalCase', [
	'A',
	'LoremIpsumDolorSitAmet',
	'FooBar42',
	'Roles2Users',
]);

test(FALSE, 'Markatom\Cody\Utils\Naming::isPascalCase', [
	'_FooBar',
	'Roles2users',
	'42TheAnswerToTheUltimateQuestionOfLifeTheUniverseAndEverything',
	'AlphaBEta',
	'camelCase',
	'snake_case',
]);

test(TRUE, 'Markatom\Cody\Utils\Naming::isScreamingSnakeCase', [
	'A',
	'LOREM_IPSUM_DOLOR_SIT_AMET',
	'FOO_BAR42',
	'FOO_BAR_42',
	'FOO42BAR',
	'FOO_42_BAR',
]);

test(FALSE, 'Markatom\Cody\Utils\Naming::isScreamingSnakeCase', [
	'_FOO_BAR',
	'FOO_BAR_',
	'FOO__BAR',
]);

test(TRUE, 'Markatom\Cody\Utils\Naming::isUnderscorePrefixedScreamingSnakeCase', [
	'A',
	'LOREM_IPSUM_DOLOR_SIT_AMET',
	'FOO_BAR42',
	'FOO_BAR_42',
	'FOO42BAR',
	'FOO_42_BAR',
	'_A',
	'_LOREM_IPSUM_DOLOR_SIT_AMET',
	'_FOO_BAR42',
	'_FOO_BAR_42',
	'_FOO42BAR',
	'_FOO_42_BAR',
]);

test(FALSE, 'Markatom\Cody\Utils\Naming::isUnderscorePrefixedScreamingSnakeCase', [
	'FOO_BAR_',
	'FOO__BAR',
	'_FOO_BAR_',
	'_FOO__BAR',
	'__A',
]);
