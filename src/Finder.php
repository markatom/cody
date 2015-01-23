<?php

namespace Markatom\Cody;

use Nette\Object;
use Nette\Utils\Finder as NetteFinder;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 */
class Finder extends Object
{

	/**
	 * @param string[] $extensions
	 * @param string[] $masks
	 * @return string[]
	 */
	public function findFiles(array $extensions, array $masks)
	{
		$extensions = array_map(function ($extension) {
			return '*.' . $extension;
		}, $extensions);

		$result = $found = [];

		foreach (NetteFinder::find($extensions)->from('.') as $name => $file) {
			$found[] = substr($name, 1); // remove .
		}

		foreach ($masks as $mask) {
			if (substr($mask, 0, 1) === '!') {
				$pattern = $this->maskToPattern(substr($mask, 1));
				foreach ($result as $index => $name) {
					if (preg_match($pattern, $name)) {
						$found[] = $name;
						unset($result[$index]);
					}
				}

			} else {
				$pattern = $this->maskToPattern($mask);
				foreach ($found as $index => $name) {
					if (preg_match($pattern, $name)) {
						$result[] = $name;
						unset($found[$index]);
					}
				}
			}
		}

		return array_values($result);
	}

	/**
	 * @param string $mask
	 * @return string
	 */
	private function maskToPattern($mask)
	{
		$pattern = preg_quote($mask, '~');

		if (is_int(strpos($pattern, '/'))) { // contains /
			if (substr($pattern, 0, 5) === '\*\*/') { // leading **/
				$pattern = substr($pattern, 4);

			} elseif (substr($pattern, 0, 1) === '/') { // leading /
				$pattern = '^' . $pattern;

			} else { // inner /
				$pattern = '^/' . $pattern;
			}
		}

		if (substr($pattern, -5) === '/\*\*') { // trailing /**
			$pattern = substr($pattern, 0, -4);
		}

		$pattern = str_replace('/\*\*/', '/([^/]+/)*', $pattern); // inner /**/

		$pattern = str_replace('\*', '[^/]*', $pattern); // *

		return '~' . $pattern . '~';
	}

}
