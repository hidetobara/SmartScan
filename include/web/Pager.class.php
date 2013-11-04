<?php


class Pager
{
	private $items;

	public $pageCount = 100;
	public $currentItems;

	public $previousPage;
	public $currentPage;
	public $nextPage;


	public function __construct( array $items, $count = 0 )
	{
		$this->items = $items;
		if( $count > 0 ) $this->pageCount = $count;

		$this->trim( 1 );
	}

	private function clear()
	{
		$this->currentItems = array();
		$this->previousPage = null;
		$this->currentPage = null;
		$this->nextPage = null;
	}

	public function trim( $page )
	{
		$this->clear();
		if( $this->pageCount * $page > count($this->items) ) return null;

		$this->currentItems = array_slice(( $this->items ), ($page-1) * $this->pageCount, $this->pageCount );
		$this->currentPage = $page;
		if( $page >= 2 ) $this->previousPage = $page - 1;
		if( $page < count($this->items) ) $this->nextPage = $page + 1;
		return $this->currentItems;
	}
}