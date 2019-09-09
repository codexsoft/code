<?php


namespace CodexSoft\Code\Helpers;


class Cli
{

    /**
     * todo: this nasty method should be reviewed, as it is used in a very speciefic case!
     * @return string
     */
    public static function getFirstArgumentOrDie(): string
    {
        if (PHP_SAPI !== 'cli') {
            die('Configuration loading works only from CLI');
        }

        if (count($_SERVER['argv']) < 2) {
            die("config file is required CLI argument\n");
        }

        $ormConfigFile = realpath($_SERVER['argv'][1]);
        if (!file_exists($ormConfigFile)) {
            die("config file $ormConfigFile does not exists!\n");
        }

        unset($_SERVER['argv'][1]);

        return $ormConfigFile;
    }

    /**
     * This will execute $cmd in the background (no cmd window) without PHP waiting for it to
     * finish, on both Windows and Unix.
     *
     * https://www.php.net/manual/en/function.exec.php#86329
     *
     * @param $cmd
     */
    function execInBackground($cmd): void
    {
        if (substr(php_uname(), 0, 7) === 'Windows') {
            pclose(popen("start /B ". $cmd, "r"));
        } else {
            exec($cmd.' > /dev/null &');
        }
    }

}