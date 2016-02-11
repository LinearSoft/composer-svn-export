<?php
/**
 * Created by PhpStorm.
 * User: meej
 * Date: 2/10/2016
 * Time: 4:47 PM
 */

namespace LinearSoft\Composer\SvnExport;


use Composer\Downloader\SvnDownloader;
use Composer\Package\PackageInterface;
use Composer\Util\Svn as SvnUtil;
use Composer\Repository\VcsRepository;

class Downloader extends SvnDownloader
{
    /**
     * {@inheritDoc}
     */
    public function doDownload(PackageInterface $package, $path, $url)
    {
        SvnUtil::cleanEnv();
        $ref = $package->getSourceReference();

        $repo = $package->getRepository();
        if ($repo instanceof VcsRepository) {
            $repoConfig = $repo->getRepoConfig();
            if (array_key_exists('svn-cache-credentials', $repoConfig)) {
                $this->cacheCredentials = (bool) $repoConfig['svn-cache-credentials'];
            }
        }

        $this->io->writeError("    Exporting ".$package->getSourceReference());
        $this->execute($url, "svn export --force", sprintf("%s/%s", $url, $ref), null, $path);
    }
    /**
     * {@inheritDoc}
     */
    public function doUpdate(PackageInterface $initial, PackageInterface $target, $path, $url)
    {
        if (!$this->filesystem->removeDirectory($path)) {
            throw new \RuntimeException('Could not completely delete '.$path.', aborting.');
        }
        //Choo
        $this->doDownload($target,$path,$url);
    }

    /**
     * {@inheritDoc}
     */
    public function getLocalChanges(PackageInterface $package, $path)
    {
        return;
    }

    /**
     * {@inheritDoc}
     */
    protected function cleanChanges(PackageInterface $package, $path, $update)
    {
        return;
    }

    /**
     * {@inheritDoc}
     */
    protected function getCommitLogs($fromReference, $toReference, $path)
    {
        return null;
    }

    protected function discardChanges($path)
    {
        return;
    }
}