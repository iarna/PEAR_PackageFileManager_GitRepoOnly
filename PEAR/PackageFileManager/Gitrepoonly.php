<?php
/**
 * Generate PEAR file lists from committed and staged files in a Git repo.
 *
 * @category  PEAR
 * @package   PEAR_PackageFileManager_Gitrepoonly
 * @author    Becca Turner <turner@mikomi.org>
 * @license   New BSD, Revised
 * @version   Release: 1.0.0
 * @link      https://github.com/iarna/PEAR_PackageFileManager_GitRepoOnly
 * @since     File available since Release 1.0.0
 */

require_once 'PEAR/PackageFileManager/File.php';

/**
 * Generate a file list from a Git working copy.
 *
 * @category  PEAR
 * @package   PEAR_PackageFileManager_Gitrepoonly
 * @author    Becca Turner <turner@mikomi.org>
 * @license   New BSD, Revised
 * @version   Release: 1.0.0
 * @link      https://github.com/iarna/PEAR_PackageFileManager_GitRepoOnly
 * @since     Class available since Release 1.0.0
 */
class PEAR_PackageFileManager_Gitrepoonly extends PEAR_PackageFileManager_File
{
    function PEAR_PackageFileManager_Gitrepoonly($options)
    {
        parent::PEAR_PackageFileManager_File($options);
    }
    
    function getFileList()
    {
        $this->_options['ignore'][] = '.gitignore';
        return parent::getFileList();
    }
    
    function dirList($directory)
    {
        $git = $this->_findGitRootDir($directory);
        
        if ( ! $git ) {
            throw new Exception("Can't collect filelist, '$directory' is not in a Git repository");
        }
        $ignore = $this->_options['ignore'];
        
        $ret = array();
        foreach ( explode("\n",trim(`git ls-files "$directory"`)) as $de ) {
            $entry = basename($de);
            /// From PEAR_PackageFileManager_File
            // BEGIN
            // if include option was set, then only pass included files
            if ($this->ignore[0] && $this->_checkIgnore($entry, $de, 0)) {
                continue;
            }
            // if ignore option was set, then only pass included files
            if ($this->ignore[1] && $this->_checkIgnore($entry, $de, 1)) {
                continue;
            }
            $ret[] = implode( DIRECTORY_SEPARATOR, array( $directory, $de ) );
            // END
        }

        /// From PEAR_PackageFileManager_File
        // BEGIN
        usort($ret, array($this, 'mystrucsort'));
        return $ret;
        // END
    }

    /// Adapted from https://github.com/armen/PEAR_PackageFileManager_Git
    // BEGIN
    function _findGitRootDir($directory)
    {
        $directory = realpath($directory);

        if ( ! file_exists($directory.DIRECTORY_SEPARATOR.'.git') and $directory != DIRECTORY_SEPARATOR ) {
            $directory = realpath($directory.DIRECTORY_SEPARATOR.'..');
            return $this->_findGitRootDir($directory);
        }

        if ( file_exists($directory.DIRECTORY_SEPARATOR.'.git') ) {
            return $directory.DIRECTORY_SEPARATOR;
        }

        return false;
    }
    // END
}
