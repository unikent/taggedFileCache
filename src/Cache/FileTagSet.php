<?php
namespace Unikent\Cache;

use Illuminate\Cache\TagSet;
use Unikent\Jobs\FlushTagFromFileCacheJob;

class FileTagSet extends TagSet{

	protected static $driver = 'tfile';
	
	/**
	 * Get the tag identifier key for a given tag.
	 *
	 * @param  string  $name
	 * @return string
	 */
	public function tagKey($name)
	{
		return 'cache_tags' . $this->store->separator . preg_replace('/[^\w\s\d\-_~,;\[\]\(\).]/', '~', $name);
	}


	/**
	 * Reset the tag and return the new tag identifier.
	 *
	 * @param  string  $name
	 * @return string
	 */
	public function resetTag($name)
	{

		$oldID = $this->store->get($this->tagKey($name));

		if ($oldID!==false && $oldID!==null){
			$job = new FlushTagFromFileCacheJob($oldID, static::$driver);
			if(!empty($this->store->queue)){
				$job->onQueue($this->store->queue);
			}
			dispatch($job);
		}

		return parent::resetTag($name);
	}

}
