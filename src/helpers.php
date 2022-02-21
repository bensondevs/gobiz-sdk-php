<?php

/**
 * Get the last character of a string
 * 
 * @param string  $string
 * @return string
 */
if (! function_exists('last_character')) {
	function last_character(string $string)
	{
		return substr($string, -1);
	}
}

/**
 * Check if last character of string
 * is match specified string
 * 
 * @param  string  $string
 * @param  string  $match
 * @return  bool
 */
if (! function_exists('is_last_character')) {
	function is_last_character(string $string, string $match)
	{
		return last_character($string) === $match;
	}
}

/**
 * Concat array of paths and avoid the double slash
 * in the paths concated.
 * 
 * This function has 3 parameters
 * - First parameter will handle array of paths,
 * - Set second parameter to true and the resulted path will be
 * started with slash,
 * - Set the third parameter to true and the resulted path will
 * be ended with slash.
 * 
 * @param  array  $paths
 * @param  bool   $startSlash
 * @param  bool   $endSlash
 * @return string
 */
if (! function_exists('concat_paths')) {
    function concat_paths(array $paths, bool $startSlash = false, bool $endSlash = false)
    {
        // Clear slash from paths
        foreach ($paths as $index => $path) {
            if (first_character($path) == '/') {
                $paths[$index] = substr($path, 1);
            }

            if (last_character($path) == '/') {
                $paths[$index] = substr($path, 0, -1);
            }
        }

        // Implode with glue of "/"
        $concatedPaths = implode('/', $paths);

        // If $startSlash is true, add slash to first character.
        if ($startSlash) $concatedPaths = '/' . $concatedPaths;

        // If $endSlash is true, add slash to last character. 
        if ($endSlash) $concatedPaths .= '/';

        // Prevent triple slash
        $concatedPaths = str_replace('///', '/', $concatedPaths);

        // Prevent double slash
        $concatedPaths = str_replace('//', '/', $concatedPaths);

        return $concatedPaths;
    }
}

/**
 * Get certain env variable with condition.
 * If true then get value from second parameter as env attribute.
 * If false do for the third parameter
 * 
 * @param  bool    $condition
 * @param  string  $firstEnvAttr
 * @param  string  $secondEnvAttr
 * @param  mixed   $defaultValue
 * @return mixed
 */
if (! function_exists('which_env')) {
    function which_env(
        bool $condition, 
        string $firstEnvAttr, 
        string $secondEnvAttr,
        $defaultValue = null
    ) {
        $attr = $condition ? $firstEnvAttr : $secondEnvAttr;

        return env($attr, $defaultValue);
    }
}