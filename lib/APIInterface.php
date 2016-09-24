<?php

namespace IMVDb;

interface APIInterface
{
	public function searchVideos($query='', $start=0, $limit=10);
	public function searchEntities($query='', $start=0, $limit=10);
	public function video($id=0, $includes=array('sources'));
	public function entity($id=0, $includes=array(''));
}