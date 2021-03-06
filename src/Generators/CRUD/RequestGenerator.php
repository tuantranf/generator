<?php
namespace LaravelRocket\Generator\Generators\CRUD;

class RequestGenerator extends CRUDBaseGenerator
{
    /**
     * @return array
     */
    protected function getVariables(): array
    {
        $modelName                 = $this->getModelName();
        $variables                 = [];
        $variables['modelName']    = $modelName;
        $variables['variableName'] = camel_case($modelName);
        $variables['className']    = $modelName.'Request';

        return $variables;
    }

    protected function getFillableColumns()
    {
        $columnInfo = [
            'fillableColumns'      => [],
            'timestampColumns'     => [],
            'unixTimestampColumns' => [],
            'booleanColumns'       => [],
        ];

        $excludes = ['id', 'remember_token', 'created_at', 'deleted_at', 'updated_at'];

        foreach ($this->table->getColumns() as $column) {
            $name = $column->getName();
            $type = $column->getType();

            if (in_array($name, $excludes)) {
                continue;
            }
            if (ends_with($name, '_at') && ($type === 'timestamp' || $type === 'timestamp_f')) {
                $columnInfo['timestampColumns'][] = $name;
                continue;
            }
            if (ends_with($name, '_at') && $type === 'int') {
                $columnInfo['unixTimestampColumns'][] = $name;
                continue;
            }
            if ((starts_with($name, 'is_') || starts_with($name, 'has_')) && $type === 'int') {
                $columnInfo['booleanColumns'][] = $name;
                continue;
            }
            $columnInfo['fillableColumns'][] = $name;
        }

        return $columnInfo;
    }
}
