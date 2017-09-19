<?php

/**
 * @version $Id$
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright Center for History and New Media, 2010
 * @package DropboxTEI
 */

/**
 * DropboxTEI plugin class
 *
 * @copyright Center for History and New Media, 2010
 * @package DropboxTEI
 */
define('DROPBOXTEI_DIR',dirname(__FILE__));

require_once DROPBOXTEI_DIR.'/helpers/DropboxTEIFunctions.php';

class DropboxTEIPlugin extends Omeka_Plugin_AbstractPlugin
{
    // Define Hooks
    protected $_hooks = array(
        'initialize',
        'after_save_item',
        'admin_items_form_files',
        'define_acl',
        'public_items_show'
    );

    //Define Filters
    protected $_filters = array(
        'admin_navigation_main',
    );

    public function hookInitialize()
    {
        add_translation_source(dirname(__FILE__) . '/languages');
    }
    /**
     * DropboxTEI admin navigation filter
     */
    public function filterAdminNavigationMain($nav)
    {

        $nav[] = array(
            'label'   => __('Dropbox TEI'),
            'uri'     => url(
                    array(
                        'module'=>'dropbox-tei',
                        'controller'=>'index',
                        'action'=>'index',
                        ), 'default'
                    ),
            'resource' => 'DropboxTei_Index'
        );

        return $nav;
    }

    /*
     * Define ACL entry for DropboxTEI controller.
     */
    public function hookDefineAcl($args)
    {
        $acl = $args['acl'];
        $acl->addResource('DropboxTei_Index');
    }

    /**
     * Display the dropboxTEI files list on the  itemf form.
     * This simply adds a heading to the output
     */
    public function hookAdminItemsFormFiles()
    {
        echo '<h3>' . __('Add TEI Documents') . '</h3>';
        dropboxTEI_list();
    }

    /**
     * Display the dropboxTEI transcription on the public item show page
     */
    public function hookPublicItemsShow($filename){
        echo render_tei_file($filename);

    }

    public function hookAfterSaveItem($args)
    {
        $item = $args['record'];
        $post = $args['post'];

        if (!($post && isset($post['dropboxTEI-files']))) {
            return;
        }

        $fileNames = $post['dropboxTEI-files'];
        if ($fileNames) {
            if (!dropboxTEI_can_access_files_dir()) {
                throw new DropboxTEI_Exception(__('The DropboxTEI files directory must be both readable and writable.'));
            }
            $filePaths = array();
            foreach($fileNames as $fileName) {
                $filePaths[] = dropboxTEI_validate_file($fileName);
            }

            $files = array();

            $db = get_db();
            $files = $item->Files;
            foreach($files as $file){
                //declare DomDocument, load the TEI file, and declare xpath. this taken from TEI import routine, tweaked
                $xml_doc = new DomDocument;
                $teiFile = $file->getWebPath('original');
                $xml_doc->load($teiFile);
                $xpath = new DOMXPath($xml_doc);
                $teiNode = $xml_doc->getElementsByTagName('TEI');
                foreach ($teiNode as $teiNode){
                    $tei_id = $teiNode->getAttribute('xml:id');
                }
                //add the file to the tei_display_config table if it isn't already there
                echo "got it";
                $configs = $db->getTable('TeiDisplayText')->findAll();
                $configTeiIds = array();
                foreach ($configs as $config){
                    $configTeiIds[] = $config['tei_id'];
                }
                if (!in_array(trim($tei_id), $configTeiIds)){
                    $db->insert('TeiDisplayText', array('item_id'=>$item->id, 'file_id'=>$file->id, 'tei_id'=>trim($tei_id)));
                }
                //get element_ids
                $dcSetId = $db->getTable('ElementSet')->findByName('Dublin Core')->id;
                $dcElements = $db->getTable('Element')->findBySql('element_set_id = ?', array($dcSetId));
				        $dc = array();
                //write DC element names and ids to new array for processing
                foreach ($dcElements as $dcElement){
					          $dc[] = $dcElement['name'];
				        }
                //map TEI to DC taken from TEIDisplay plugin
	 			        //based on CDL encoding guidelines: http://www.cdlib.org/groups/stwg/META_BPG.html#d52e344 tweaked SH: this bit should walk the schema
                foreach ($dc as $name){
                    if ($name == 'Identifier'){
                        $queries == array('//*[local-name()="teiHeader"]/*[local-name()="fileDesc"]/*[local-name()="titleStmt"]/*[local-name()="title" and @type="main"]');
                    } elseif ($name == 'Title'){
                        $queries == array('//*[local-name()="teiHeader"]/*[local-name()="fileDesc"]/*[local-name()="titleStmt"]/*[local-name()="title" and @type="parallel"]');
                    } elseif ($name == 'Rights Holder'){
                        $queries == array('//*[local-name()="teiHeader"]/*[local-name()="fileDesc"]/*[local-name()="sourceDesc"]/*[local-name()="msDesc"]/*[local-name()="msIdentifier"]/*[local-name()="repository"]');
                    } elseif ($name == 'Is Part Of'){
                        $queries == array('//*[local-name()="teiHeader"]/*[local-name()="fileDesc"]/*[local-name()="sourceDesc"]/*[local-name()="msDesc"]/*[local-name()="msIdentifier"]/*[local-name()="collection"]');
                    } elseif ($name == 'Source'){
                        $queries == array('//*[local-name()="teiHeader"]/*[local-name()="fileDesc"]/*[local-name()="sourceDesc"]/*[local-name()="msDesc"]/*[local-name()="msIdentifier"]/*[local-name()="idno"]');
                    } elseif ($name == 'Date Created'){
                        $queries == array('//*[local-name()="teiHeader"]/*[local-name()="profileDesc"]/*[local-name()="creation"]/*[local-name()="date"]');
                    } elseif ($name == 'Spatial Coverage'){
                        $queries == array('//*[local-name()="teiHeader"]/*[local-name()="profileDesc"]/*[local-name()="creation"]/*[local-name()="place"]');
                    } elseif ($name == 'Type'){
                        $queries == array('//*[local-name()="teiHeader"]/*[local-name()="profileDesc"]/*[local-name()="textClass"]/*[local-name()="keywords"]/*[local-name()="term" and @type="genre"]');
                    } elseif ($name == 'Temporal Coverage'){
                        $queries == array('//*[local-name()="text"]/*[local-name()="body"]/*[local-name()="date"]/@when');
                    }
                    //get item element texts
                    $ielement = $item->getElementByNameAndSetName($name, 'Dublin Core');
                    $ielementTexts = $item->getTextsByElement($ielement);
                    $itexts = array();
                    foreach ($ielementTexts as $ielementText){
						            $itexts[] = $ielementText['text'];
					          }
                    //get file element texts
                    $felement = $file->getElementByNameAndSetName($name, 'Dublin Core');
					          $felementTexts = $file->getTextsByElement($felement);
					          $ftexts = array();
					          foreach ($felementTexts as $felementText){
						            $ftexts[] = $felementText['text'];
					          }
                    //set element texts for item and file
					          foreach ($queries as $query){
						            $nodes = $xpath->query($query);
                        foreach($nodes as $node){
                            //see if that text is already set and don't put in any blank or null fields
                            $value = preg_replace('/\s\s+/', ' ', trim($node->nodeValue));

                            //item
                            $item->addTextForElement($ielement, trim($value));

                            //file
                            if (!in_array(trim($value), $ftexts) && trim($value) != '' && trim($value) != NULL){
								                      $file->addTextForElement($felement, trim($value));
						                }
                        }
                    }
                    $item->saveElementTexts();
				            $file->saveElementTexts();
                }

            //try {
                //$files = insert_files_for_item($item, 'Filesystem', $filePaths, array('file_ingest_options'=> array('ignore_invalid_files'=>false)));
        /*  } catch (Omeka_File_Ingest_InvalidException $e) {
                    release_object($files);
                    $item->addError('DropboxTEI', $e->getMessage());
                    return;
                } catch (Exception $e) {
                    release_object($files);
                    throw $e;
                }
                release_object($files);*/
          }
          // delete the files
          foreach($filePaths as $filePath) {
                try {
                    unlink($filePath);
                } catch (Exception $e) {
                    throw $e;
                }
            }
        }
    }
}
