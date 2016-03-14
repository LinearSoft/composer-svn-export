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
    protected function initialize()
    {
        parent::initialize();
        $repoData = $this->loadDataFromServer();

        foreach ($repoData as $package) {
            $this->addPackage($this->createPackage($package, 'Composer\Package\CompletePackage'));
        }
    }

    protected function createPackage(array $data, $class = 'Composer\Package\CompletePackage')
    {
        if(isset($data['source']['type']) && $data['source']['type'] === 'svn') $data['source']['type'] = 'svn-export';
        return parent::createPackage($data, $class);
    }
}