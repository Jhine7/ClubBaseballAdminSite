<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Justin
 * Date: 5/26/13
 * Time: 5:07 PM
 * To change this template use File | Settings | File Templates.
 */
namespace view;

interface IView {
    function getViewFile();
    function getViewElements();
    function getCommandName();
}
?>