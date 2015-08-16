<?php
require_once( 'App/ZipArchive/pclzip.lib.php' );

class CZipArchive
{
    protected $m_arrmixValidFiles;

    function __construct() {
        return true;
    }

    public function makeZipFile( $arrmixFiles, $strDestination, $boolIsOverwrite = false ) {

        //Check if file is exist already!!!
        if( true == file_exists( $strDestination ) && false == $boolIsOverwrite ) {
            echo( 'already exist' );
            return false;
        }

        $this->m_arrmixValidFiles = array();

        if( true == is_array( $arrmixFiles ) ) {
            foreach( $arrmixFiles as $strOneFile ) {
                if( true == $strOneFile ) {
                    $this->m_arrmixValidFiles[] = $strOneFile;
                }
            }
        }

        if( 0 >= count( $this->m_arrmixValidFiles ) ) {
            echo( 'no files found' );
            return false;
        }

        $objZip = new PclZip( $strDestination );
        /*if( true !== $objZip->create( $strDestination, $boolIsOverwrite ? ZipArchive::OVERWRITE : ZipArchive::CREATE ) ) {
            echo( 'Zip cannot created' );
            return false;
        }*/

        foreach( $this->m_arrmixValidFiles as $strOneFile ) {
            $objZip->add( $strOneFile );
        }

        return file_exists( $strDestination );
    }
}


/*try
{
if ($objZip->open($strFile, ZipArchive::CREATE | ZipArchive::OVERWRITE))
{
exit('Sorry!! Zip cannot be created.');
}

$objZip->addFile($strFile, $strFileName);

$objZip->close();
} catch (Exception $objException ) {
    echo '<pre>';
    print_r($objZip);
    print_r($objException);
    echo '<pre>';
    exit;
}

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);
header("Content-Type: application/zip");
header("Content-Disposition: attachment; filename=" . basename($$strFileName) . ";");
header("Content-Transfer-Encoding: binary");
header("Content-Length: " . filesize($strFileName));
readfile($strFileName);

//deletes file when its done...
unlink($strFileName);
exit;*/
?>