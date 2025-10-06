<?php
// 代码生成时间: 2025-10-07 02:18:26
 * It is designed to be easily understandable, maintainable, and extensible.
 *
 * @package    TreeComponent
 * @author     Your Name
 * @version    1.0
 */

use Phalcon\Mvc\User\Component;

class TreeComponent extends Component
{
    /**
     * Recursively builds a tree structure from a flat array.
     *
     * @param array $elements
     * @param int $parentId
     * @return array
     */
    public function buildTree(array $elements, $parentId = 0)
    {
        $tree = [];
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if (!empty($children)) {
                    $element['children'] = $children;
                }
                $tree[] = $element;
            }
        }
        return $tree;
    }

    /**
     * Finds a node by its ID in the tree.
     *
     * @param array $tree
     * @param int $id
     * @return mixed
     */
    public function findNode(array $tree, $id)
    {
        foreach ($tree as $node) {
            if ($node['id'] == $id) {
                return $node;
            } elseif (isset($node['children'])) {
                $found = $this->findNode($node['children'], $id);
                if ($found) {
                    return $found;
                }
            }
        }
        return null;
    }

    /**
     * Adds a new node to the tree.
     *
     * @param array $nodeData
     * @param array $tree
     * @return array
     */
    public function addNode(array $nodeData, array $tree)
    {
        if (!isset($nodeData['parent_id'])) {
            throw new \Exception('Parent ID is required for the new node.');
        }

        $parentNode = $this->findNode($tree, $nodeData['parent_id']);
        if (!$parentNode) {
            throw new \Exception('Parent node not found.');
        }

        $nodeData['children'] = [];
        $parentNode['children'][] = $nodeData;
        return $tree;
    }

    /**
     * Removes a node from the tree.
     *
     * @param int $id
     * @param array $tree
     * @return array
     */
    public function removeNode($id, array $tree)
    {
        foreach ($tree as $key => $node) {
            if ($node['id'] == $id) {
                unset($tree[$key]);
                return $tree;
            } elseif (isset($node['children'])) {
                $updatedTree = $this->removeNode($id, $node['children']);
                if (empty($updatedTree)) {
                    unset($tree[$key]['children']);
                } else {
                    $tree[$key]['children'] = $updatedTree;
                }
            }
        }
        return $tree;
    }
}
