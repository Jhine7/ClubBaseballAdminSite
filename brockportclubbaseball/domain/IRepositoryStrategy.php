<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 2:24 PM
 */

namespace domain;


interface IRepositoryStrategy {
    function matching(array $criteria, $className);
    function soleMatch(array $criteria, $className);
}
?>