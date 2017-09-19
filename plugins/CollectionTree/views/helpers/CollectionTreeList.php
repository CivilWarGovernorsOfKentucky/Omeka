<?php
/**
 * Collection Tree
 *
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * @package CollectionTree\View\Helper
 */
class CollectionTree_View_Helper_CollectionTreeList extends Zend_View_Helper_Abstract
{
    /**
     * Recursively build a nested HTML unordered list from the provided
     * collection tree.
     *
     * @see CollectionTreeTable::getCollectionTree()
     * @see CollectionTreeTable::getAncestorTree()
     * @see CollectionTreeTable::getDescendantTree()
     * @param array $collectionTree
     * @param bool $linkToCollectionShow
     * @return string
     */
    public function collectionTreeList($collectionTree, $linkToCollectionShow = true)
    {
        if (!$collectionTree) {
            return;
        }
        $collectionTable = get_db()->getTable('Collection');

        $html = '';

        foreach ($collectionTree as $collection) {

            $html .= '<div class="parental">';
            if ($linkToCollectionShow && !isset($collection['current']) && $collection['parent_collection_id']!=0) {
                $collectionId = $collectionTable->find($collection['id']);
                //if ($collectionImage = record_image($collectionId, 'square_thumbnail')){
                //  $html .= '<div class="item-img">'.link_to($collectionId, 'show', $collectionImage).'</div>';
              //  }
                $html .= '<div class="children"><h3>'.link_to_collection(null, array(), 'show', $collectionId).'</h3></div>';
            } else {
                //$html .= '';//$collection['name'] ? $collection['name'] : '[Untitled]';
            }

            $html .= $this->collectionTreeList($collection['children'], $linkToCollectionShow);

            $html .= '</div>';

        }
        //$html .= '</div>';
        return $html;
    }
}
