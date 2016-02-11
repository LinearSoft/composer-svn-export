<?php
/**
 * Created by PhpStorm.
 * User: meej
 * Date: 2/10/2016
 * Time: 3:18 PM
 */

namespace LinearSoft\Composer\SvnExport;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Util\Filesystem;
use Composer\Util\ProcessExecutor;

class Plugin implements PluginInterface
{

    /**
     * @inheritdoc
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        //Extend download manager
        $dm = $composer->getDownloadManager();
        $executor = new ProcessExecutor($io);
        $fs = new Filesystem($executor);
        $config = $composer->getConfig();
        $dm->setDownloader('svn-export', new Downloader($io, $config, $executor, $fs));

        //Extend RepositoryManager Classes
        $rm = $composer->getRepositoryManager();
        $rm->setRepositoryClass('svn-export', 'Composer\Repository\VcsRepository');
    }

}