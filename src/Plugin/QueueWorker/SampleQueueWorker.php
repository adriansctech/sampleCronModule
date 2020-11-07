<?php
namespace Drupal\sample\Plugin\QueueWorker;
use Drupal\Core\Queue\QueueWorkerBase;
/**
 * Sample Queue Worker.
 *
 * @QueueWorker(
 *   id = "sample_queue_worker",
 *   title = @Translation("Sample Queue Worker: Change title of books"),
 *   cron = {"time" = 180}
 * )
 */
class SampleQueueWorker extends QueueWorkerBase
{

    /**
     * {@inheritdoc}
     */
    public function processItem($data)
    {
        // Process Item.      
        try {
            switch ($data['op']) {
            case 'title':
                $this->changeTitle($data['nid']);
                break;
            case 'unpublish':
                $this->unPublish($data['nid']);
                break;
            }
      
        }catch (\Exception $e) {
            watchdog_exception('sample', $e);
              return;
        }
    }

    /**
     * This function change tite of nodes.
     *
     * @param string $data
     *  The id of book.   
     */
    private function _changeTitle($data)
    {        
        $book = \Drupal\node\Entity\Node::load($data);
        if (empty($book)) {
            return;
        }
        $book->setTitle("has vuelto a cambiar de nombre otra vez mas");
        $book->save();
    }

    /**
     * This function unpublished all nodes.
     *
     * @param string $data
     *  The id of book.
     */
    private function _unPublish($data)
    {
        $book = \Drupal\node\Entity\Node::load($data);
        if (empty($book)) {
            return;
        }
        $book->setPublished(false);        
        $book->save();    
    }
}