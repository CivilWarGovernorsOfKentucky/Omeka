<?php
/**
 * @copyright Roy Rosenzweig Center for History and New Media, 2007-2011
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package DropboxTEI
 */

/**
 * Controller for DropboxTEI admin pages.
 *
 * @package DropboxTEI
 */
class DropboxTei_IndexController extends Omeka_Controller_AbstractActionController
{


    /**
     * Front admin page.
     */
    public function indexAction() {

    }

    /**
     * Add action
     *
     * Batch creates items with DropboxTEI files.
     */
    public function addAction()
    {
      $fileNames = $_POST['dropboxTEI-files'];
      $uploadedFileNames = array();
      if ($fileNames) {
          try {
              $uploadedFileNames = $fileNames;
              $fileErrors = $this->_uploadFiles($fileNames);
              if ($fileErrors) {
                  $message = 'Some files were not uploaded. Specific errors for each file follow below:';
                  foreach ($fileErrors as $fileName => $errorMessage) {
                     $message .= "\n- $fileName: $errorMessage";
                  }
                  $this->_helper->flashMessenger($message, 'error');
                  $uploadedFileNames = array_diff($fileNames, array_keys($fileErrors));
              }
          } catch(Exception $e) {
              $this->_helper->flashMessenger($e->getMessage());
              $this->_helper->redirector('index');
          }
      } else {
          $this->_helper->flashMessenger('You must select a file to upload.');
          $this->_helper->redirector('index');
      }
    /*  if ($uploadedFileNames) {
          $message = 'The following files were uploaded:';
          foreach ($uploadedFileNames as $fileName) {
              $message .= "\n- $fileName";
          }
          $this->_helper->flashMessenger($message, 'success');
      }*/

      $this->view->assign(compact('uploadedFileNames', 'fileErrors'));
      //$this->_helper->redirector('index');
  }

    /**
     * Create a new Item for each of the given files.
     *
     * @param array $filenames
     * @return array An array of errors that occurred when creating the
     *  Items, indexed by the filename that caused the error.
     */
    protected function _uploadFiles($fileNames)
    {
        if (!dropboxTEI_can_access_files_dir()) {
            throw new DropboxTEI_Exception('The DropboxTEI files directory must be both readable and writable.');
        }

        $fileErrors = array();
        foreach ($fileNames as $fileName) {
            $filePath = dropboxTEI_validate_file($fileName);
            $item = null;
            try {
                    //echo "this is a tei file".$filePath;
                    $xml_doc = new DomDocument;

                    $teiFile= "http://discovery.civilwargovernors.org/plugins/DropboxTEI/files/".$fileName;
                    $xml_doc->load($teiFile);
                    $xpath = new DOMXPath($xml_doc);

                    $teiNode = $xml_doc->getElementsByTagName('TEI');
                      foreach ($teiNode as $teiNode){
                            $identifier = $teiNode->getAttribute('xml:id');
                      }
                    $fd = $xml_doc->getElementsByTagName("fileDesc");
                    foreach($fd as $fdn){
                        $ts = $fdn->getElementsByTagName("titleStmt");
                        foreach($ts as $tsn){
                            $tn = $tsn->getElementsByTagName("title");
                            $titlev = $tn->item(0)->nodeValue;
                            $dtidv = $tn->item(1)->nodeValue;
                            $title = preg_replace('/\s\s+/', ' ', trim($titlev));
                            $dtid = preg_replace('/\s\s+/', ' ', trim($dtidv));
                        }
                        $ns = $fdn->getElementsByTagName("notesStmt");
                        foreach($ns as $nsn){
                            $rs = $nsn->getElementsByTagName("respStmt");
                            foreach($rs as $rsn){
                              $notes = $nsn->getElementsByTagName("note");
                              foreach($notes as $notev){
                                $natt = $notev->getAttribute("type");
                                if ($natt == "editorial"){
                                  $editorialnote = $notev->item(0)->nodeValue;
                                }
                              }
                            }
                        }
                        $sd = $fdn->getElementsByTagName("sourceDesc");
                        foreach($sd as $sdn){
                            $msd = $sdn->getElementsByTagName("msDesc");
                            foreach($msd as $msdn){
                              $msi = $msdn->getElementsByTagName("msIdentifier");
                              foreach($msi as $msin){
                                $scountryv = $msin->getElementsByTagName("country")->item(0)->nodeValue;
                                $scountry = preg_replace('/\s\s+/', ' ', trim($scountryv));
                                $sstatev = $msin->getElementsByTagName("region")->item(0)->nodeValue;
                                $sstate = preg_replace('/\s\s+/', ' ', trim($sstatev));
                                $scityv = $msin->getElementsByTagName("settlement")->item(0)->nodeValue;
                                $scity = preg_replace('/\s\s+/', ' ', trim($scityv));
                                $rightsholderv = $msin->getElementsByTagName("repository")->item(0)->nodeValue;
                                $rightsholder = preg_replace('/\s\s+/', ' ', trim($rightsholderv));
                                $ispartofv = $msin->getElementsByTagName("collection")->item(0)->nodeValue;
                                $ispartof = preg_replace('/\s\s+/', ' ', trim($ispartofv));
                                $sourcev = $msin->getElementsByTagName("idno")->item(0)->nodeValue;
                                $source = preg_replace('/\s\s+/', ' ', trim($sourcev));
                              }
                            }
                        }
                    }

                    $pd = $xml_doc->getElementsByTagName("profileDesc");
                    foreach($pd as $pdn){
                       $cr = $pdn->getElementsByTagName("creation");
                       foreach($cr as $crn){
                          $datecreatedv = $crn->getElementsByTagName("date")->item(0)->nodeValue;
                          $datecreated = preg_replace('/\s\s+/', ' ', trim($datecreatedv));
                          $isodatecreatedv = $crn->getElementsByTagName("date")->item(0)->getAttribute('when');
                          $isodatecreated = preg_replace('/\s\s+/', ' ', trim($isodatecreatedv));
                          $placev = $crn->getElementsByTagName("placeName")->item(0)->nodeValue;
                          $place = preg_replace('/\s\s+/', ' ', trim($placev));
                       }
                       $tc = $pdn->getElementsByTagName("textClass");
                       foreach($tc as $tcn){
                          $kw = $tcn->getElementsByTagName("keywords");
                          foreach($kw as $kwn){
                            $termv = $kwn->getElementsByTagName("term")->item(0)->nodeValue;
                          //  if ($termv['type']=='genre'){
                              $term = preg_replace('/\s\s+/', ' ', trim($termv));
                          //  }
                          }
                       }
                    }
                    $body = $xml_doc->getElementsByTagName("body");
                    $transcriptionv = $body->item(0)->nodeValue;
                    $transcription = preg_replace('/\s\s+/', ' ', trim($transcriptionv));

                    $dmarray = array();
                    for ($k = 0; $k < $body->length; $k++){
                      $dm = $body->item($k)->getElementsByTagName("date");
                      for ($j = 0; $j < $dm->length; $j++){
                        $datementionedv = $dm->item($j)->getAttribute('when');
                        $datementioned = preg_replace('/\s\s+/', ' ', trim($datementionedv));
                        if (!in_array($datementioned, $dmarray)) {
                            $dmarray[] = $datementioned;
                        }
                      }

                    }

                $itemMetadata = array(
                    'public' => $_POST['dropbox-tei-public'],
                    'featured' => $_POST['dropbox-tei-featured'],
                    'collection_id' => $_POST['dropbox-tei-collection-id']
                        ? $_POST['dropbox-tei-collection-id']
                        : null,
                    'tags' => $_POST['dropbox-tei-tags']
                );
                $elementTexts = array(
                    'CWG Documents' => array(
                          'Document Title' => array(
                            array('text' => $title, 'html' => false)
                        ), 'Accession Number' => array(
                            array('text' => $identifier, 'html' => false)
                        ), 'DocTracker Number' => array(
                            array('text' => $dtid, 'html' => false)
                        ), 'Source Country' => array(
                            array('text' => $scountry, 'html' => false)
                        ), 'Source State' => array(
                            array('text' => $sstate, 'html' => false)
                        ), 'Source City' => array(
                            array('text' => $scity, 'html' => false)
                        ), 'Repository' => array(
                            array('text' => $rightsholder, 'html' => false)
                        ), 'Collection' => array(
                            array('text' => $ispartof, 'html' => false)
                        ), 'Item Location' => array(
                            array('text' => $source, 'html' => false)
                        ), 'Date of Creation' => array(
                            array('text' => $datecreated, 'html' => false)
                        ), 'ISO Date of Creation' => array(
                            array('text' => $isodatecreated, 'html' => false)
                        ), 'Place of Creation' => array(
                            array('text' => $place, 'html' => false)
                        ), 'Document Genre' => array(
                            array('text' => $term, 'html' => false)
                        ), 'Transcription' => array(
                            array('text' => $transcription, 'html' => false)
                        ),
                    ), 'Dublin Core' => array(
                          'Title' => array(
                            array('text' => $title, 'html' => false)
                        ),'Identifier' => array(
                          array('text' => $identifier, 'html' => false)
                      ),
                      )

                );
                if (isset($dmarray)) {
                  $newCWG = array();
                  for ($i = 0; $i < count($dmarray); $i++){
                      $dmvalue = array('text' => $dmarray[$i], 'html' => false);
                      $newCWG[] = $dmvalue;
                  }
                  $elementTexts['CWG Documents']['Dates Mentioned'] = $newCWG;
                }
                if (isset($editorialnote)) {
                      $newdata = array(
                        array('text' => $editorialnote, 'html' => false)
                      );
                      $elementTexts['CWG Documents']['Editorial Note'] = $newdata;
                }
                $fileMetadata = array(
                    'file_transfer_type' => 'Filesystem',
                    'file_ingest_options' => array(
                        'ignore_invalid_files' => false
                    ),
                    'files' => array($filePath)
                );
                $item = insert_item($itemMetadata, $elementTexts, $fileMetadata);
                release_object($item);
                // delete the file from the dropboxTEI folder
                unlink($filePath);
           } catch(Exception $e) {
                release_object($item);
                $fileErrors[$fileName] = $e->getMessage();
            }
        }
        return $fileErrors;
    }
}
