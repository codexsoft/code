<?php

namespace CodexSoft\Code\Helpers;

use CodexSoft\Code\Traits\StaticAccess;

class Render
{

    use StaticAccess;

    /**
     * @param $_file_
     * @param array $_params_
     *
     * @return false|string
     * @throws \Throwable
     */
    public function phpFile( $_file_, array $_params_ = [] ) {

        if (!file_exists($_file_)) {
            throw new \Exception("Template file {$_file_} does not exists!");
        }

        ob_start();
        ob_implicit_flush(false);
        extract($_params_, EXTR_OVERWRITE);
        try {
            /** @noinspection PhpIncludeInspection */
            require($_file_);
        } catch (\Throwable $e) {
            ob_clean();
            throw $e;
        };

        return ob_get_clean();
    }

}