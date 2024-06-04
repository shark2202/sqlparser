<?php

/**
 * This file is part of the Zephir.
 *
 * (c) Phalcon Team <team@zephir-lang.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Zephir\Optimizers\FunctionCall;

use Zephir\Call;
use Zephir\CompilationContext;
use Zephir\CompiledExpression;
use Zephir\Optimizers\OptimizerAbstract;

use function count;

/**
 * JsonEncodeOptimizer.
 *
 * Optimizes calls to 'json_encode' using internal function
 */
class Sql2jsonOptimizer extends OptimizerAbstract{

	public function optimize(array $expression, Call $call, CompilationContext $context){
        if (!isset($expression['parameters'])) {
            return false;
        }

        /*
         * Process the expected symbol to be returned
         */
        $call->processExpectedReturn($context);

        $symbolVariable = $call->getSymbolVariable(true, $context);
        //$this->checkNotVariable($symbolVariable, $expression);

        $context->headersManager->add('sqlparser');

        $resolvedParams = $call->getReadOnlyResolvedParams($expression['parameters'], $context, $expression);


        $symbol = $context->backend->getVariableCode($symbolVariable);


        $codes = <<<CODE
        const char* __sql__ = Z_STRVAL(sql);
        const char* __dialog__ = Z_STRVAL(dialog);
        const char* __json = sql2json(__sql__, __dialog__);
        ZVAL_STRING(&_json, __json);
        free(__json);
CODE;
        $context->codePrinter->output($codes);


        return new CompiledExpression('variable', $symbolVariable->getRealName(), $expression);
	}
}