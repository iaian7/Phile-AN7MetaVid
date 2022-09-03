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
  * version 0.2 modified 2022.01.01
  *
  * @author		John Einselen
  * @link		http://iaian7.com
  * @license	http://opensource.org/licenses/MIT
  * @package	Phile\Plugin\An7\MetaVid
  *
  */

class Plugin extends AbstractPlugin implements EventObserverInterface {

	protected $events = ['after_read_file_meta' => 'processMeta'];

	protected function processMeta($data)
	{
		(isset($data['meta']['title']))? $title = str_replace(' ', '', $data['meta']['title']): $title = uniqid();
		$openclass = $this->settings['open_class'];
		$closeclass = $this->settings['close_class'];
		$overlayclass = $this->settings['overlay_class'];
		$iframeclass = $this->settings['iframe_class'];
		$metatags = explode(',', $this->settings['meta_tags']);
		$metasuffix = $this->settings['meta_suffix'];

		foreach($metatags as $metatag) {
			if (isset($data['meta'][$metatag])) { // If this specific metatag exists
				$id = $data['meta'][$metatag];
				$thumbnail = $this->getVimeoThumbnail($id);
				$data['meta'][$metatag.$metasuffix] = $this->videoOverlayCode($title."_".$metatag, $id, $thumbnail, $openclass, $closeclass, $overlayclass, $iframeclass); // Create the embed code and put it in a new meta tag
			}
		}
	}

	// Generic function for getting Vimeo thumbnail URLs
	private function getVimeoThumbnail($id = '') {
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

	private function videoOverlayCode($tag, $id, $thumbnail, $openclass, $closeclass, $overlayclass, $iframeclass) {
		if (!isset($id)) return null; // return nothing if no content is available for processing

		$content = <<<EOD
			<a class="$openclass" href="#$tag" onclick="lightboxShow('$tag'); $tag.play(); return false;" style="background-image: url($thumbnail)"></a>
			<div class="$overlayclass" id="$tag">
			<a class="$closeclass" href="#!" onclick="$tag.pause(); lightboxHide('$tag'); return false;"></a>
			<iframe class="$iframeclass" src="https://player.vimeo.com/video/$id" allowfullscreen onload="$tag=new Vimeo.Player(this)"></iframe>
			</div>
		EOD;

		return $content; // return processed data
	}
}
