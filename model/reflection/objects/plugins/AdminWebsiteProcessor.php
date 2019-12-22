<?php
namespace model\reflection\Processor;

class AdminWebsiteProcessor extends \model\reflection\base\SystemPlugin
{
    function SAVE_SYSTEM($level)
    {

        if ($level != 2)
            return;
        $this->generateIndexPage($sys);

    }

    function generateIndexPage()
    {
        $config = new \model\reflection\base\ConfigurationFile(PROJECTPATH . "/html/Website/admin/config.php", "\\Website\\admin", '');
        if ($config->mustRebuild("page", "index", PROJECTPATH . "/html/Website/admin/index.wid")) {
            $this->generateIndexPageDefinition($sys, 'Website\\admin',
                '/admin/index.wid',
                array('PUBLIC'),
                "/admin/index",
                PROJECTPATH . "/html/Website/admin");

        }

        $packages=\model\reflection\ReflectorFactory::getPackageNames();
        for($kk=0;$kk<count($packages);$kk++) {
            $pkg=new \model\reflection\Package($packages[$kk]);
            $objs = $pkg->getModels();
            foreach ($objs as $key => $value) {
                if ($config->mustRebuild("page", "index" . $key, PROJECTPATH . "/html/Website/admin/$key/index.wid")) {
                    $this->generateIndexPageDefinition($sys, 'Website\\admin\\' . $key,
                        '/admin/' . $key . '/index.wid',
                        array(),
                        '/admin/' . $key . "/index",
                        PROJECTPATH . "/html/Website/admin/" . $key, $key, $value);

                }
            }
        }
    }

    function generateIndexPageDefinition($sys, $namespace, $relPath, $permissions, $webPath, $destPath, $objName = null, $objDef = null)
    {
        $def = array(
            "NAME" => "index",
            "TYPE" => "HTML",
            "MODEL" => null,
            "CACHING" => array(
                "TYPE" => "NO-CACHE"
            ),
            "ENCODING" => "utf8",
            "LAYOUT" => array($relPath),
            "PERMISSIONS" => $permissions,
            "FIELDS" => array(),
            "PATH" => $webPath,
            "WIDGETPATH"=>array(
                "/html/Website"
            )
        );
        $packages=\model\reflection\ReflectorFactory::getPackageNames();
        for($kk=0;$kk<count($packages);$kk++) {
            $package = $packages[$kk];
            $def["WIDGETPATH"][] = "/$package/objects";
        }
        $def["WIDGETPATH"][]="/output/html/Widgets";

        $text = "<?php\r\n\tnamespace " . $namespace . ";\nclass index extends \\lib\\output\\html\\WebPage\n" .
            " {\n" .
            "        var \$definition=";
        $text .= \lib\php\ArrayTools::dumpArray($def, 5);
        $text .= ";\n}\n?>";

        @mkdir($destPath, 0777, true);
        file_put_contents($destPath . "/index.php", $text);
        if ($objName != null)
            $prefix = "/admin/$objName/";

        $page = "[*" . $prefix . "ADMINPAGE]\n\t[_TITLE]Admin index[#]\n\t[_CONTENT]\n\t\tWelcome to this site admin pages\n\t[#]\n[#]";
        file_put_contents($destPath . "/index.wid", $page);

        if (!$objName) {
            $cont = $this->generateIndexPageWidget($sys);
            $cont = str_replace("{%submenu%}", "", $cont);
            file_put_contents(PROJECTPATH . "/output/html/Widgets/ADMINPAGE.wid", $cont);
        } else {
            // Se crea la pagina en si:
            $dss = array();
            $datasources = $objDef->getDataSources();
            $dss[] = "[_:MENUITEM][_:LABEL][@L]Index[#][#][_:LINK]" . WEBPATH . "/admin/" . $objName . "[#][#]";
            foreach ($datasources as $key => $value) {
                if ($value->isAdmin()) {
                    $dss[] = "\t\t\t\t[_:MENUITEM][_:LABEL][@L]" . $value->getLabel() . "[#][#][_:LINK]" . WEBPATH . "/admin/" . $objName . "/" . $value->getName() . "[#][#]\n";
                }
            }
            $actions = $objDef->getActions();
            foreach ($actions as $key => $value) {
                if ($value->isAdmin()) {
                    $name = str_replace("Action", "", $value->getName());
                    $dss[] = "\t\t\t\t[_:MENUITEM][_:LABEL][@L]" . $value->getLabel() . "[#][#][_:LINK]" . WEBPATH . "/admin/" . $objName . "/" . $name . "[#][#]\n";
                }

            }
            $menuText = "\n\t\t\t[*:/MENUS/TABBED/SIMPLE]\n" . implode("", $dss) . "\t\t\t[#]\n";

            // Se crea la plantilla ADMINPAGE para este objeto, que contiene
            $cont = <<<'LAYOUT'
            [*:ADMINPAGE]
                [_TITLE][_:TITLE][_*][#][#]
                [_:CONTENT]{%submenu%}[_CONTENT][_*][#][#]
            [#]            
LAYOUT;
            $cont = str_replace("{%submenu%}", $menuText, $cont);
            $dest = PROJECTPATH . "/html/Website/admin/" . $objName . "/ADMINPAGE.wid";
            @mkdir(dirname($dest), 0777, true);

            file_put_contents($dest, $cont);
        }
    }

    function generateIndexPageWidget($sys, $module = '')
    {
        ob_start();
        ?>
        [*:/LAYOUTS/LAYOUT_ADMIN]
        [_:TITLE][_TITLE][_*][#][#]
        [_:MENU]
        [*:/MENUS/MENU_1]
        {%menuitem%}
        [#]
        [#]
        [_:CONTENT]{%submenu%}[_CONTENT][_*][#][#]
        [_:SIDE][_SIDE][_*][#][#]
        [_:FOOTER][_FOOTER][_*][#][#]
        [#]
        <?php
        $cont = ob_get_clean();
        $code = "";
        $offset = 3;

        $packages=\model\reflection\ReflectorFactory::getPackageNames();
        for($kk=0;$kk<count($packages);$kk++) {
            $package = $packages[$kk];
            $pkg = new \model\reflection\Package($package);
            $objs = $pkg->getModels($pkg);
            $code .= str_repeat("\t", $offset) . '[_:MENUGROUP({"permission":"' . ucfirst($package) . 'Admin"})]' . "\n";
            foreach ($objs as $key => $val) {
                $code .= str_repeat("\t", $offset + 1) . '[_:MENUITEM({"permission":"adminList","module":"' . $key . '"})]' . "\n";
                $code .= str_repeat("\t", $offset + 2) . '[_:LINK]' . WEBPATH . '/admin/' . $key . '[#]' . "\n";
                $code .= str_repeat("\t", $offset + 2) . '[_:LABEL]' . $val->getLabel() . "[#]\n";
                $code .= str_repeat("\t", $offset + 1) . '[#]' . "\n\n";
            }
            $code .= str_repeat("\t", $offset) . '[#]' . "\n\n";
        }
        $cont = str_replace("{%menuitem%}", $code, $cont);
        return $cont;

    }
}
?>
