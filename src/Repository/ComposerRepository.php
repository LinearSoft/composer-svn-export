<?php
/**
 * Created by PhpStorm.
 * User: meej
 * Date: 2/11/2016
 * Time: 3:56 PM
 */

namespace LinearSoft\Composer\SvnExport\Repository;

use Composer\Repository\ComposerRepository as CrComposerRepository;

class ComposerRepository extends CrComposerRepository
{
    public function findPackage($name, $constraint)
    {
        $name = str_replace('svn-export-','',$name);
        return parent::findPackage($name, $constraint);
    }

    public function findPackages($name, $constraint = null)
    {
        $name = str_replace('svn-export-','',$name);
        return parent::findPackages($name, $constraint);
    }

    public function search($query, $mode = 0)
    {
        $query = str_replace('svn-export-','',$query);
        return parent::search($query, $mode);
    }

    protected function createPackage(array $data, $class)
    {
        if(isset($data['source']['type'])) $data['source']['type'] = 'svn-export';
        return parent::createPackage($data, $class);
    }
}