<?php
/**
 * page.
 * 
 * 
 * 
 * @author samson.zhou <samson.zhou@newbiiz.com>
 * @package framework
 * @date 2010-09-14
 */
class CPages extends CComponents
{
	/**
	 * The default page size.
	 */
	const DEFAULT_PAGE_SIZE=10;
	
	/**
	 * @var string name of the GET variable storing the current page index. Defaults to 'page'.
	 */
	public $pageVar='page';
	
	/**
	 * @var string the route (controller ID and action ID) for displaying the paged contents.
	 * Defaults to empty string, meaning using the current route.
	 */
	public $route='';
	
	/**
	 * @var array the additional GET parameters (name=>value) that should be used when generating pagination URLs.
	 * Defaults to null, meaning using the currently available GET parameters.
	 * @since 1.0.9
	 */
	public $params;

	private $_pageSize=self::DEFAULT_PAGE_SIZE;
	private $_itemCount=0;
	private $_currentPage;

	/**
	 * @var string 'LINK' OR 'JS'
	 */
	private $_activeWay = 'LINK';

	/**
	 * Constructor.
	 * @param integer total number of items.
	 * @since 1.0.1
	 */
	public function __construct($itemCount=0)
	{
		$this->setItemCount($itemCount);
	}

	/**
	 * @param string active way
	 */
	public function setActiveWay($activeWay)
	{
		$this->_activeWay = $activeWay;
	}

	/**
	 * @return integer number of items in each page. Defaults to 10.
	 */
	public function getPageSize()
	{
		return $this->_pageSize;
	}

	/**
	 * @param integer number of items in each page
	 */
	public function setPageSize($value)
	{
		if(($this->_pageSize=$value)<=0)
			$this->_pageSize=self::DEFAULT_PAGE_SIZE;
	}

	/**
	 * @return integer total number of items. Defaults to 0.
	 */
	public function getItemCount()
	{
		return $this->_itemCount;
	}

	/**
	 * @param integer total number of items.
	 */
	public function setItemCount($value)
	{
		if(($this->_itemCount=$value)<0)
			$this->_itemCount=0;
	}

	/**
	 * @return integer number of pages
	 */
	public function getPageCount()
	{
		return (int)(($this->_itemCount+$this->_pageSize-1)/$this->_pageSize);
	}

	/**
	 * @param boolean whether to recalculate the current page based on the page size and item count.
	 * @return integer the zero-based index of the current page. Defaults to 0.
	 */
	public function getCurrentPage($recalculate=true)
	{
		if($this->_currentPage===null || $recalculate)
		{
			if(isset($_GET[$this->pageVar]))
			{
				$this->_currentPage=(int)$_GET[$this->pageVar]-1;
				$pageCount=$this->getPageCount();
				if($this->_currentPage>=$pageCount)
					$this->_currentPage=$pageCount-1;
				if($this->_currentPage<0)
					$this->_currentPage=0;
			}
			else
				$this->_currentPage=0;
		}
		return $this->_currentPage;
	}

	/**
	 * @param integer the zero-based index of the current page.
	 */
	public function setCurrentPage($value)
	{
		$this->_currentPage=$value;
		$_GET[$this->pageVar]=$value+1;
	}

	/**
	 * Creates the URL suitable for pagination.
	 * This method is mainly called by pagers when creating URLs used to
	 * perform pagination. The default implementation is to call
	 * the controller's createUrl method with the page information.
	 * You may override this method if your URL scheme is not the same as
	 * the one supported by the controller's createUrl method.
	 * @param CController the controller that will create the actual URL
	 * @param integer the page that the URL should point to. This is a zero-based index.
	 * @return string the created URL
	 */
	public function createPageUrl($page)
	{
		$params=$this->params===null ? $_GET : $this->params;
		$routeName = Nbt::app()->request->routeName;
		$route = isset( $params[$routeName] ) ? $params[$routeName] : null;
		if($page>0) // page 0 is the default
		{
			$params[$this->pageVar]=$page+1;			
		}
		else
		{
			unset($params[$this->pageVar]);
		}
		unset($params[$routeName]);
		return Nbt::app()->createUrl($route,$params);
	}

	/**
	 * Applies LIMIT and OFFSET to the specified query criteria.
	 * @param CDbCriteria the query criteria that should be applied with the limit
	 * @since 1.0.1
	 */
	public function applyLimit($criteria)
	{
		$criteria->limit=$this->pageSize;
		$criteria->offset=$this->currentPage*$this->pageSize;
	}
	
	const CSS_FIRST_PAGE='first';
	const CSS_LAST_PAGE='last';
	const CSS_PREVIOUS_PAGE='previous';
	const CSS_NEXT_PAGE='next';
	const CSS_INTERNAL_PAGE='page';
	const CSS_HIDDEN_PAGE='disabled_tnt_pagination';
	const CSS_SELECTED_PAGE='zzjs';

	/**
	 * @var integer maximum number of page buttons that can be displayed. Defaults to 10.
	 */
	public $maxButtonCount=10;
	/**
	 * @var string the text label for the next page button. Defaults to 'Next &gt;'.
	 */
	public $nextPageLabel;
	/**
	 * @var string the text label for the previous page button. Defaults to '&lt; Previous'.
	 */
	public $prevPageLabel;
	/**
	 * @var string the text label for the first page button. Defaults to '&lt;&lt; First'.
	 */
	public $firstPageLabel;
	/**
	 * @var string the text label for the last page button. Defaults to 'Last &gt;&gt;'.
	 */
	public $lastPageLabel;
	/**
	 * @var string the text shown before page buttons. Defaults to 'Go to page: '.
	 */
	public $header;
	/**
	 * @var string the text shown after page buttons.
	 */
	public $footer='';
	/**
	 * @var mixed the CSS file used for the widget. Defaults to null, meaning
	 * using the default CSS file included together with the widget.
	 * If false, no CSS file will be used. Otherwise, the specified CSS file
	 * will be included when using this widget.
	 */
	public $cssFile;
	/**
	 * @var array HTML attributes for the pager container tag.
	 */
	public $htmlOptions=array();
	
	public $id = 0;
	
	public function getId()
	{
		$this->id++;
		return "pager_{$this->id}";
	}

	public function setMaxButtonCount( $_intNum = 10 )
	{
		$this->maxButtonCount = $_intNum;
	}


	/**
	 * Executes the widget.
	 * This overrides the parent implementation by displaying the generated page buttons.
	 */
	public function getPageNav()
	{
		$total = "共{$this->_itemCount}条记录&nbsp;&nbsp;&nbsp;&nbsp;";
		
		if($this->nextPageLabel===null)
			$this->nextPageLabel='下页';
		if($this->prevPageLabel===null)
			$this->prevPageLabel='上页';
		if($this->firstPageLabel===null)
			$this->firstPageLabel='首页';
		if($this->lastPageLabel===null)
			$this->lastPageLabel='尾页';
		// 修改：关闭页数提醒
		if($this->header===null && false)
			$this->header="<label>第".($this->getCurrentPage()+1)."页&nbsp;|&nbsp;共".$this->getPageCount()."页 </label>";
		
		$buttons=$this->createPageButtons();		
		
		$htmlOptions=$this->htmlOptions;
		$id = isset($htmlOptions['id']) ? $htmlOptions['id'] : $this->getId();
		$class = isset($htmlOptions['class']) ? $htmlOptions['class'] : 'pager';
		
		//$htmlPage = '<div id="'.$id.'" class="'.$class.'">';	
		$htmlPage = "";
		$htmlPage .= $this->header;
		if( !empty( $buttons ) )
		{
			//$htmlPage .= CHtml::tag('ul',$htmlOptions,implode("\n",$buttons));
			//$htmlPage .= implode("&nbsp;|&nbsp;",$buttons);
			// 修改：取消分割符
			$htmlPage .= implode("",$buttons);
		}
		$htmlPage .= $this->footer;
		$htmlPage .= "</div>";
		return $htmlPage;
	}

	/**
	 * Creates the page buttons.
	 * @return array a list of page buttons (in HTML code).
	 */
	protected function createPageButtons()
	{
		if(($pageCount=$this->getPageCount())<=1)
			return array();
		list($beginPage,$endPage)=$this->getPageRange();
		$currentPage=$this->getCurrentPage(false); // currentPage is calculated in getPageRange()

		$buttons=array();
		// first page
		$buttons[]=$this->createPageButton($this->firstPageLabel,0,self::CSS_FIRST_PAGE,$beginPage<=0,$currentPage == 0);

		// prev page
		if(($page=$currentPage-1)<0)
			$page=0;
		$buttons[]=$this->createPageButton($this->prevPageLabel,$page,self::CSS_PREVIOUS_PAGE,$currentPage<=0,$currentPage == 0);
		
		// internal pages
		for($i=$beginPage;$i<=$endPage;++$i)
			$buttons[]=$this->createPageButton($i+1,$i,self::CSS_INTERNAL_PAGE,false,$i==$currentPage);

		// next page
		if(($page=$currentPage+1)>=$pageCount-1)
			$page=$pageCount-1;
		$buttons[]=$this->createPageButton($this->nextPageLabel,$page,self::CSS_NEXT_PAGE,$currentPage>=$pageCount-1,$currentPage == $pageCount-1);

		// last page
		$buttons[]=$this->createPageButton($this->lastPageLabel,$pageCount-1,self::CSS_LAST_PAGE,$endPage>=$pageCount-1,$currentPage == $pageCount-1);
		
		return $buttons;
	}

	/**
	 * Creates a page button.
	 * You may override this method to customize the page buttons.
	 * @param string the text label for the button
	 * @param integer the page number
	 * @param string the CSS class for the page button. This could be 'page', 'first', 'last', 'next' or 'previous'.
	 * @param boolean whether this page button is visible
	 * @param boolean whether this page button is selected
	 * @return string the generated button
	 */
	protected function createPageButton($label,$page,$class,$hidden,$selected)
	{
		if($hidden || $selected)
			$class.=' '.($hidden ? self::CSS_HIDDEN_PAGE : self::CSS_SELECTED_PAGE);
		$style = "";
		$itemStart = "<a";
		$itemEnd = "</a>";
		// 修改：选择元素根据状态变化
		if( $selected )
		{
			//$style = "font-weight:bold;";
			$itemStart = "<span";
			$itemEnd = "</span>";
		}

		if ( $this->_activeWay === 'LINK' )
			$link = "{$itemStart} class='{$class}' href='".$this->createPageUrl($page)."' style='{$style}'>{$label}{$itemEnd}";
		else if ( $this->_activeWay === 'JS' )
			$link = "{$itemStart} class='{$class}' page='{$page}' href='javascript:;' style='{$style}'>{$label}{$itemEnd}";
		else
			$link = "{$itemStart} class='{$class}' href='".$this->createPageUrl($page)."' style='{$style}'>{$label}{$itemEnd}";

		return $link;
		//return '<li class="'.$class.'"><a href="'.$this->createPageUrl($page).'">'.$label.'</a></li>';
		//return '<li class="'.$class.'">'.CHtml::link($label,$this->createPageUrl($page)).'</li>';
	}

	/**
	 * @return array the begin and end pages that need to be displayed.
	 */
	protected function getPageRange()
	{
		$currentPage=$this->getCurrentPage();
		$pageCount=$this->getPageCount();

		$beginPage=max(0, $currentPage-(int)($this->maxButtonCount/2));
		if(($endPage=$beginPage+$this->maxButtonCount-1)>=$pageCount)
		{
			$endPage=$pageCount-1;
			$beginPage=max(0,$endPage-$this->maxButtonCount+1);
		}
		return array($beginPage,$endPage);
	}

	
//end class
}
