<?php
namespace lib\model\permissions;
include_once(LIBPATH."/model/BaseException.php");
class AclException extends \lib\model\BaseException
{

    const ERR_GROUP_ALREADY_EXISTS = 1;
    const ERR_GROUP_DOESNT_EXIST = 2;
    const ERR_ITEM_NOT_FOUND = 3;
    const ERR_ITEM_ALREADY_EXIST = 4;
    const ERR_INVALID_ACL_SPECIFICATION = 5;
    const ERR_ACO_NOT_FOUND = 6;
    const ERR_ARO_NOT_FOUND = 7;

}

class AclManager
{

    const DEFAULT_USER_GROUP="Users";
    const DEFAULT_ADMIN_GROUP="Admins";
    const DEFAULT_EDITORS_GROUP="Editors";
    const DEFAULT_REFLECTION_GROUP="Reflection";
    const PERMISSIONTYPE_GROUP=1;
    const PERMISSIONTYPE_ITEM=0;
    // Aro => usuarios
    const ARO = 0;
    // Aco => tipos de permisos
    const ACO = 1;
    // Axo => objetos sobre los que se definen permisos
    const AXO = 2;

    var $conn;

    function __construct($serializer = null)
    {
        if (!$serializer)
        {
            global $APP_NAMESPACES;
            if(in_array("web",$APP_NAMESPACES))
                $tdb="web";
            else
                $tdb=$APP_NAMESPACES[0];
            $serializer = Registry::$registry["serializers"][$tdb];
        }

        $this->itemTypes = array("aro", "aco", "axo");
        $this->conn = $serializer->getConnection();
        
    }

    function install()
    {
        // Los campos "TYPE" en grupos,items,etc, significan : 0: ARO, 1:ACO, 2:AXO
        // Primera tabla : grupos. 
        $q[] = "CREATE TABLE IF NOT EXISTS _permission_groups (id smallint(8) AUTO_INCREMENT PRIMARY KEY NOT NULL,group_name varchar(30),group_type smallint(2),group_parent smallint(8) DEFAULT 0,group_path varchar(200),group_charPath char(255), KEY USING HASH(group_type,group_name,group_parent),KEY(group_name,group_path),UNIQUE KEY(group_charPath))";
        $q[] = "CREATE TABLE IF NOT EXISTS _permission_items (id smallint(8) AUTO_INCREMENT PRIMARY KEY NOT NULL,item_type smallint(2),item_name varchar(20),item_value varchar(50),KEY USING HASH(item_type,item_name,item_value))";
        $q[] = "CREATE TABLE IF NOT EXISTS _permission_group_items (group_id smallint(8),item_id smallint(8),KEY (group_id),KEY(item_id))";
        $q[] = "CREATE TABLE IF NOT EXISTS _permissions (id smallint(8) AUTO_INCREMENT PRIMARY KEY,aro_type smallint(1),aco_type smallint(1),aro_id smallint(8),aco_id smallint(8),axo_type smallint(1) DEFAULT 0,axo_id smallint(8) DEFAULT 0,allow smallint(1) DEFAULT 1,enabled smallint(1) DEFAULT 1,ACLDATE TIMESTAMP,UNIQUE KEY(aro_type,aro_id,aco_type,aco_id,axo_type,axo_id))";
        $this->conn->batch($q);
    }

    function uninstall()
    {
        $q[] = "DROP TABLE IF EXISTS _permission_groups";
        $q[] = "DROP TABLE IF EXISTS _permission_items";
        $q[] = "DROP TABLE IF EXISTS _permission_group_items";
        $q[] = "DROP TABLE IF EXISTS _permissions";

        $this->conn->batch($q, true);
    }

    // This method is used to load a set of items from an array.
    // The item's sections are set to the name of its parent group, or to its type name.
    function createPermissions(& $currentArr, $section, $group, $type)
    {
        if (!$section)
        {
            $keys = array_keys($currentArr);
            for ($k = 0; $k < count($keys); $k++)
            {
                $name = $keys[$k];

                if (!is_array($currentArr[$name]))
                {
                    // Es un item.Hay que incluirlo como item, con nombre de seccion, el nombre del tipo
                    $this->add_object($type, $currentArr[$name], $type);
                }
                else
                {
                    // Hay que crear este grupo.
                    $gId = $this->add_group($name, 0, $type);
                    $this->createPermissions($currentArr[$name], $name, $gId, $type);
                }
            }
            return;
        }
        if (!is_array($currentArr))
        {
            // Si un item no es un array, es que es un elemento simple.Hay que crearlo primero,
            // y luego aniadirlo al grupo.El metodo add_object ya se encarga de chequear que el 
            // objeto ya exista...
            $id = $this->add_object($section, $currentArr, $type);
            if ($group)
                $this->add_group_object($group, $section, $currentArr);
            return;
        }
        $keys = array_keys($currentArr);
        for ($k = 0; $k < count($keys); $k++)
        {
            $name = $keys[$k];
            $cgroup = $group;
            if (is_array($currentArr[$name]))
            {
                $cgroup = $this->add_group($name, $group, $type);
            }
            $this->createPermissions($currentArr[$name], $section, $cgroup, $type);
        }
    }

    /**
     * add_acl()
     *
     * $aco, $aro and $axo are associative arrays, especifying to which GROUPS and ITEMS are we referring to.
     * Each one of them are, again, associative arrays.
     * The GROUPS sub-array has the form : [root-group]=>array("[group-name-1]","[group-name-2]"....)
     * The ITEMS sub-array has the form : [item-name(section)]=>array("[item-value]","[item-value]"....)
     *  ("GROUP"=>array("<root-group>"=>array("<group-1>"...."<group-n>"),"<root-group>"=>....),
     *   "ITEM"=>array("<name-1>"=>array("<value-1>"..."<value-n>"),"<name-2>"=>array("<value-1>"
     *  No numeric id's are needed.They're resolved from their names.
     */
    function add_acl($aco, $aro, $axo = NULL, $allow = 1, $enabled = 1)
    {

        $itemTypes = & $this->itemTypes;
        for ($k = 0; $k < count($itemTypes); $k++)
        {

            $curItem = $itemTypes[$k];
            $curVal = $$curItem;            
            if (!$curVal)
                continue;
            if (isset($curVal["ITEM"]))
            {                              
                $expr="'".(is_array($curVal["ITEM"])?implode("','", $curVal["ITEM"]):$curVal["ITEM"])."'";
                $nameExpr="";
                if(isset($curVal["NAME"]))
                    $nameExpr=" AND item_name='".$curVal["NAME"]."'";
                $q = "SELECT 0 as type,id FROM _permission_items WHERE item_type=" . $k . $nameExpr." AND item_value IN (" . $expr . ")";
            }
            else
            {
                $expr="'".(is_array($curVal["GROUP"])?implode("','", $curVal["GROUP"]):$curVal["GROUP"])."'";
                $q = "SELECT 1 as type,id FROM _permission_groups WHERE group_type=" . $k . " AND group_name IN (" . $expr . ")";
            }


            $sources[$curItem] = "(" . $q . ") " . $curItem;
            $fields[] = $curItem . ".type as " . $curItem . "_type," . $curItem . ".id as " . $curItem . "_id";
        }
        $fullQuery = "SELECT " . implode(",", $fields) . ",$allow as allow,$enabled as enabled FROM " . $sources["aro"] . " LEFT JOIN " . $sources["aco"] . " ON 1=1";
        if (isset($sources["axo"]))
            $fullQuery.=" LEFT JOIN " . $sources["axo"] . " ON 1=1";
        
        // Se ejecuta la query, para asegurarnos de que el aco y el aro existen.
        $data = $this->conn->select($fullQuery);

        if (count($data) == 0)
            throw new AclException(AclException::ERR_INVALID_ACL_SPECIFICATION);
        $fieldNames = null;
        $fieldValues = array();
        for ($k = 0; $k < count($data); $k++)
        {
            if (!$fieldNames)
                $fieldNames = array_keys($data[$k]);
            if ($data[$k]["aco_id"] == 0)
                throw new AclException(AclException::ERR_ACO_NOT_FOUND, array("aco" => $aco));

            if ($data[$k]["aro_id"] == 0)
                throw new AclException(AclException::ERR_ARO_NOT_FOUND, array("aro" => $aro));

            $fieldValues[] = "(" . implode(",", array_values($data[$k])) . ")";
        }
        $insertQuery = "INSERT INTO _permissions (" . implode(",", $fieldNames) . ") VALUES " . implode(",", $fieldValues)." ON DUPLICATE KEY UPDATE ACLDATE=NOW()";
        $this->conn->insert($insertQuery);
    }

    function add_acl_by_id($aco, $aro, $axo = NULL, $allow = 1, $enabled = 1)
    {
        $itemTypes = & $this->itemTypes;
        for ($k = 0; $k < count($itemTypes); $k++) {
            $curItem = $itemTypes[$k];
            $curVal = $$curItem;
            if (!$curVal)
                continue;
            $fieldNames[] = $curItem . "_type";
            if (isset($curVal["ITEM"])) {
                $fieldValues[] = 0;
                $val = $curVal["ITEM"];
            } else {
                $fieldValues[] = 1;
                $val = $curVal["GROUP"];
            }
            $fieldNames[] = $curItem . "_id";
            $fieldValues[] = $val;
        }
        $insertQuery = "INSERT INTO _permissions (" . implode(",", $fieldNames) . ") VALUES (" . implode(",", $fieldValues).") ON DUPLICATE KEY UPDATE ACLDATE=NOW()";
        $this->conn->insert($insertQuery);
    }

    /**
     * del_acl()
     * Deletes a given ACL
     */
    function del_acl($acl_id)
    {
        $this->conn->delete("DELETE FROM _permissions where id in ('" . implode("','", (array) $acl_id) . "')");
    }

    function getRootGroupId($group_name, $type = AclManager::ARO)
    {
        $results = $this->conn->select("SELECT id FROM _permission_groups WHERE group_name='$group_name' AND group_parent=0 AND group_type=$type");
        if (!isset($results[0]["id"]))
        {
            throw new AclException(AclException::ERR_GROUP_DOESNT_EXIST);
        }
        return $results[0]["id"];
    }

    /**
     * get_group_id()
     * Gets the group_id given the name or value.
     */
    function get_group_id($group_root, $group_name, $type = AclManager::ARO)
    {
        $pId = 0;
        if ($group_root)
            $pId = $this->getRootGroupId($group_root, $type);
        if ($pId)
            $q = "SELECT id FROM _permission_groups where group_name='$group_name' AND group_path LIKE '," . $pId . ",%'";
        else
            $q = "SELECT id FROM _permission_groups where group_name='$group_name' AND group_type='$type'"; // AND group_path=id";
        $results = $this->conn->select($q);
        if (!isset($results[0]["id"]))
            throw new AclException(AclException::ERR_GROUP_DOESNT_EXIST);
        return $results[0]["id"];
    }

    /**
     * get_group_parent_id()
     * Grabs the parent_id of a given group
     */
    function get_group_parent_id($group_id)
    {
        $results = $this->conn->select("SELECT group_parent FROM _permission_groups WHERE id=$group_id");
        if (!$results[0]["group_parent"])
            throw new AclException(AclException::ERR_GROUP_DOESNT_EXIST);

        return $results[0]["group_parent"];
    }

    /**
     * add_group()
     * Inserts a group, defaults to be on the "root" branch.
     * The path is supposed to be in the form : "/a/b/c/d"
     * All paths must be absolute.
     */
    function getGroupFromPath($path, $type = AclManager::ARO,$autoCreate=false)
    {
        // Si es una cadena, se espera que sean nombres de grupos separados por "/", que
        // es mas intuitivo que por comas.
        $parts = explode("/", $path);
        $currentPath = "";
        $lastId = 0;
        // El path no era absoluto...esto se considera un error.
        for ($k = 1; $k < count($parts); $k++)
        {
            if($parts[$k]=="")
                continue;
            if ($currentPath != "")
                $currentPath.=",";
            $q = "SELECT id from _permission_groups WHERE group_name='" . $parts[$k] . "' AND group_type=$type";
            

            if ($currentPath != "")
                $q.=" AND group_path like '," . $currentPath . "%'";
            
            $arr = $this->conn->select($q);
            

            if (!$arr)
            {
                if($autoCreate)
                    $id=$this->add_group($parts[$k],$lastId,$type);
                else
                    throw new AclException(AclException::ERR_GROUP_DOESNT_EXIST);
            }
            else
                $id=$arr[0]["id"];
            $currentPath .= $id;
            $lastId = $id;

        }
        if($lastId==0)
        {
            throw new AclException(AclException::ERR_GROUP_DOESNT_EXIST);
        }
        return $lastId;
    }

	private function __getGroupIdCondition($group_parent)
	{
			if(is_numeric($group_parent))			
				return "id=$group_parent";			
			return "group_charPath='$group_parent'";
	}
    // Group parent is the id of the parent group OR the path string.
	
    // If set to 0, it's a root group
    function add_group($group_name, $group_parent = 0, $type = AclManager::ARO)
    {        
		$parent_id=0;
        if ($group_parent)
        {

            // Buscamos que tipo de grupo es el padre...Y asi lo pasamos al hijo.
			
			// is group_parent is numeric, we take it as the group_id of the parent.
		
							
            $q = "SELECT id,group_type,group_path,group_charPath from _permission_groups where ".$this->__getGroupIdCondition($group_parent);
            $result = $this->conn->select($q);
            if (count($result) != 1)
            {
                throw new AclException(AclException::ERR_GROUP_DOESNT_EXIST);
            }
            $type = $result[0]["group_type"];
            $path = $result[0]["group_path"] . ",";
			$fullPath=$result[0]["group_charPath"];
			$parent_id=$result[0]["id"];
        }

        if (!$parent_id)
        {
            $parent_id = 0;
            $path=",";
        }
        // Lo insertamos primero sin especificar el path.Una vez tengamos el id del nuevo grupo,
        // se construye el path.
        $group_id = $this->conn->insert("INSERT INTO _permission_groups (group_type,group_name,group_parent,group_charPath) VALUES ($type,'$group_name',$parent_id,'".$fullPath."/".$group_name."')");
        $path.=$group_id;
        $this->conn->update("UPDATE _permission_groups SET group_path='$path' WHERE id='$group_id'");
        return $group_id;
    }
	
	
	function __getGroupFrom($groupId)
	{
		return  $this->conn->select("SELECT * FROM _permission_groups WHERE ".$this->__getGroupIdCondition($groupId));
	}
	
    function __itemIdFromGroupAndId($group_id, $item_name, $item_value = "")
    {

        // From the group id, we'll know what kind of object (aro,aco,axo) is this.

        $gData = $this->__getGroupFrom($group_id);
        if (count($gData) == 0)
            throw new AclException(AclException::ERR_GROUP_DOESNT_EXIST);
		

        $q = "SELECT id FROM _permission_items WHERE item_value='$item_value' AND item_type=" . $gData[0]["group_type"];
        $arr = $this->conn->select($q);
        if (count($arr) == 0)
            throw new AclException(AclException::ERR_ITEM_NOT_FOUND, array("group_id" => $group_id,
                "name" => $item_name,
                "value" => $item_value));
        return array("item"=>$arr[0]["id"],"group"=>$gData[0]["id"]);
    }

    /**
     * add_group_object()
     * Assigns an Object to a group
     */
    function add_group_object($group_id, $item_name_or_id, $item_value = "")
    {

        if ($item_value != "")
        {
            $ids = $this->__itemIdFromGroupAndId($group_id, $item_name_or_id, $item_value);

			$item_id=$ids["item"];
			$gid=$ids["group"];
        }
        else
		{

            $item_id = $item_name_or_id;
			$gData=$this->__getGroupFrom($group_id);
			$gid=$gData[0]["id"];

			
		}
        $q="SELECT group_id from _permission_group_items WHERE item_id=$item_id AND group_id=$gid";
        echo $q."\n";
        $res = $this->conn->select($q);
        if (count($res) == 0) // No existia la asignacion de item a grupo.
            $this->conn->insert("INSERT INTO _permission_group_items (group_id,item_id) VALUES ($gid,$item_id)");

        return $item_id;
    }

    /**
     * del_group_object()
     * Removes an Object from a group.
     */
    function del_group_object($group_id, $item_name_or_id, $item_value = "")
    {
        $ids = $this->__itemIdFromGroupAndId($group_id, $item_name_or_id, $item_value);
		$item_id=$ids["item"];
		$gid=$ids["group"];
        $this->conn->delete("DELETE FROM _permission_group_items WHERE group_id=$gid AND item_id=$item_id");
    }

    function rename_group($group_id, $newName)
    {
        
    }

    function reparent_group($group_id, $newParentId)
    {
        
    }

    /**
     * del_group()
     * deletes a given group
     * If reparent_children is true, the group childs are moved to their grandparent.If not, they're removed also.
     * All the acls referred to those groups are deleted too.
     */
    function del_group($group_id, $reparent_children = TRUE)
    {

        if (!$group_id)
            return;
        // Primero, obtenemos informacion del grupo:
        $groupInfo = $this->__getGroupFrom($group_id);
		$group_id=$groupInfo[0]["group_id"];

        if (!$groupInfo || count($groupInfo) == 0)
            throw new AclException(AclException::ERR_GROUP_DOESNT_EXIST);

        if (!$groupInfo[0]["group_parent"])
            $reparent_children = FALSE;

        if ($reparent_children)
        {
            // Se mueven todos los hijos de este grupo, al padre.
            // Primero, los grupos hijos
			$repPath="/".$groupInfo["group_name"];
            $this->conn->update("UPDATE _permission_groups SET group_parent=" . $groupInfo[0]["group_parent"] . ",group_charPath=REPLACE(group_charPath,'$repPath','') WHERE group_parent=$group_id");
            // Segundo, los items
            $this->conn->update("UPDATE _permission_group_items SET group_id=".$groupInfo[0]["group_parent"]." WHERE group_id=$group_id");

        }
        else
        {

            $path = "%,$group_id,%";
            $groupsToDelete = array();
            $this->conn->selectCallback("SELECT id FROM _permission_groups WHERE group_path LIKE '$path'", function($arr)
                    {
                        $groupsToDelete[] = $arr["id"];
                    });
        }
        $groupsToDelete[] = $group_id;
        $groupCad = implode(",", $groupsToDelete);
        // Eliminacion de los grupos
        $qs[] = "DELETE FROM _permission_groups WHERE id IN ($groupCad)";
        $qs[] = "DELETE FROM _permission_group_items WHERE group_id IN ($groupCad)";
        // Determinar sobre que columna tenemos que hacer match en la tabla principal.
        switch ($groupInfo[0]["group_type"])
        {
            case AclManager::ARO:
                {
                    $column = "aro";
                }break;
            case AclManager::ACO:
                {
                    $column = "aco";
                }break;
            default:
                {
                    $column = "axo";
                }
        }
        $qs[] = "DELETE FROM _permissions WHERE " . $column . "_type=1 AND " . $column . "_id IN ($groupCad)";
        $this->conn->batch($qs);
    }

    /**
     * get_object_groups()
     * Gets all groups an object is a member of.
     */
    function get_object_groups($object_id, $type = AclManager::ARO)
    {

        return $this->conn->selectColumn("SELECT group_id FROM _permission_group_items WHERE item_id=$object_id", "group_id");
    }

    /**
     * add_object()
     * Inserts a new object
     */
    function add_object($item_name, $item_value, $item_type = AclManager::ARO)
    {
        // Si ya existe, devolvemos el id del objeto existente

        try {
            return $this->get_object($item_value, $item_type);
        }
        catch(AclException $e){
            return $this->conn->insert("INSERT INTO _permission_items (item_name,item_value,item_type) VALUES ('$item_name','$item_value',$item_type)");
        }
    }

    function get_object($item_value, $item_type = AclManager::ARO,$name=null)
    {
        $nameExpr="";
        if($name!=null)
        {
            $nameExpr=" AND item_name='".$name."'";
        }
        $arr = $this->conn->select("SELECT id FROM _permission_items WHERE  item_value='$item_value' $nameExpr AND item_type=$item_type");

        if (count($arr))
            return $arr[0]["id"];
        throw new AclException(AclException::ERR_ITEM_NOT_FOUND);
    }


    function set_object_name($item_id, $newName)
    {
        
    }

    function set_object_value($item_id, $newValue)
    {
        
    }

    /**
     * del_object()
     */
    function del_object($item_id)
    {
        // Se obtiene informacion (tipo) del item
        $arr = $this->conn->select("SELECT * FROM _permission_items WHERE id=$item_id");
        if (count($arr) == 0)
            throw new AclException(AclException::ERR_ITEM_NOT_FOUND);

        $type = $this->itemTypes[$arr[0]["item_type"]];
        $q = array("DELETE FROM _permission_items WHERE id=$item_id",
            "DELETE FROM _permission_group_items WHERE item_id=$item_id",
            "DELETE FROM _permissions WHERE " . $type . "_type=0 AND " . $type . "_id=$item_id");
        $this->conn->batch($q);
    }

    /**
     * acl_query
     */
    /*
      aco debe contener ITEM y GROUP
      aro y axo son arrays que contienen ITEM o GROUP

     */


    function acl_check($aco, $aro, $axo = null,$allResults=false)
    {
        
        if (!isset($aco["ITEM"]) && !isset($aco["GROUP"]))
            return false;
        $parts = array("aro", "aco", "axo");
        
        for ($k = 0; $k < count($parts); $k++)
        {
            $current = $parts[$k];
            if (!$$current)
                continue;
            $c = $$current;
            $subQ = "(";
            if (isset($c["ITEM"]))
            {

                $subQ.='SELECT g.group_type,IF(i.id is NULL,0,i.id) as id ,g.id as group_id,group_path from _permission_groups g
                            LEFT JOIN  _permission_group_items gi ON g.id=gi.group_id
                            LEFT JOIN _permission_items i ON i.id=gi.item_id WHERE
                             item_type=' . $k . ' AND item_value=\'' . $c["ITEM"] . '\'';
                if (isset($c["GROUP"]))
                {
					if($c["GROUP"][0]=="/")
						$cond='g.group_charPath=\'' . $c["GROUP"] . '\'';
					else
						$cond='g.group_name=\'' . $c["GROUP"] . '\'';
                    $subQ.=' UNION SELECT g.group_type,null as id ,g.id as group_id,group_path from _permission_groups g
                             WHERE '.$cond;
                }
            }
            else
            {
                $subQ.="SELECT $k as item_type,null as id,g.id as group_id,group_path from _permission_groups g WHERE group_type=$k AND group_name='" . $c["GROUP"] . "'";
            }
            $subqueries[] = $subQ . ")" . $current . "s";
        }

        
        $q = '
        SELECT p.id,(4*aro_type+2*aco_type+axo_type) as score,aro_type,aro_id,axo_type,axo_id,allow,        
            IF(aco_type=1,aco_id,0) as acoNumber,
            IF(aro_type=1,aro_id,0) as aroNumber,
            ACLDATE FROM _permissions p,' .
                implode(",", $subqueries);

        $q.=' WHERE 
                ((aro_type=0 AND aro_id=aros.id ) OR (aro_type=1 AND LOCATE(CONCAT(\',\',CONCAT(aro_id,\',\')),CONCAT(\',\',CONCAT(aros.group_path,\',\')))))
                AND
                ((aco_type=0 AND aco_id=acos.id) OR (aco_type=1 AND LOCATE(CONCAT(\',\',CONCAT(aco_id,\',\')),CONCAT(\',\',CONCAT(acos.group_path,\',\')))))';
        if ($axo)
        {

            $q.=' AND
                ((axo_type=0';
            if($allResults==false)
                $q.=' AND axo_id=axos.id';
            $q.=') OR (axo_type=1 AND  LOCATE(CONCAT(\',\',CONCAT(axo_id,\',\')),CONCAT(\',\',CONCAT(axos.group_path,\',\')))))';
        }


        // Nota: los permisos, si son por grupos o son individuales, se resuelven por fecha.
            $q.=' ORDER BY (2*aro_type+axo_type) ASC ,ACLDATE DESC,p.id DESC';
        if($allResults==false)
            $q.=' LIMIT 1';


        $results = $this->conn->select($q);
        // Si no hay entradas, en allow habra un 0.
        if(count($results)==0)
            return false;
        if($allResults)
            return $results;
        return $results[0]['allow']==0?false:true;
    }

    function getUserPermissions($module, $moduleItem, $userId, $userGroup = "AllUsers",$returnAll=false)
    {
        
        $aro = array("GROUP" => $userGroup, "ITEM" => $userId);
        if ($moduleItem)
            $axo = array("GROUP" => $module, "ITEM" => $moduleItem);
        else
            $axo = array("GROUP" => $module);

        $parts = array("aro", "aco", "axo");
        
        for ($k = 0; $k < count($parts); $k++)
        {
            if ($k == 1)
                continue;

            $current = $parts[$k];
            if (!$$current)
                continue;
            $c = $$current;
            $subQ = "(";
            if (isset($c["ITEM"]))
            {

                $subQ.='SELECT g.group_type,IF(i.id is NULL,0,i.id) as id ,g.id as group_id,group_path from _permission_groups g
                            LEFT JOIN  _permission_group_items gi ON g.id=gi.group_id
                            LEFT JOIN _permission_items i ON i.id=gi.item_id WHERE
                             item_type=' . $k . ' AND item_value=\'' . $c["ITEM"] . '\'';
                if ($c["GROUP"])
                {
					if($c["GROUP"][0]=="/")
						$cond='g.group_charPath=\'' . $c["GROUP"] . '\'';
					else
						$cond='g.group_name=\'' . $c["GROUP"] . '\'';
					
                    $subQ.=' UNION SELECT g.group_type,null as id ,g.id as group_id,group_path from _permission_groups g
                             WHERE '.$cond;
                }
            }
            else
            {
                $subQ.="SELECT $k as item_type,null as id,g.id as group_id,group_path from _permission_groups g WHERE group_type=$k AND group_name='" . $c["GROUP"] . "'";
            }
            $subqueries[] = $subQ . ")" . $current . "s";
        }
        $q = '
        SELECT IF(group_name IS NULL, pi.item_value,pii.item_value) AS name,allow FROM
        (
        SELECT aco_type,aco_id,
            IF(aco_type=1,aco_id,0) as acoNumber,
            IF(aro_type=1,aro_id,0) as aroNumber,
            allow
            FROM _permissions p,' .
                implode(",", $subqueries);

        $q.=' WHERE 
                ((aro_type=0 AND aro_id=aros.id ) OR (aro_type=1 AND LOCATE(CONCAT(\',\',CONCAT(aro_id,\',\')),CONCAT(\',\',CONCAT(aros.group_path,\',\')))))
             AND
                ((axo_type=0 AND axo_id=axos.id) OR (axo_type=1 AND  LOCATE(CONCAT(\',\',CONCAT(axo_id,\',\')),CONCAT(\',\',CONCAT(axos.group_path,\',\')))))
                ORDER BY (aro_type*10+aco_type),aroNumber DESC, acoNumber DESC,ACLDATE DESC 
             ) p LEFT JOIN

                 _permission_groups pg ON (pg.id=aco_id OR pg.group_path LIKE CONCAT(\'%,\',CONCAT(aco_id,\',%\'))) AND pg.group_type=1 AND aco_type=1 LEFT JOIN
                _permission_group_items pgi ON pgi.group_id=pg.id LEFT JOIN _permission_items pii ON pgi.item_id=pii.id LEFT JOIN
                _permission_items pi ON pi.id=aco_id AND pi.item_type=1 AND aco_type=0 WHERE not(pi.item_value IS NULL AND pii.item_value IS NULL)';
        $results = $this->conn->select($q);
        if (!$results)
            return array();
        if($returnAll)
            return $results;
        $perms = array();

        foreach ($results as $k)
            $perms[$k["name"]]=$k["allow"];

        return $perms;
        // Si no hay entradas, en allow habra un 0.
        //return $results[0]['allow'];
    }

    function getAccessDetails($module, $moduleItem, $permission, $userId)
    {
        // Aro => usuarios
        // Aco => tipos de permisos
        // Axo => objetos sobre los que se definen permisos
        //aco aro axo
        $res=$this->acl_check(
            array("ITEM"=>$permission),
            array("ITEM"=>$userId),
            array("GROUP"=>$module),
            true
        );
        /*SELECT aro_type,aro_id,axo_type,allow,
            IF(aco_type=1,aco_id,0) as acoNumber,
            IF(aro_type=1,aro_id,0) as aroNumber,
            ACLDATE FROM _permissions p,' .
          */
        if(count($res)==0)
            return array("allow"=>false);
        // General incluira el permiso para el modulo (el grupo).
        $general=null;
        $allow=array();
        $disallow=array();
        // Allowed incluira el permiso para
        $inverse=array();
        for($k=0;$k<count($res);$k++)
        {
            $cRes=$res[$k];
            if($cRes["axo_type"]==AclManager::PERMISSIONTYPE_GROUP)
            {
                if($general==null)
                    $general=$cRes["allow"]=="1"?true:false;
            }
            else
            {
                if($moduleItem && $cRes["axo_id"]==$moduleItem)
                {
                    $general=$cRes["allow"];
                    $allow=[];
                    $disallow=[];
                    break;
                }
                if($cRes["allow"]=="1")
                    $allow[]=$cRes["axo_id"];
                else
                    $disallow[]=$cRes["axo_id"];

            }
        }
        $inverse=($general==true?$disallow:$allow);
        if(count($inverse)>0)
        {
            $q="SELECT item_value from _permission_items where id in (".implode(",",$inverse).")";
            $inverse=$this->conn->selectColumn($q, "item_value");
        }
        return array($general,$inverse);
    }

    /*

      Las siguientes funciones son los principales metodos publicos para acceder al sistema de permisos
     */

    // A partir de una instancia del modelo , devuelve una especificacion de permisos
    function getModelPermissionSpec($item)
    {
        $oName=$item->__getObjectName();
        $keys=$item->__getKeys();
        $aKeys=$keys->get();
        return array("ITEM" =>  $oName. implode("_", $aKeys),
            "GROUP" => $oName);
    }


    function canAccess($permsDefinition, $user, $model = null)
    {
        $permsObj = new \model\reflection\Permissions\PermissionRequirementsDefinitionRequirementsDefinition($permsDefinition);
        if ($permsObj->isJustPublic())
            return true;
        if ($model)
        {
            $curState = $model->getState();
            $reqPermissions = $permsObj->getRequiredPermissionsForState($curState);
        }
        else
            $reqPermissions = $permsObj->getRequiredPermissions();

        if ($model)
        {
            $axoParam = $this->getModelPermissionSpec($model);
        }

        foreach ($reqPermissions as $key => $value)
        {
            $reqPerms = array();
            if ($value == 'PUBLIC')
                return true;
            if ($value == 'OWNER')
            {
                $owner = $model->getOwner();
                if ($owner == $user->getId())
                    return true;
            }

            if ($this->acl_check(array("ITEM" => $value), array("ITEM" => $user->getId(), "GROUP" => "Users"), $axoParam))
                return true;
        }
        return false;
    }

    // Funcion para simplificar la busqueda de ids en los siguientes metodos.
    // Cada uno de los elementos, es un array, que contiene un elemento "ITEM" o un elemento "GROUP",  un flag "CREATE", en caso de que no se haya encontrado, y un valor "CREATEPATH" para, si se quiere crear, agregarlo a ese PATH.
    // Retorna un array con el id del ITEM y/o GROUP
    function resolveAccessIds($aro, $aco, $axo)
    {
        foreach (array("aro", "aco", "axo") as $key => $value)
        {

            $current = $$value;

            if (!$current)
                continue;
            if (isset($current["ITEM"]))
            {
                if (isset($current["CREATE"]))
                {
                    $id = $this->add_object($current["NAME"], $current["ITEM"], $key);

                    if (isset($current["CREATEPATH"]))
                    {
                        $groupId = $this->getGroupFromPath($current["CREATEPATH"], $key,true);
                        if (!$groupId)
                        {
                            throw new AclException(AclException::ERR_GROUP_DOESNT_EXIST);
                        }
                        else
                        {
                            $this->add_group_object($groupId, $id);
                            $results[$value]["GROUP"] = $groupId;
                        }
                    }
                }
                else
                    $id = $this->get_object($current["ITEM"], $key);

                $results[$value]["ITEM"] = $id;
            }
            else
            {
                if (isset($current["GROUP"]))
                {
                    if (isset($current["CREATE"])) // Si hay un CREATE, GROUP es simplemente un nombre. Si no, debe ser un path
                    {
                        if (isset($current["CREATEPATH"]))
                        {
                            $groupParent = $this->getGroupFromPath($current["CREATEPATH"], $key);
                            if (!$groupParent)
                            {
                                // TODO: throw exception;
                                return;
                            }
                        }
                        else
                            $groupParent = 0;

                        $groupId = $this->add_group($current["GROUP"], $groupParent, $key);
                    }
                    else
                    {
                        $groupId = $this->getGroupFromPath($current["GROUP"], $key);
                    }
                    $results[$value]["GROUP"] = $groupId;
                }
            }

        }
        return $results;
    }

    function getModulePath($model, $onlyParent = false)
    {
        if (is_object($model))
        {
            $objName = $model->__getObjectNameObj();
        }
        else
        {
            $objName = new \model\reflection\Model\ModelName($model);
        }
        $objLayer = $objName->layer;
        if ($onlyParent)
            return "/AllObjects/Sys/" . $objLayer . "/" . $objLayer . "Modules";

        $objClass = $objName->className;
        $objClass=str_replace('\\',"_",$objClass);

        return "/AllModules/Sys/" . $objLayer . "/" .str_replace('\\','/',$objClass);
    }

    public function getModelId($model)
    {
        $modelName = $model->__getObjectName();
        return $modelName . implode("_", array_values($model->__getKeys()->get()));
    }

    private function revokePermission($aro, $aco, $axo = null)
    {
        $result = $this->resolveAccessIds($aro, $aco, $axo);
        foreach (array("aro", "aco", "axo") as $key => $value)
        {
            $param = $$value;
            if (!$param && $value == "axo")
                break;

            // Si no se ha encontrado un elemento, se retorna.
            if ((isset($param["ITEM"]) && !isset($result[$value]["ITEM"])) ||
                    isset($param["GROUP"]) && !isset($result[$value]["GROUP"]))
                return;

            if (isset($param["ITEM"]))
            {
                $selectParts[] = $value . "_type=0 AND " . $value . "_id=" . $result[$value]["ITEM"];
                $insertParts[] = "0," . $result[$value]["ITEM"];
            }
            else
            {
                $selectParts[] = $value . "_type=1 AND " . $value . "_id=" . $result[$value]["GROUP"];
                $insertParts[] = "1," . $result[$value]["GROUP"];
            }
            $insertFields[] = $value . "_type," . $value . "_id";
        }

        $q = "SELECT id FROM _permissions WHERE " . implode(" AND ", $selectParts) . " AND allow=1";
        $res = $this->conn->select($q);
        if (count($res) > 0)
        {
            // TODO: Y si hay mas de 1 resultado?
            $q = "UPDATE _permissions SET allow=0 WHERE id=" . $res[0]["id"];
            $this->conn->update($q);
        }
        else
        {
            // Se inserta una linea especifica denegando permisos a ese elemento.
            $q = "INSERT INTO _permissions (" . implode(",", $insertFields) . ",allow,enabled) VALUES (" . implode(",", $insertParts) . ",0,1)";
            $this->conn->insert($q);
        }
    }

    // Permissions debe ser un array
    function givePermissionOverItem($permissions, $userId, $model)
    {

        $itemValue = $this->getModelId($model);
        // Se llama a resolveAccessIds para asegurarnos de que los objetos se crean.
        $this->resolveAccessIds(array("ITEM" => $userId, "CREATE" => 1), null, array("ITEM" => $itemValue, "CREATE" => 1, "CREATEPATH" => $this->getModulePath($model)));

        $this->add_acl(
                array("ITEM" => is_array($permissions) ? $permissions : array($permissions)), array("ITEM" => array($userId)), array("ITEM" => array($itemValue)));
    }

    function removePermissionOverItem($permission, $userId, $model)
    {
        // Esto es bastante mas complicado; Primero hay que ver si existe una entrada acl que explicitamente de ese permiso.Si existe, hay que cambiarla de allow, a no-allow.
        // Si no existe, hay que introducirla en la tabla.        
        $this->revokePermission(array("ITEM" => $userId), array("ITEM" => $permission), array("ITEM" => $this->getModelId($model))
        );
    }

    function addPermissionOverModule($permissions, $userId, $moduleName)
    {

        $this->add_acl(
                array("ITEM" => is_array($permissions) ? $permissions : array($permissions)), array("ITEM" => array($userId)), array("GROUP" => array($moduleName)));
    }
    function addModule($modelClass)
    {
        $this->resolveAccessIds(null, null, array("GROUP" => $modelClass, "CREATE" => 1, "CREATEPATH" => $this->getModulePath($modelClass)));
    }

    function removePermissionOverModule($permission, $userId, $moduleName)
    {
        $result = $this->revokePermission(array("ITEM" => $userId), array("ITEM" => $permission), array("GROUP" => $moduleName)
        );
    }

    function addGroupPermissionOverModule($permissions, $groupName, $moduleName)
    {
        $this->add_acl(
                array("ITEM" => is_array($permissions) ? $permissions : array($permissions)), array("GROUP" => array($groupName)), array("GROUP" => array($moduleName))
        );
    }

    function removeGroupPermissionOverModule($permission, $groupName, $moduleName)
    {
        $this->revokePermission(array("GROUP" => $groupName), array("ITEM" => $permission), array("GROUP" => $moduleName));
    }

    function addUserToGroup($groupName, $userId)
    {
        $res1 = $this->resolveAccessIds(array("ITEM" => $userId, "CREATE" => 1), null, null);
        $res2 = $this->resolveAccessIds(array("GROUP"=>$groupName,"CREATE" => 1), null, null);
        $this->add_group_object($res2["aro"]["GROUP"],$res1["aro"]["ITEM"]);
    }

    function getUserGroups($userId)
    {
        $q = "SELECT group_name from 
            _permission_groups pg
                LEFT JOIN (SELECT CONCAT(',',CONCAT(group_path,',')) as mgroup 
                            from _permission_groups g
                                LEFT JOIN _permission_group_items gi ON g.id=gi.group_id
                                LEFT JOIN _permission_items i ON gi.item_id=i.id
                                WHERE i.item_value='" . $userId . "'
                            ) ig
                ON LOCATE(CONCAT(',',CONCAT(pg.group_path,',')),mgroup)>0 WHERE mgroup IS NOT NULL";
        return $this->conn->selectColumn($q, "group_name");
    }

    // Se busca si el usuario pertenece al grupo, o a alguno de sus padres.
    function userBelongsToGroup($groupName, $userId)
    {
        //_d($this->getUserGroups($userId));
        return in_array($groupName, $this->getUserGroups($userId));
    }

    function removeUserFromGroup($groupName, $userId)
    {
        $result = $this->resolveAccessIds(array("ITEM" => $userId, "CREATE" => 1), null, null);

        $groupId = $this->get_group_id(null, $groupName, AclManager::ARO);
        if (!$groupId)
            return null;
        $this->del_group_object($groupId, $result["ITEM"]);
    }

    function grantPermissionToGroup($groupName, $permissionName)
    {
        $this->add_acl(
                array("ITEM" => array($permissionName)), array("GROUP" => array($groupName)));
    }

    function revokePermissionToGroup($groupName, $permissionName)
    {
        $this->revokePermission(
                array("GROUP" => $groupName), array("ITEM" => $permissionName)
        );
    }

}

?>
