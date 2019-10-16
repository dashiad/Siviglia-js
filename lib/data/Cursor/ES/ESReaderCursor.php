<?php


namespace lib\data\Cursor\ES;
include_once(__DIR__ . "/../ReaderCursor.php");
include_once(LIBPATH . "/storage/ES/ESClient.php");
include_once(LIBPATH . "/storage/ES/ESBaseQuery.php");

/**
 * Class ESReaderCursor
 * @package lib\data\Cursor\ES
 *  (c) Smartclip
 * Clase de tipo cursor para leer de Elasticsearch.
 * Soporta agregaciones y queries planas.Ambas son convertidas a tablas usando la clase ESBaseQuery
 */
class ESReaderCursor extends \lib\data\Cursor\ReaderCursor
{
    var $query;
    var $curLine;
    var $result;
    var $nRes;
    var $scrollId;

    /**
     * @param $params
     * En $params existen las keys:
     *  hosts: array de hosts de elasticsearch
     *  aggs : (opcional): agregaciones, como cadena de texto.Esta cadena se formatea segun lo indicado en ESBaseQuery
     *  fields : (opcional): Campos a devolver
     *  query :  query base de ES, con filtros, condiciones,etc
     *  documentType : Tipo de documento a devolver
     *  index : Indice sobre el que ejecutar la query.
     *  page : numero de lineas por pagina, en queries no agregadas.
     */
    function init($params)
    {
        parent::init($params);
        $this->client = new \lib\ES\ESClient($params["hosts"]);
        $this->query = new \lib\ES\ESBaseQuery($params["hosts"]);
        // Si la query es agregada, no queda mas remedio que cargar todos los datos, y luego, en produce, simplemente devolverlos uno a uno.
        if (isset($params["aggs"])) {
            $this->result = $this->query->fetch_agg_plain($this->params["aggs"], $this->params["query"], $this->params["documentType"], $this->params["index"]);
            $this->nRes = count($this->result);
        } else {
            $this->curPage = null;
        }
    }

    /**
     * @return bool
     * Generacion de lineas del cursor. En caso de query agregada, devuelve linea a linea. En caso de query plana, devuelve pagina a pagina.
     */
    function produce()
    {
        if (isset($this->params["aggs"])) {
            if ($this->nRes == $this->curLine)
                return false;
            return [$this->result[$this->curLine]];
        } else {

            $params = $this->params;
            $curPage = $this->query->fetch_plain($params["fields"], $params["query"], $params["documentType"], $this->params["index"], $this->scrollId, isset($params["page"]) ? $params["page"] : 1000);
            if ($curPage == null)
                return false;
            $this->mpush($curPage);
            return true;
        }
    }
}
