<?php

class Sitemap extends CController {

  protected $webSiteTitle = '';
  protected $changefreq = '';
  protected $content = '';
  protected $priority = '';
  protected $blogItems = array();
  protected $tagItems = array();
  protected $categoryItems = array();
  protected $items = array();
  protected $classify=Posts::CLASSIFY_POST;
  protected $limitPerPage=10000;//每个xml文件的url数量
  protected $pageStart=0;



  /**
   * 添加基本信息
   * @param string $title
   * @param string $link
   * @param string $description
   */
  public function __construct() {
    if(!$this->webSiteTitle){
      $this->webSiteTitle = 'http://' . $_SERVER['SERVER_NAME'];
    } 
    $this->changefreq = 'daily'; //always hourly daily weekly monthly yearly never
    $this->priority = 0.5;
  }

  /**
   * 分类
   */
  private function categorySitemap() {
    $criteria = new CDbCriteria();
    $criteria->condition = 'classify=:category';
    $criteria->params = array(':category' => $this->classify);
    $criteria->limit = $this->limitPerPage;
    $criteria->offset=$this->pageStart;
    $result = Column::model()->findAll($criteria);

    foreach ($result as $k => $v) {
      $this->categoryItems[] = array(
          'url' => $this->webSiteTitle . '/posts/col-' . $v->id.'.html',
          'date' => date(DATE_W3C, time())
      );
    }
  }

  /**
   * 文章
   */
  private function blogSitemap() {
    $criteria = new CDbCriteria();
    $criteria->condition = 'classify='.$this->classify . ' AND status='.Posts::STATUS_PASSED;
    $criteria->select = 'id, cTime';
    $criteria->order = 'id ASC';
    $criteria->limit = $this->limitPerPage;
    $criteria->offset=$this->pageStart;
    $model = Posts::model()->findAll($criteria);
    foreach ($model as $k => $v) {
      $this->blogItems[] = array(
          'url' => $this->webSiteTitle . '/post/' . $v->id.'.html',
          'date' => date(DATE_W3C, $v->cTime)
      );

      //$tagArr = preg_split('#,|，#i', $v->tag);

      if (!empty($tagArr)) {
        foreach ($tagArr as $k => $v) {
          if (!in_array($v, $this->tagItems)) {
            $this->tagItems[] = $v;
          }
        }
      }
    }

    //创建临时函数数组
    $tmp = array();
    $tmp = $this->tagItems;
    $this->tagItems = array();
    foreach ($tmp as $k => $v) {
      $this->tagItems[] = array(
          'url' => $this->webSiteTitle . '/posts/tag-' . $v->id.'.html',
          'date' => date(DATE_W3C, time())
      );
    }
    unset($tmp);
  }

  /**
   * 构建xml元素
   */
  public function buildSitemap() {
    $blogitem = '';
    foreach ($this->blogItems as $k => $v) {
      $blogitem .= <<<BLOG
            <url>\r\n
                <loc>{$v['url']}</loc>\r\n
                <lastmod>{$v['date']}</lastmod>\r\n
                <changefreq>{$this->changefreq}</changefreq>\r\n
                <priority>{$this->priority}</priority>\r\n
            </url>\r\n
BLOG;
    }

    $categoryitem = '';
    foreach ($this->categoryItems as $k => $v) {
      $categoryitem .= <<<BLOG
            <url>\r\n
                <loc>{$v['url']}</loc>\r\n
                <lastmod>{$v['date']}</lastmod>\r\n
                <changefreq>{$this->changefreq}</changefreq>\r\n
                <priority>{$this->priority}</priority>\r\n
            </url>\r\n
BLOG;
    }
    $tagitem = '';
    foreach ($this->tagItems as $k => $v) {
      $tagitem .= <<<BLOG
            <url>\r\n
                <loc>{$v['url']}</loc>\r\n
                <lastmod>{$v['date']}</lastmod>\r\n
                <changefreq>{$this->changefreq}</changefreq>\r\n
                <priority>{$this->priority}</priority>\r\n
            </url>\r\n
BLOG;
    }


    $this->content = <<<SITEMAP
<?xml version='1.0' encoding='UTF-8'?>\r\n
<?xml-stylesheet type="text/xsl" href="{$this->webSiteTitle}/sitemap.xsl"?>
<!-- generator="NewSoul.cn" -->
<!-- sitemap-generator-url="{$this->webSiteTitle}" sitemap-generator-version="1.0.0" -->
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"\r\nxsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"\r\nxmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\r\n{$blogitem}{$categoryitem}{$tagitem}</urlset>\r\n
SITEMAP;
  }

  /**
   * 输出sitemap内容
   */
  function show($type=  Posts::CLASSIFY_POST,$start='',$limit='') {
    if($start)$this->pageStart=$start;
    if($limit)$this->limitPerPage=$limit;
    if($type==Posts::CLASSIFY_POST){
      $this->webSiteTitle=zmf::config('domain');
      $this->classify=  Posts::CLASSIFY_POST;
    }else{
      $this->webSiteTitle=zmf::config('blog_domain');
      $this->classify=  Posts::CLASSIFY_BLOG;
    }    
    $this->blogSitemap();
    $this->categorySitemap();
    if (empty($this->content)) {
      $this->buildSitemap();
    }
    return $this->content;
  }

  /**
   * 将rss保存为文件
   * @param String $fname
   * @return boolean
   */
  function saveToFile($fname) {
    $handle = fopen($fname, 'wb');
    if ($handle === false) {
      return false;
    }
    fwrite($handle, $this->content);
    fclose($handle);
  }

  /**
   * 获取文件的内容
   * @param String $fname
   * @return boolean
   */
  function getFile($fname) {
    $handle = fopen($fname, 'r');
    if ($handle === false) {
      return false;
    }
    while (!feof($handle)) {
      echo fgets($handle);
    }
    fclose($handle);
  }

}

?>