<?php
/**
 * Class: Icon.php
 * Definition: Ayuda para poder usar los iconos llamados desde una clase, para su reutilización para jobs, o templating.
 * Autor: Smartclip
 * Date: Enero 2020
 */
namespace output\html\helpers;

class Icon
{
    static function getIcon($iconName, $family="")
    {
        switch ($iconName) {
            case "usuario": {
                    echo '<i class="bx bx-user"></i>';
                }
                break;
            case "password": {
                    echo '<i class="bx bx-lock"></i>';
                }
                break;
            case "email": {
                    echo '<i class="bx bx-mail-send"></i>';
                }
                break;
            case "enlace": {
                    echo '<i class="bx bx-link-alt"></i>';
                }
                break;
            case "filtro": {
                    echo '<i class="bx bx-filter"></i>';
                }
                break;
            case "papelera": {
                    echo '<i class="bx bx-trash-alt"></i>';
                }
                break;
            case "configuracion": {
                    echo '<i class="bx bx-cog"></i>';
                }
                break;
            case "calendario": {
                    echo '<i class="bx bx-calendar"></i>';
                }
                break;
            case "reloj": {
                    echo '<i class="bx bx-time"></i>';
                }
                break;
            case "mapa": {
                    echo '<i class="bx bx-map"></i>';
                }
                break;
            case "login": {
                    echo '<i class="bx bx-log-in"></i>';
                }
                break;
            case "logout": {
                    echo '<i class="bx bx-log-out"></i>';
                }
                break;
            case "integer": {
                    echo '<i class="bx bx-calculator"></i>';
                }
                break;
            case "estrella": {
                    echo '<i class="bx bx-star"></i>';
                }
                break;
            case "error": {
                    echo '<i class="bx bx-error"></i>';
                }
                break;
            case "quitar": {
                    echo '<i class="bx bx-x"></i>';
                }
                break;
            case "recargar": {
                    echo '<i class="bx bx-revision"></i>';
                }
                break;
            case "expandir": {
                    echo '<i class="bx bx-fullscreen"></i>';
                }
                break;
            case "simbolo-mas":
                    echo '<i class="bx bx-plus"></i>';
                break;
            case "papelera":
                    echo '<i class="bx bx-trash-alt"></i>';
                break;
            case "enviar":
                    echo '<i class="bx bx-send"></i>';                
                break;
            case "collapse": {
                    echo '<i class="bx bx-chevron-down"></i>';
                }
                break;
        }
    }
}
