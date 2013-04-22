<?php

/**
 * Base class for Swifki.
 *
 * DO NOT CHANGE THIS UNLESS YOU UPGRADE!
 * Use UserSwifki.php for extending functionality.
 *
 * @category Swifki
 * @package  Swifki
 * @author   Iulian N. <mrjulio@gmail.com>
 * @license  http://iuliann.ro/LICENSE BSD
 * @version  0.1
 * @link     http://wiki.iuliann.ro
 */
class Swifki
{
    /**
     * Used for generating html id's in menu
     *
     * @var int $id
     */
    protected $id  = 0;

    /**
     * Used by GeShi implementation to check if a stylesheet was applied already
     *
     * @var array
     */
    protected $css = array();

    /**
     * Get page to be processed.
     * Use Intro.html as home page
     *
     * @return string
     */
    protected function page()
    {
        return str_replace('..' ,'', urldecode($_SERVER['QUERY_STRING'])) ?: 'Intro.html';
    }

    /**
     * Sort pages in menu
     *
     * @param SplFileInfo  $a  First comparison item
     * @param SplFileInfo  $b  Second comparison item
     * @return int
     */
    public function sortDirectoryIterator($a, $b)
    {
        $nameA = $a->getFilename();
        $nameB = $b->getFilename();
        return ($nameA < $nameB) ? -1 : 1;
    }

    /**
     * Generate menu in the sidebar by recursively iterating inside $dir
     *
     * @param string  $dir   Directory to parse
     * @param string  $path  Path from ./pages/
     *
     * @return array
     */
    public function menu($dir, $path = '')
    {
        $menu = array();

        $iterator = new RecursiveDirectoryIterator($dir);

        $array = array();
        foreach ($iterator as $item) {
            $array[] = $item;
        }

        usort($array, [$this, 'sortDirectoryIterator']);

        /** @var SplFileInfo $file */
        foreach ($array as $file) {
            if ($file->isDir() && $file->getFilename() != '.' && $file->getFilename() != '..') {
                $menu[$file->getFilename()] = $this->menu($file->getPathname(), $path . '/' . $file->getBasename());
            }
            elseif ($file->getFilename() != '.' && $file->getFilename() != '..') {
                $menu[$file->getFilename()] = ltrim($path . '/' . $file->getFilename(), '/');
            }
        }

        return $menu;
    }

    /**
     * Xhtml for menu.
     * $nodes are coming from menu()
     *
     * @param array   $nodes
     * @param string  $page
     *
     * @return string
     */
    public function renderMenu($nodes, $page)
    {
        $xhtml = '';

        if (is_array($nodes)) {
            $xhtml .= "<ul>";
            foreach ($nodes as $name => $node) {
                if (is_array($node)) {
                    $xhtml .= sprintf('<li id="item%d">', $this->id++);
                    $xhtml .= sprintf('<a href="#">%s</a>', $name);
                    $xhtml .= $this->renderMenu($node, $page);
                    $xhtml .= '</li>';
                }
                else {
                    $xhtml .= sprintf('<li title="%s" id="item%d"><a href="/?%s">%s</a></li>', $node, $this->id++, urlencode($node), $name);
                }

            }
            $xhtml .= "</ul>";
        }

        return $xhtml;
    }

    /**
     * Page title.
     *
     * @param string  $page  Page to process
     *
     * @return string
     */
    public function title($page)
    {
        $title = str_replace('/', ' :: ', $page);
        return substr($title, 0, strrpos($title, '.'));
    }

    /**
     * Generating breadcrumbs
     *
     * @param string  $page  Page to process
     *
     * @return string
     */
    public function breadcrumbs($page)
    {
        $bc = explode('/', 'wiki/' . $page);
        $count = count($bc);
        $url = urlencode($page);

        foreach ($bc as $index => $name) {
            if ($index == 0) {
                $bc[$index] = "<a href='/'>wiki</a>";
            }
            elseif ($index == $count-1) {
                $bc[$index] = "<a href='/?{$url}'>{$name}</a>";
            }
            else {
                $bc[$index] = $name;
            }
        }

        return implode(' / ', $bc);
    }

    /**
     * Render method.
     *
     * @return null
     */
    public function render()
    {
        $page = $this->page();

        if (!file_exists(PAGES_PATH . '/' . $page)) {
            header('HTTP/1.1 404 Not Found');
            echo '404 Not Found';
            exit;
        }

        $template = CACHE_PATH . '/' . $page;

        $menuArray   = $this->menu(PAGES_PATH);
        $menu        = $this->renderMenu($menuArray, $page);
        $title       = $this->title($page);
        $breadcrumbs = $this->breadcrumbs($page);

        $this->compile($page);

        if (file_exists($template)) {
            ob_start();
            include 'layout.php';
            ob_get_flush();
        }
    }

    /**
     * Compile method.
     * It is used as a proxy to check whether the cached file exist and modification time is greater than original.
     *
     * @param string  $page  Page to process
     *
     * @return bool
     */
    public function compile($page)
    {
        $templateFile = PAGES_PATH . '/' . $page;
        $compiledFile = CACHE_PATH . '/' . $page;

        if (file_exists($compiledFile) && filemtime($templateFile) < filemtime($compiledFile)) {
            return true;
        }

        // compile
        $extension = strtoupper(substr($page, strrpos($page, '.') + 1));
        $compileMethod = "compile{$extension}";

        if ($extension && method_exists($this, $compileMethod)) {
            return $this->$compileMethod($page);
        }
        else {
            if (!$this->compileCATCHALL($page)) {
                header('HTTP/1.1 404 Not Found');
                echo 'No compilation method found for files with extension: ' . $extension;
                exit;
            }
            else {
                return true;
            }
        }
    }

    /**
     * Compile files with PHP extension
     *
     * @param string  $page  Page to process
     *
     * @return null
     */
    public function compilePHP($page)
    {
        $phpFile = PAGES_PATH . '/' . $page;
        ob_start();
        include $phpFile;
        $data = ob_get_clean();

        $this->writeToCache($page, $data);
    }

    /**
     * Compile files with MD extension
     *
     * @param string  $page  Page to process
     *
     * @return null
     */
    public function compileMD($page)
    {
        require_once __DIR__ . '/markdown/markdown.php';
        require_once __DIR__ . '/geshi/geshi.php';

        $templateFile = PAGES_PATH . '/' . $page;

        $mdContent = Markdown(file_get_contents($templateFile));

        // apply geshi
        preg_match_all('#\<pre class="(?P<lang>.*?)">(?P<pre>.*?)\<\/pre\>#si', $mdContent, $matches);
        foreach ($matches['lang'] as $index => $lang) {
            $html = $matches['pre'][$index];
            $geshi = new GeSHi(trim($html), $lang);
            $geshi->set_header_type(GESHI_HEADER_PRE_TABLE);
            $geshi->enable_classes();
            //$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);

            if (!isset($this->css[$geshi->get_language_name()])) {
                $this->css[$geshi->get_language_name()] = $geshi->get_stylesheet();
            }

            $mdContent = str_replace(
                "<pre class=\"{$lang}\">{$html}</pre>",
                $geshi->parse_code(),
                $mdContent
            );
        }

        $mdContent = '<style>' . implode('', $this->css) . '</style>' . $mdContent;

        $this->writeToCache($page, $mdContent);
    }

    /**
     * Compile files with HTML extension
     *
     * @param string  $page  Page to process
     *
     * @return null
     */
    public function compileHTML($page)
    {
        $templateFile = PAGES_PATH . '/' . $page;

        $this->writeToCache($page, file_get_contents($templateFile));
    }

    /**
     * This method is used as a fallback when there is nothing already defined.
     * Useful for batch processing of multiple files or custom things.
     *
     * @param string  $page  Page to process
     *
     * @return bool True means is has compiled something.
     */
    public function compileCATCHALL($page)
    {
        $ext = substr($page, strrpos($page, '.') + 1);

        if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif'))) {
            $content = sprintf(
                '<img src="data:image/%s;base64,%s" alt="%s" />',
                $ext,
                base64_encode(file_get_contents(PAGES_PATH . "/{$page}")),
                $page
            );

            $this->writeToCache($page, $content);

            return true;
        }

        return false;
    }

    /**
     * Write compiled file to cache
     *
     * @param string  $page  Page to process
     * @param string  $data  Compiled data
     *
     * @return bool
     */
    public function writeToCache($page, $data)
    {
        $dir  = dirname($page);

        if (!file_exists(CACHE_PATH . '/' . $dir)) {
            mkdir(CACHE_PATH . '/' . $dir, 0777, true);
        }

        $data .= $this->appendToCompiled();

        return file_put_contents(CACHE_PATH . '/' . $page, $data);
    }

    /**
     * Append string to compiled template
     *
     * @return string
     */
    protected function appendToCompiled()
    {
        return '<p class="last-modification-time">Last modification time: ' . date(\DateTime::RFC822) . '</p>';
    }
}
