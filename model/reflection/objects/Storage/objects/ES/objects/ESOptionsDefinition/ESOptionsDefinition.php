<?php
namespace model\reflection\Storage\ES;
class ESOptionsDefinition
{
    function __construct($parentModel,$def)
    {
        // En esta clase, los aliases, indexes,etc, se regeneran cada vez que se
        // llama a getDefinition, para reflejar cambios realizados en el modelo.
        $this->definition=$def;
        $this->parentModel=$parentModel;
    }

    static function createDefault($parentModel)
    {
        // https://www.elastic.co/guide/en/elasticsearch/reference/current/indices-create-index.html
        $defaultDefinition=[
            "settings"=>[
                "index"=>[
                    "number_of_shards"=>2,
                    "number_of_replicas"=>2
                ]
            ]
        ];
        /*
         *
         */
        return new ESOptionsDefinition($parentModel,$defaultDefinition);
    }
    function getDefinition()
    {
        return $this->definition;
    }
}
?>
