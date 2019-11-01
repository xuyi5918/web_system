{@update-if-block}
            if(! empty(${@UPDATE_ITEM@})) {
                $update['{@UPDATE_KEY@}'] = ${@UPDATE_ITEM@};
            }
{update-if-block@}

{@empty-function-block}
        ${@VAR_ITEM@} = empty(${@VAR@}['{@ITEM@}']) ? {@DEFAULT@} : ${@VAR@}['{@ITEM@}'];{empty-function-block@}

{@update-insert-array-block}
                '{@ARRAY-KEY@}' => ${@ARRAY-VAR@},{update-insert-array-block@}
