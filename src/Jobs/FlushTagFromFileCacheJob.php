<?php

namespace Unikent\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class FlushTagFromFileCacheJob implements ShouldQueue
{
	/*
	|--------------------------------------------------------------------------
	| Queueable Jobs
	|--------------------------------------------------------------------------
	|
	| This job base class provides a central location to place any logic that
	| is shared across all of your jobs. The trait included with the class
	| provides access to the "queueOn" and "delay" queue helper methods.
	|
	*/

	use InteractsWithQueue, Queueable, SerializesModels;

	protected $tagIds;
	protected $driver;

	/**
	 * Create a new job instance.
	 *
	 * @param $ids array of tagIds to find and purge
	 */
    public function __construct( $ids , $driver = 'tfile')
    {
        $this->tagIds = is_array($ids)?$ids:[$ids];
	$this->driver = $driver;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->tagIds as $id){
		app('cache')->driver($this->driver)->flushOldTag($id);
	}
    }
}
