<?php
/**
 * Plugin class
 */

namespace Phile\Plugin\An7\MetaVid;

use Phile\Gateway\EventObserverInterface;
use Phile\Plugin\AbstractPlugin;
use Phile\Exception;

/**
  * MetaVid
  * version 0.1 modified 2022.01.01
  *
  * @author		John Einselen
  * @link		http://iaian7.com
  * @license	http://opensource.org/licenses/MIT
  * @package	Phile\Plugin\An7\MetaVid
  *
  */

class Plugin extends AbstractPlugin implements EventObserverInterface
{

	protected $events = ['after_read_file_meta' => 'processMeta'];

	protected function processMeta($data)
	{
		$metatags = explode(',', $this->settings['meta_tags']);
		$identifier = $this->settings['meta_title'];
		
		foreach($metatags as $metatag) {
			if (isset($data['meta'][$metatag])) { // If this specific metatag exists
				$data['meta'][$metatag."-img"] = $this->get_image($data['meta'][$metatag]); // Create the video embed item
				$data['meta'][$metatag."-vid"] = $this->embed_vid($data['meta'][$metatag]); // Create the video embed item
			}
		}
	}

	// Generic function for getting thumbnails for Vimeo videos
	function getVimeoThumbnail($id = '') {
		if (strlen($id) < 6) {
			return FALSE;
		} else {
			$id = trim($id);
		}
		$data = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$id.php"));
		if (is_array( $data ) && count( $data ) > 0 ) {
			return $data[0]['thumbnail_large'];
		} else {
			return FALSE;
		}
	}

	// Specific function for returning the thumbnail as a JPG URL
	private function get_image($content) {
		if (!isset($content)) return null; // return nothing if no content is available for processing
		$content = getVimeoThumbnail($content).".jpg";
		return $content; // return processed data
	}

	private function embed_vid($content) {
		if (!isset($content)) return null; // return nothing if no content is available for processing
		$content = '<iframe class="animate" src="https://player.vimeo.com/video/'.$content.'" allowfullscreen onload="'.uniqid().'=new Vimeo.Player(this)"></iframe>';
		return $content; // return processed data
	}
}
	