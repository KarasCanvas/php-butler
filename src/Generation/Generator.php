<?php
/**
 * @author Raven <karascanvas@qq.com>
 */

namespace PhpButler\Generation;

use PhpButler\Schema\SchemaProvider;

class Generator
{
    public static function generate(SchemaProvider $provider, TableBasedTemplate $template)
    {
        $tables = $provider->getTables();
        $result = [];
        foreach ($tables as $table) {
            $template->setTable($table);
            $filename = $template->getOutputFile();
            if ($template->render()) {
                $result['generated'][] = $filename;
            } else {
                $result['skipped'][] = $filename;
            }
        }
        return $result;
    }
}