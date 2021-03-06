<?php namespace DBDiff\Params;

use DBDiff\Exceptions\CLIException;
use DBDiff\Logger;


class ParamsFactory {

    public function get() {

        $params = new DefaultParams;

        $cli = new CLIGetter;
        $paramsCLI = $cli->getParams();

        if (!isset($paramsCLI->debug)) {
            error_reporting(E_ERROR);
        }

        if (!isset($paramsCLI->silent)) {
            Logger::$silent = false;
        }

        $fs = new FSGetter($paramsCLI);
        $paramsFS = $fs->getParams();
        $params = $this->merge($params, $paramsFS);

        $params = $this->merge($params, $paramsCLI);

        if (empty($params->server1)) {
            throw new CLIException("A server is required");
        }
        return $params;

    }

    protected function merge($obj1, $obj2) {
        return (object) array_merge((array) $obj1, (array) $obj2);
    }
}
