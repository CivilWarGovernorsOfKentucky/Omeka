<?php



/**
 * Print the list of files in the DropboxTEI.
 */
function dropboxTEI_list()
{
    echo common('dropboxTEIlist', array(), 'index');
}
/**
 * Get the absolute path to the DropboxTEI "files" directory.
 *
 * @return string
 */
function dropboxTEI_get_files_dir_path()
{
    return DROPBOXTEI_DIR . DIRECTORY_SEPARATOR . 'files';
}

/**
 * Check if the necessary permissions are set for the files directory.
 *
 * The directory must be both writable and readable.
 *
 * @return boolean
 */
function dropboxTEI_can_access_files_dir()
{
    $filesDir = dropboxTEI_get_files_dir_path();
    return is_readable($filesDir) && is_writable($filesDir);
}

/**
 * Get a list of files in the given directory.
 *
 * The files are returned in natural-sorted, case-insensitive order.
 *
 * @param string $directory Path to directory.
 * @return array Array of filenames in the directory.
 */
function dropboxTEI_dir_list($directory)
{
    // create an array to hold directory list
    $filenames = array();

    $iter = new DirectoryIterator($directory);

    foreach ($iter as $fileEntry) {
        if ($fileEntry->isFile()) {
            $filenames[] = $fileEntry->getFilename();
        }
    }

    natcasesort($filenames);

    return $filenames;
}

/**
 * Check if the given file can be uploaded from the dropboxTEI.
 *
 * @throws DropboxTEI_Exception
 * @return string Validated path to the file
 */
function dropboxTEI_validate_file($fileName)
{
    $dropboxTEIDir = dropboxTEI_get_files_dir_path();
    $filePath = $dropboxTEIDir .DIRECTORY_SEPARATOR . $fileName;
    $realFilePath = realpath($filePath);
    // Ensure the path is actually within the dropboxTEI files dir.
    if (!$realFilePath
        || strpos($realFilePath, $dropboxTEIDir . DIRECTORY_SEPARATOR) !== 0) {
        throw new DropboxTEI_Exception(__('The given path is invalid.'));
    }
    if (!file_exists($realFilePath)) {
        throw new DropboxTEI_Exception(__('The file "%s" does not exist or is not readable.', $fileName));
    }
    if (!is_readable($realFilePath)) {
        throw new DropboxTEI_Exception(__('The file "%s" is not readable.', $fileName));
    }
    return $realFilePath;
}

function render_tei_file($filename){

	//query for file-specific stylesheet and display_type. use default from option table if NULL
	$stylesheet = "http://$_SERVER[HTTP_HOST]/files/xslt/content/testCWG.xsl";//tei_display_local_stylesheet($file_id);
	//$displayType = tei_display_local_display($file_id);

	$xp = new XsltProcessor();
	// create a DOM document and load the XSL stylesheet
	$xsl = new DomDocument;

	// import the XSL styelsheet into the XSLT process
	$xsl->load($stylesheet);
	$xp->importStylesheet($xsl);

	//set query parameter to pass into stylesheet
	//$xp->setParameter('', 'display', $displayType);
	//$xp->setParameter('', 'section', $section);

	// create a DOM document and load the XML data
	$xml_doc = new DomDocument;

  $filepath = "http://$_SERVER[HTTP_HOST]/files/original/$filename";
	$xml_doc->load($filepath);

	try {
		if ($doc = $xp->transformToXML($xml_doc)) {
			echo $doc;
		}
	} catch (Exception $e){
		$this->view->error = $e->getMessage();
	}
}
